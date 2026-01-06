<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // opcional, agrégalo si quieres protegerlo
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(10)
        ->appends(['search' => $search]);

        return view('categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // CORREGIMOS EL CHECKBOX ANTES DE VALIDAR
        $data = $request->all();
        $data['active'] = $request->has('active'); // true si marcado, false si no

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ], $data); // validamos con los datos corregidos

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // CORREGIMOS EL CHECKBOX ANTES DE VALIDAR
        $data = $request->all();
        $data['active'] = $request->has('active');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'active' => 'boolean',
        ], $data);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente');
    }
}