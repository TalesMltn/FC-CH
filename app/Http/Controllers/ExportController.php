<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Transaction;
use App\Models\Payroll;
use App\Models\Loan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContractsExport;
use App\Exports\TransactionsExport;
use App\Exports\PayrollsExport;
use App\Exports\LoansExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index()
    {
        return view('exports.index');
    }

    // Exportar a Excel
    public function contractsExcel()
    {
        return Excel::download(new ContractsExport, 'ventas_contratos_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function transactionsExcel()
    {
        return Excel::download(new TransactionsExport, 'cobros_transacciones_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function payrollsExcel()
    {
        return Excel::download(new PayrollsExport, 'nomina_pagos_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function loansExcel()
    {
        return Excel::download(new LoansExport, 'prestamos_' . now()->format('Y-m-d') . '.xlsx');
    }

    // Exportar a PDF
    public function contractsPdf()
    {
        $contracts = Contract::with('person', 'category')->orderBy('date', 'desc')->get();
        $total = $contracts->sum('total');

        $pdf = Pdf::loadView('exports.contracts_pdf', compact('contracts', 'total'));
        return $pdf->download('reporte_ventas_' . now()->format('Y-m-d') . '.pdf');
    }

    public function transactionsPdf()
    {
        $transactions = Transaction::with('contract.person')->orderBy('date', 'desc')->get();
        $total = $transactions->sum('amount');

        $pdf = Pdf::loadView('exports.transactions_pdf', compact('transactions', 'total'));
        return $pdf->download('reporte_cobros_' . now()->format('Y-m-d') . '.pdf');
    }

    public function payrollsPdf()
    {
        $payrolls = Payroll::with('person')->orderBy('payment_date', 'desc')->get();
        $total = $payrolls->sum('total_paid');

        $pdf = Pdf::loadView('exports.payrolls_pdf', compact('payrolls', 'total'));
        return $pdf->download('reporte_nomina_' . now()->format('Y-m-d') . '.pdf');
    }

    public function loansPdf()
    {
        $loans = Loan::with('person')->orderBy('loan_date', 'desc')->get();
        $total = $loans->sum('amount');
        $pendiente = $loans->sum('pending_amount');

        $pdf = Pdf::loadView('exports.loans_pdf', compact('loans', 'total', 'pendiente'));
        return $pdf->download('reporte_prestamos_' . now()->format('Y-m-d') . '.pdf');
    }
}