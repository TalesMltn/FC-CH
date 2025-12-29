@extends('layouts.app')

@section('page-title', 'Personas')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="header-table" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 style="color: var(--primary); font-size: 32px; margin: 0;">Lista de Personas</h2>
            
            <a href="{{ route('persons.create') }}" class="btn-login" style="
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 25px;
                background-color: var(--primary);
                color: white;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 500;
                white-space: nowrap;
            ">
                <i class="fas fa-plus"></i> Nueva Persona
            </a>
        </div>

        <!-- Filtros y búsqueda -->
        <form method="GET" action="{{ route('persons.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por DNI, nombre o apellido..." 
                       style="flex: 1; min-width: 250px; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <select name="type" style="padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: var(--primary); font-size: 16px; cursor: pointer;">
                    <option value="" style="color: var(--text-light);">Todos los tipos</option>
                    <option value="trabajador" {{ request('type') == 'trabajador' ? 'selected' : '' }} style="color: black;">Trabajador</option>
                    <option value="cliente" {{ request('type') == 'cliente' ? 'selected' : '' }} style="color: black;">Cliente</option>
                    <option value="proveedor" {{ request('type') == 'proveedor' ? 'selected' : '' }} style="color: black;">Proveedor</option>
                    <option value="otro" {{ request('type') == 'otro' ? 'selected' : '' }} style="color: black;">Otro</option>
                </select>

                <button type="submit" class="btn-login" style="padding: 14px 25px; font-size: 16px;">
                    <i class="fas fa-search"></i> Filtrar
                </button>

                @if(request('search') || request('type'))
                    <a href="{{ route('persons.index') }}" class="btn-login" style="background: #666; padding: 14px 20px;">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>

        @if(session('success'))
            <div style="background: rgba(0, 200, 0, 0.2); color: #0f0; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #0f0;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray);">
                        <th style="padding: 15px; text-align: left; color: var(--primary);">DNI</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Nombre Completo</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Tipo</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Teléfono</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Estado</th>
                        <th style="padding: 15px; text-align: center; color: var(--primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($persons as $person)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $person->dni }}</td>
                            <td style="padding: 15px; color: white;">{{ $person->getFullNameAttribute() }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
                                    @switch($person->type)
                                        @case('trabajador') background: rgba(0,100,200,0.3); color: #66f; @break
                                        @case('cliente') background: rgba(0,200,0,0.3); color: #0f0; @break
                                        @case('proveedor') background: rgba(200,100,0,0.3); color: #fc6; @break
                                        @default background: rgba(100,100,100,0.3); color: #aaa;
                                    @endswitch">
                                    {{ ucfirst($person->type) }}
                                </span>
                            </td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $person->phone ?? 'Sin teléfono' }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
                                    {{ $person->active ? 'background: rgba(0,200,0,0.3); color: #0f0;' : 'background: rgba(200,0,0,0.3); color: #f66;' }}">
                                    {{ $person->active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('persons.edit', $person) }}" style="color: var(--primary); margin: 0 10px;">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('persons.destroy', $person) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f66; cursor: pointer;" 
                                            onclick="return confirm('¿Seguro que quieres eliminar esta persona?')">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay personas registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $persons->appends(request()->query())->links() }}
        </div>
    </div>
@endsection