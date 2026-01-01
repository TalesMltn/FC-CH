<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::select('date', 'contract_id', 'amount', 'payment_method', 'reference')
            ->with('contract.person')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'Fecha' => $transaction->date->format('d/m/Y'),
                    'Código Contrato' => $transaction->contract->code,
                    'Cliente' => $transaction->contract->person->getFullNameAttribute(),
                    'Monto' => $transaction->amount,
                    'Método de Pago' => ucfirst($transaction->payment_method),
                    'Referencia' => $transaction->reference ?? 'Sin referencia',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Código Contrato',
            'Cliente',
            'Monto',
            'Método de Pago',
            'Referencia',
        ];
    }
}