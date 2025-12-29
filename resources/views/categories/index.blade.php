@extends('layouts.app')

@section('page-title', 'Categorías')

@section('content')
<div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: var(--primary); font-size: 32px; margin: 0;">Lista de Categorías</h2>
        
        <a href="http://127.0.0.1:8000/categories/create" class="btn-login" style="
            width: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            text-decoration: none;
            background-color: var(--primary); /* o el color que uses para botones */
            color: white;
            border-radius: 6px; /* opcional */
            font-weight: 500;
        ">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

        <!-- Búsqueda -->
        <form method="GET" action="{{ route('categories.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 10px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o descripción..." 
                       style="flex: 1; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                <button type="submit" class="btn-login" style="padding: 14px 25px;">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="btn-login" style="background: #666; padding: 14px 20px;">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div style="background: rgba(0, 200, 0, 0.2); color: #0f0; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #0f0;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray);">
                        <th style="padding: 15px; text-align: left; color: var(--primary);">ID</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Nombre</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Descripción</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Estado</th>
                        <th style="padding: 15px; text-align: center; color: var(--primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $category->id }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $category->name }}</td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $category->description ?? 'Sin descripción' }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
                                    {{ $category->active ? 'background: rgba(0,200,0,0.3); color: #0f0;' : 'background: rgba(200,0,0,0.3); color: #f66;' }}">
                                    {{ $category->active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('categories.edit', $category) }}" style="color: var(--primary); margin: 0 10px;">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f66; cursor: pointer;" 
                                            onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay categorías registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div style="margin-top: 30px;">
            {{ $categories->links() }}
        </div>
    </div>
@endsection