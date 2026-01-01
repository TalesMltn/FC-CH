<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Payroll::select('payment_date', 'person_id', 'year', 'month', 'base_salary', 'bonus', 'discounts', 'loan_deduction', 'total_paid', 'payment_method')
            ->with('person')
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(function ($payroll) {
                return [
                    'Fecha Pago' => $payroll->payment_date->format('d/m/Y'),
                    'Trabajador' => $payroll->person->getFullNameAttribute(),
                    'DNI' => $payroll->person->dni,
                    'Período' => \DateTime::createFromFormat('!m', $payroll->month)->format('F') . ' ' . $payroll->year,
                    'Salario Base' => $payroll->base_salary,
                    'Bono' => $payroll->bonus,
                    'Descuentos' => $payroll->discounts,
                    'Deducción Préstamo' => $payroll->loan_deduction,
                    'Total Pagado' => $payroll->total_paid,
                    'Método de Pago' => ucfirst($payroll->payment_method),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha Pago',
            'Trabajador',
            'DNI',
            'Período',
            'Salario Base',
            'Bono',
            'Descuentos',
            'Deducción Préstamo',
            'Total Pagado',
            'Método de Pago',
        ];
    }
}