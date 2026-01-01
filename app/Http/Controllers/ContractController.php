<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Person;
use App\Models\Category;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $contracts = Contract::with(['person', 'category'])
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', '%' . $search . '%')
                             ->orWhereHas('person', function ($q) use ($search) {
                                 $q->where('name', 'like', '%' . $search . '%')
                                   ->orWhere('lastname', 'like', '%' . $search . '%')
                                   ->orWhere('dni', 'like', '%' . $search . '%');
                             });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('date', 'desc')
            ->orderBy('code', 'desc')
            ->paginate(10);

        return view('contracts.index', compact('contracts', 'search', 'status'));
    }

    public function create()
    {
        $clients = Person::where('type', 'cliente')->where('active', true)->orderBy('lastname')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();
        $code = Contract::generateCode();

        return view('contracts.create', compact('clients', 'categories', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_m3' => 'required|numeric|min:0',
            'delivery_date' => 'nullable|date|after_or_equal:date',
            'status' => 'required|in:pendiente,en_produccion,entregado,pagado,cancelado',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['code'] = Contract::generateCode();

        Contract::create($data);

        return redirect()->route('contracts.index')
            ->with('success', 'Contrato creado correctamente');
    }

    public function edit(Contract $contract)
    {
        $clients = Person::where('type', 'cliente')->where('active', true)->orderBy('lastname')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('contracts.edit', compact('contract', 'clients', 'categories'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_m3' => 'required|numeric|min:0',
            'delivery_date' => 'nullable|date|after_or_equal:date',
            'status' => 'required|in:pendiente,en_produccion,entregado,pagado,cancelado',
            'notes' => 'nullable|string',
        ]);

        $contract->update($request->all());

        return redirect()->route('contracts.index')
            ->with('success', 'Contrato actualizado correctamente');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contrato eliminado correctamente');
    }
}