<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Person;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('person')
            ->when($search, function ($query, $search) {
                return $query->whereHas('person', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('lastname', 'like', '%' . $search . '%')
                      ->orWhere('dni', 'like', '%' . $search . '%');
                });
            })
            ->when($month, function ($query, $month) {
                return $query->where('month', $month);
            })
            ->where('year', $year)
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        return view('payrolls.index', compact('payrolls', 'search', 'month', 'year'));
    }

    public function create()
    {
        $workers = Person::where('type', 'trabajador')
            ->where('active', true)
            ->orderBy('lastname')
            ->get();

        return view('payrolls.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'payment_date' => 'required|date',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'discounts' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:efectivo,transferencia,yape,plin,otro',
            'notes' => 'nullable|string',
        ]);

        Payroll::create($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Pago de nómina registrado correctamente');
    }

    public function edit(Payroll $payroll)
    {
        $workers = Person::where('type', 'trabajador')
            ->where('active', true)
            ->orderBy('lastname')
            ->get();

        return view('payrolls.edit', compact('payroll', 'workers'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'payment_date' => 'required|date',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'discounts' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:efectivo,transferencia,yape,plin,otro',
            'notes' => 'nullable|string',
        ]);

        $payroll->update($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Pago de nómina actualizado correctamente');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Pago de nómina eliminado correctamente');
    }
}