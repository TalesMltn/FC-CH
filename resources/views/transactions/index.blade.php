@extends('layouts.app')

@section('page-title', 'Transacciones')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="header-table" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 style="color: var(--primary); font-size: 32px; margin: 0;">Registro de Pagos y Transacciones</h2>
            <a href="{{ route('transactions.create') }}" class="btn-login" style="
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 25px;
                background: var(--primary);
                color: white;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 16px;
                transition: all 0.3s;
                box-shadow: 0 4px 15px rgba(255,107,0,0.3);
            ">
                <i class="fas fa-plus"></i> Nueva Transacción
            </a>
        </div>

        <!-- Búsqueda -->
        <form method="GET" action="{{ route('transactions.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por código de contrato o referencia..." 
                       style="flex: 1; min-width: 280px; padding: 14px 18px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <button type="submit" class="btn-login" style="padding: 14px 28px; border-radius: 12px;">
                    <i class="fas fa-search"></i> Buscar
                </button>

                @if(request('search'))
                    <a href="{{ route('transactions.index') }}" class="btn-login" style="background: #555; padding: 14px 24px; border-radius: 12px; color: white;">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div style="background: rgba(0, 200, 0, 0.25); color: #0f0; padding: 16px; border-radius: 12px; margin-bottom: 24px; border-left: 5px solid #0f0; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla -->
        <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="background: rgba(255,102,0,0.15);">
                        <th style="padding: 16px; text-align: left; color: var(--primary); font-weight: 700; font-size: 15px;">Fecha</th>
                        <th style="padding: 16px; text-align: left; color: var(--primary); font-weight: 700; font-size: 15px;">Contrato</th>
                        <th style="padding: 16px; text-align: left; color: var(--primary); font-weight: 700; font-size: 15px;">Cliente</th>
                        <th style="padding: 16px; text-align: center; color: var(--primary); font-weight: 700; font-size: 15px;">Detalle de Pago y Saldo</th>
                        <th style="padding: 16px; text-align: left; color: var(--primary); font-weight: 700; font-size: 15px;">Método</th>
                        <th style="padding: 16px; text-align: left; color: var(--primary); font-weight: 700; font-size: 15px;">Referencia</th>
                        <th style="padding: 16px; text-align: center; color: var(--primary); font-weight: 700; font-size: 15px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.15); transition: background 0.3s;">
                            <td style="padding: 16px; color: var(--text-light); font-size: 15px;">{{ $transaction->date->format('d/m/Y') }}</td>
                            <td style="padding: 16px; color: white; font-weight: 700; font-size: 16px;">{{ $transaction->contract->code }}</td>
                            <td style="padding: 16px; color: var(--text-light); font-size: 15px;">{{ $transaction->contract->person->getFullNameAttribute() }}</td>

                            <!-- DETALLE DE PAGO Y SALDO - CÁLCULO CORREGIDO Y FORMATO INTACTO -->
                            <td style="padding: 12px; text-align: center;">
                                @php
                                    // Carga fresca del contrato (evita caché malo)
                                    $contract = App\Models\Contract::find($transaction->contract_id);

                                    // Suma SOLO pagos ANTERIORES (excluye el actual)
                                    $pagadoAntes = $contract->transactions()
                                        ->where('id', '!=', $transaction->id)  // excluye el pago actual
                                        ->where(function($query) use ($transaction) {
                                            $query->where('date', '<', $transaction->date)
                                                  ->orWhere(function($q) use ($transaction) {
                                                      $q->where('date', '=', $transaction->date)
                                                        ->where('id', '<', $transaction->id);
                                                  });
                                        })
                                        ->where('amount', '>', 0)
                                        ->sum('amount');

                                    $debiaAntes = (float)$contract->total - (float)$pagadoAntes;
                                    $saldoFinal = max(0, $debiaAntes - (float)$transaction->amount);
                                @endphp

                                <div style="display: inline-flex; align-items: center; gap: 10px; padding: 8px 12px; background: rgba(255,102,0,0.08); border-radius: 10px;">
                                    <span style="color: #ffcc66; font-weight: 700;">S/ {{ number_format($debiaAntes, 2) }}</span>
                                    <span style="color: #aaa; font-size: 14px;">−</span>
                                    <span style="color: #22c55e; font-weight: 700;">S/ {{ number_format($transaction->amount, 2) }}</span>
                                    <span style="color: #aaa; font-size: 14px;">=</span>
                                    <span style="color: white; font-weight: 800; font-size: 16px;">S/ {{ number_format($saldoFinal, 2) }}</span>

                                    <span style="
                                        font-size: 11px;
                                        padding: 4px 10px;
                                        border-radius: 20px;
                                        font-weight: 700;
                                        background: {{ $saldoFinal <= 0 ? 'rgba(34,197,94,0.3)' : 'rgba(255,204,102,0.3)' }};
                                        color: {{ $saldoFinal <= 0 ? '#22c55e' : '#ffcc66' }};
                                    ">
                                        {{ $saldoFinal <= 0 ? 'Pagado' : 'Pendiente' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Método de pago con íconos -->
                            <td style="padding: 16px;">
                                <span style="padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.12); display: inline-flex; align-items: center; gap: 8px;">
                                    @switch($transaction->payment_method)
                                        @case('efectivo') <i class="fas fa-money-bill-wave" style="color: #0f8;"></i> Efectivo @break
                                        @case('transferencia') <i class="fas fa-university" style="color: #66f;"></i> Transferencia @break
                                        @case('yape') <i class="fas fa-mobile-alt" style="color: #a0f;"></i> Yape @break
                                        @case('plin') <i class="fas fa-mobile-alt" style="color: #f6a;"></i> Plin @break
                                        @case('tarjeta') <i class="fas fa-credit-card" style="color: #fc6;"></i> Tarjeta @break
                                        @case('cheque') <i class="fas fa-file-invoice-dollar" style="color: #ccc;"></i> Cheque @break
                                        @default <i class="fas fa-question-circle" style="color: #999;"></i> Otro @break
                                    @endswitch
                                </span>
                            </td>

                            <td style="padding: 16px; color: var(--text-light); font-size: 15px;">
                                {{ $transaction->reference ?? '<em style="color:#666;">Sin referencia</em>' }}
                            </td>

                            <!-- Acciones -->
                            <td style="padding: 16px; text-align: center;">
                                <!-- Botón Editar comentado (como pediste) -->
                                <!-- <a href="{{ route('transactions.edit', $transaction) }}" title="Editar" style="color: var(--primary); margin: 0 12px; font-size: 18px;">
                                    <i class="fas fa-edit"></i>
                                </a> -->

                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar" style="background: none; border: none; color: #f66; cursor: pointer; font-size: 18px;" 
                                            onclick="return confirm('¿Seguro que deseas eliminar esta transacción? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 60px; text-align: center; color: #888; font-size: 18px;">
                                <i class="fas fa-receipt fa-3x mb-3" style="opacity: 0.3;"></i><br>
                                No hay transacciones registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div style="margin-top: 30px; text-align: center;">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
@endsection