@extends('layouts.app')

@section('page-title', 'Préstamos')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="header-table" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 style="color: var(--primary); font-size: 32px;">Lista de Préstamos a Trabajadores</h2>
            <a href="{{ route('loans.create') }}" class="btn-login" style="
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
            <i class="fas fa-plus"></i> Nuevo Préstamo
            </a>
        </div>

        <!-- Búsqueda y filtro por estado -->
        <form method="GET" action="{{ route('loans.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por trabajador o DNI..." 
                       style="flex: 1; min-width: 250px; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <select name="status" style="padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pagado" {{ request('status') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>

                <button type="submit" class="btn-login" style="padding: 14px 25px;">
                    <i class="fas fa-search"></i> Filtrar
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('loans.index') }}" class="btn-login" style="background: #666; padding: 14px 20px;">
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
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Fecha</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Trabajador</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Monto</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Cuotas</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Valor Cuota</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Pagado</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Pendiente</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Estado</th>
                        <th style="padding: 15px; text-align: center; color: var(--primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $loan->person->getFullNameAttribute() }}</td>
                            <td style="padding: 15px; color: var(--text-light);">S/ {{ number_format($loan->amount, 2) }}</td>
                            <td style="padding: 15px; color: var(--text-light); text-align: center;">{{ $loan->installments }}</td>
                            <td style="padding: 15px; color: var(--text-light);">S/ {{ number_format($loan->installment_amount, 2) }}</td>
                            <td style="padding: 15px; color: #0f0;">S/ {{ number_format($loan->paid_amount, 2) }}</td>
                            <td style="padding: 15px; color: #f66; font-weight: 700; font-size: 18px;">
                                S/ {{ number_format($loan->pending_amount, 2) }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="padding: 8px 15px; border-radius: 20px; font-size: 13px; font-weight: 700;
                                    @switch($loan->status)
                                        @case('activo') background: rgba(200,200,0,0.3); color: #ff0; @break
                                        @case('pagado') background: rgba(0,200,0,0.3); color: #0f0; @break
                                        @case('cancelado') background: rgba(200,0,0,0.3); color: #f66; @break
                                    @endswitch">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('loans.edit', $loan) }}" style="color: var(--primary); margin: 0 10px;">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('loans.destroy', $loan) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f66; cursor: pointer;" 
                                            onclick="return confirm('¿Seguro que quieres eliminar este préstamo?')">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay préstamos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $loans->appends(request()->query())->links() }}
        </div>
    </div>
@endsection