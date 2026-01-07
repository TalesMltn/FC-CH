<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Contract;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['contract.person', 'contract.transactions'])
            ->latest('date')
            ->latest('id');
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('contract', fn($q) => $q->where('code', 'like', "%$search%"))
                  ->orWhere('reference', 'like', "%$search%");
        }
    
        $transactions = $query->paginate(15);
    
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $contracts = Contract::with('person')
            ->withSum('transactions as total_pagado', 'amount')
            ->where('status', '!=', 'cancelado')
            ->get()
            ->filter(function ($contract) {
                return $contract->total > ($contract->total_pagado ?? 0);
            })
            ->sortByDesc('code');
    
        // Fecha por defecto: hoy
        $defaultDate = now()->format('Y-m-d');
    
        // Contar cuántos pagos hay en esta fecha
        $countToday = Transaction::whereDate('date', $defaultDate)->count();
    
        // Generar referencia secuencial
        $nextNumber = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
        $reference = "OP-" . now()->format('Ymd') . "-" . $nextNumber;
    
        return view('transactions.create', compact('contracts', 'reference'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'payment_method' => 'required|in:efectivo,transferencia,yape,plin,tarjeta,cheque,otro',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $transaction = Transaction::create($request->all());

        $this->actualizarEstadoContrato($transaction->contract_id);

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción registrada correctamente');
    }
    public function edit(Transaction $transaction)
    {
        $contracts = Contract::with('person')
            ->withSum('transactions as total_pagado', 'amount')
            ->where('status', '!=', 'cancelado')
            ->orderBy('code', 'desc')
            ->get();

        return view('transactions.form', compact('transaction', 'contracts'));
    }
        
  
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'payment_method' => 'required|in:efectivo,transferencia,yape,plin,tarjeta,cheque,otro',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
    
        $contract = Contract::withSum('transactions as total_pagado', 'amount')
            ->findOrFail($transaction->contract_id);
    
        // deuda REAL (como create)
        $pagadoSinEsta = ($contract->total_pagado ?? 0) - $transaction->amount;
        $deudaReal = max(0, $contract->total - $pagadoSinEsta);
    
        if ($request->amount > $deudaReal) {
            return back()
                ->withErrors(['amount' => 'El monto excede la deuda pendiente'])
                ->withInput();
        }
    
        $transaction->update($request->only([
            'amount',
            'date',
            'payment_method',
            'reference',
            'notes',
        ]));
    
        $this->actualizarEstadoContrato($transaction->contract_id);
    
        return redirect()->route('transactions.index')
            ->with('success', 'Transacción actualizada correctamente');
    }

    public function destroy(Transaction $transaction)
    {
        $contratoId = $transaction->contract_id;

        $transaction->delete();

        $this->actualizarEstadoContrato($contratoId);

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción eliminada correctamente');
    }

    private function actualizarEstadoContrato($contractId)
    {
        $contract = Contract::findOrFail($contractId);

        $totalPagado = $contract->transactions()->sum('amount');
        $saldoPendiente = $contract->total - $totalPagado;

        if ($saldoPendiente <= 0) {
            $contract->status = 'pagado';
        } else {
            if ($contract->status === 'pagado') {
                $contract->status = 'pendiente';
            }
        }

        $contract->save();
    }
}