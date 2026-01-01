<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContractsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Contract::select('code', 'date', 'person_id', 'category_id', 'quantity', 'price_per_m3', 'total', 'status')
            ->with('person', 'category')
            ->get()
            ->map(function ($contract) {
                return [
                    'Código' => $contract->code,
                    'Fecha' => $contract->date->format('d/m/Y'),
                    'Cliente' => $contract->person->getFullNameAttribute(),
                    'Concreto' => $contract->category->name,
                    'Cantidad (m³)' => $contract->quantity,
                    'Precio/m³' => $contract->price_per_m3,
                    'Total' => $contract->total,
                    'Estado' => ucfirst(str_replace('_', ' ', $contract->status)),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Fecha',
            'Cliente',
            'Concreto',
            'Cantidad (m³)',
            'Precio/m³',
            'Total',
            'Estado',
        ];
    }
}