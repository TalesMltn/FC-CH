<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Transaction;
use App\Models\Payroll;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Resumen general
        $totalVentas = Contract::sum('total');
        $totalCobrado = Transaction::sum('amount');
        $totalPendienteCobro = $totalVentas - $totalCobrado;

        $totalNomina = Payroll::sum('total_paid');
        $totalPrestamos = Loan::sum('amount');
        $totalPagadoPrestamos = Loan::sum('paid_amount');
        $totalPendientePrestamos = $totalPrestamos - $totalPagadoPrestamos;

        $contratosMes = Contract::whereMonth('date', now()->month)->count();
        $pagosMes = Transaction::whereMonth('date', now()->month)->sum('amount');
        $nominaMes = Payroll::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('total_paid');

        return view('reports.index', compact(
            'totalVentas',
            'totalCobrado',
            'totalPendienteCobro',
            'totalNomina',
            'totalPrestamos',
            'totalPagadoPrestamos',
            'totalPendientePrestamos',
            'contratosMes',
            'pagosMes',
            'nominaMes'
        ));
    }

    public function sales()
    {
        $sales = Contract::with('person', 'category')
            ->orderBy('date', 'desc')
            ->paginate(15);

        $totalVentas = Contract::sum('total');

        return view('reports.sales', compact('sales', 'totalVentas'));
    }

    public function payments()
    {
        $payments = Transaction::with('contract.person')
            ->orderBy('date', 'desc')
            ->paginate(15);

        $totalCobrado = Transaction::sum('amount');

        return view('reports.payments', compact('payments', 'totalCobrado'));
    }

    public function payroll()
    {
        $payrolls = Payroll::with('person')
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        $totalNomina = Payroll::sum('total_paid');

        return view('reports.payroll', compact('payrolls', 'totalNomina'));
    }

    public function loans()
    {
        $loans = Loan::with('person')
            ->orderBy('loan_date', 'desc')
            ->paginate(15);

        $totalPrestamos = Loan::sum('amount');
        $totalPendiente = Loan::sum('pending_amount');

        return view('reports.loans', compact('loans', 'totalPrestamos', 'totalPendiente'));
    }
}