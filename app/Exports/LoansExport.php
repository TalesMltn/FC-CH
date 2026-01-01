<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoansExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Loan::select('loan_date', 'person_id', 'amount', 'installments', 'installment_amount', 'paid_amount', 'pending_amount', 'status')
            ->with('person')
            ->orderBy('loan_date', 'desc')
            ->get()
            ->map(function ($loan) {
                return [
                    'Fecha Préstamo' => $loan->loan_date->format('d/m/Y'),
                    'Trabajador' => $loan->person->getFullNameAttribute(),
                    'DNI' => $loan->person->dni,
                    'Monto Otorgado' => $loan->amount,
                    'Número de Cuotas' => $loan->installments,
                    'Valor por Cuota' => $loan->installment_amount,
                    'Pagado' => $loan->paid_amount,
                    'Pendiente' => $loan->pending_amount,
                    'Estado' => ucfirst($loan->status),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha Préstamo',
            'Trabajador',
            'DNI',
            'Monto Otorgado',
            'Número de Cuotas',
            'Valor por Cuota',
            'Pagado',
            'Pendiente',
            'Estado',
        ];
    }
}