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
            ->when($search, function ($query, $search) {
                return $query->whereHas('contract', function ($q) use ($search) {
                    $q->where('code', 'like', '%' . $search . '%');
                })->orWhere('reference', 'like', '%' . $search . '%');
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('transactions.index', compact('transactions', 'search'));
    }

    public function create()
    {
        $contracts = Contract::with('person')
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

        Transaction::create($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción registrada correctamente');
    }

    public function edit(Transaction $transaction)
    {
        $contracts = Contract::with('person')->orderBy('code', 'desc')->get();

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

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción actualizada correctamente');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción eliminada correctamente');
    }
}