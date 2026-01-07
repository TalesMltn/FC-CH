<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Contract;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $transactions = Transaction::with('contract.person')
            ->with(['contract' => function ($query) {
                $query->withSum('transactions as total_pagado', 'amount');
            }])
            ->when($search, function ($query, $search) {
                $query->whereHas('contract', fn($q) => $q->where('code', 'like', "%{$search}%"))
                      ->orWhere('reference', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $contracts = Contract::with('person')
            ->withSum('transactions as total_pagado', 'amount')
            ->where('status', '!=', 'cancelado')
            ->where('status', '!=', 'pagado')
            ->orderBy('code', 'desc')
            ->get();

        return view('transactions.create', compact('contracts'));
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
        // Cargar todos los contratos activos (o los que quieras mostrar)
        $contracts = Contract::orderBy('code')->get(); // Ajusta según tus necesidades
    
        return view('transactions.edit', compact('transaction', 'contracts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'payment_method' => 'required|in:efectivo,transferencia,yape,plin,tarjeta,cheque,otro',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($request->all());

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