<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $persons = Person::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('lastname', 'like', "%{$search}%")
                             ->orWhere('dni', 'like', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('lastname')
            ->orderBy('name')
            ->paginate(12)
            ->appends($request->query());

        return view('persons.index', compact('persons', 'search', 'type'));
    }

    public function create()
    {
        return view('persons.create');
    }

    public function store(Request $request)
    {
        // Normalizamos el checkbox active: si no viene marcado → false
        $data = $request->all();
        $data['active'] = $request->has('active');

        $validated = validator($data, [
            'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:persons,dni',
            'name' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'lastname' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+()]*$/',
            'email' => 'nullable|email|max:255|unique:persons,email',
            'type' => 'required|in:trabajador,cliente,proveedor,otro',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ])->validate();

        Person::create($validated);

        return redirect()->route('persons.index')
            ->with('success', 'Persona creada correctamente');
    }

    public function edit(Person $person)
    {
        return view('persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $data = $request->all();
        $data['active'] = $request->has('active');

        $validated = validator($data, [
            'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/|unique:persons,dni,' . $person->id,
            'name' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'lastname' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+()]*$/',
            'email' => 'nullable|email|max:255|unique:persons,email,' . $person->id,
            'type' => 'required|in:trabajador,cliente,proveedor,otro',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ])->validate();

        $person->update($validated);

        return redirect()->route('persons.index')
            ->with('success', 'Persona actualizada correctamente');
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('persons.index')
            ->with('success', 'Persona eliminada correctamente');
    }
}