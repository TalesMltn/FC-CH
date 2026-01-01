<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Person;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $loans = Loan::with('person')
            ->when($search, function ($query, $search) {
                return $query->whereHas('person', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('lastname', 'like', '%' . $search . '%')
                      ->orWhere('dni', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('loan_date', 'desc')
            ->paginate(12);

        return view('loans.index', compact('loans', 'search', 'status'));
    }

    public function create()
    {
        $workers = Person::where('type', 'trabajador')
            ->where('active', true)
            ->orderBy('lastname')
            ->get();

        return view('loans.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'loan_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'installments' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        Loan::create($request->all());

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo registrado correctamente');
    }

    public function edit(Loan $loan)
    {
        $workers = Person::where('type', 'trabajador')
            ->where('active', true)
            ->orderBy('lastname')
            ->get();

        return view('loans.edit', compact('loan', 'workers'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'loan_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'installments' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        $loan->update($request->all());

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo actualizado correctamente');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo eliminado correctamente');
    }
}