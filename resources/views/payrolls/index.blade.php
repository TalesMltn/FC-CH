@extends('layouts.app')

@section('page-title', 'Nómina')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="header-table" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 style="color: var(--primary); font-size: 32px;">Registro de Nómina</h2>
            <a href="{{ route('payrolls.create') }}" class="btn-login" style="
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
            <i class="fas fa-plus"></i> Nuevo Pago de Nómina
            </a>
        </div>

        <!-- Filtros: búsqueda, mes y año -->
        <form method="GET" action="{{ route('payrolls.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por DNI, nombre o apellido..." 
                       style="flex: 1; min-width: 250px; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <select name="month" style="padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                    <option value="">Todos los meses</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>

                <input type="number" name="year" value="{{ request('year', date('Y')) }}" min="2020" max="2030" placeholder="Año"
                       style="width: 120px; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <button type="submit" class="btn-login" style="padding: 14px 25px;">
                    <i class="fas fa-search"></i> Filtrar
                </button>

                @if(request('search') || request('month') || request('year'))
                    <a href="{{ route('payrolls.index') }}" class="btn-login" style="background: #666; padding: 14px 20px;">
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
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Fecha Pago</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Trabajador</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Período</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Salario Base</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Bono</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Descuentos</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Total Pagado</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Método</th>
                        <th style="padding: 15px; text-align: center; color: var(--primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $payroll->payment_date->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $payroll->person->getFullNameAttribute() }}</td>
                            <td style="padding: 15px; color: var(--text-light);">
                                {{ \DateTime::createFromFormat('!m', $payroll->month)->format('F') }} {{ $payroll->year }}
                            </td>
                            <td style="padding: 15px; color: var(--text-light);">S/ {{ number_format($payroll->base_salary, 2) }}</td>
                            <td style="padding: 15px; color: #0f0;">+ S/ {{ number_format($payroll->bonus, 2) }}</td>
                            <td style="padding: 15px; color: #f66;">- S/ {{ number_format($payroll->discounts + $payroll->loan_deduction, 2) }}</td>
                            <td style="padding: 15px; color: #0f0; font-weight: 700; font-size: 18px;">
                                S/ {{ number_format($payroll->total_paid, 2) }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                    @switch($payroll->payment_method)
                                        @case('efectivo') <i class="fas fa-money-bill-wave" style="color: #0f0;"></i> Efectivo @break
                                        @case('transferencia') <i class="fas fa-university" style="color: #66f;"></i> Transferencia @break
                                        @case('yape') <i class="fas fa-mobile-alt" style="color: #9f3;"></i> Yape @break
                                        @case('plin') <i class="fas fa-mobile-alt" style="color: #f39;"></i> Plin @break
                                        @default <i class="fas fa-question" style="color: #ccc;"></i> Otro @break
                                    @endswitch
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('payrolls.edit', $payroll) }}" style="color: var(--primary); margin: 0 10px;">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f66; cursor: pointer;" 
                                            onclick="return confirm('¿Seguro que quieres eliminar este pago de nómina?')">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay pagos de nómina registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $payrolls->appends(request()->query())->links() }}
        </div>
    </div>
@endsection