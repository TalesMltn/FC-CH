@extends('layouts.app')

@section('page-title', 'Transacciones')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div class="header-table" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 style="color: var(--primary); font-size: 32px;">Registro de Pagos y Transacciones</h2>
            <a href="{{ route('transactions.create') }}" class="btn-login" style="
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
                <i class="fas fa-plus"></i> Nueva Transacción
            </a>
        </div>

        <!-- Búsqueda -->
        <form method="GET" action="{{ route('transactions.index') }}" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código de contrato o referencia..." 
                       style="flex: 1; min-width: 250px; padding: 14px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">

                <button type="submit" class="btn-login" style="padding: 14px 25px;">
                    <i class="fas fa-search"></i> Buscar
                </button>

                @if(request('search'))
                    <a href="{{ route('transactions.index') }}" class="btn-login" style="background: #666; padding: 14px 20px;">
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
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Contrato</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Cliente</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Detalle de Pago y Saldo</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Método</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Referencia</th>
                        <th style="padding: 15px; text-align: center; color: var(--primary);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $transaction->date->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $transaction->contract->code }}</td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $transaction->contract->person->getFullNameAttribute() }}</td>

                            <!-- DETALLE DE PAGO Y SALDO - VERSIÓN FINAL Y MEJORADA -->
                            <td style="padding: 35px 15px; text-align: center; line-height: 2.6; background: rgba(255,102,0,0.05); border-radius: 15px;">
                                @php
                                    $totalContrato = $transaction->contract->total ?? 0;
                                    $totalPagado   = $transaction->contract->total_pagado ?? 0;
                                    $pagadoAntes   = $totalPagado - $transaction->amount;
                                    $debiaAntes    = $totalContrato - $pagadoAntes;
                                    $saldoFinal    = $debiaAntes - $transaction->amount;
                                @endphp

                                <!-- Lo que debía antes -->
                                <div style="color: #ff9; font-size: 34px; font-weight: 700;">
                                    {{ number_format($debiaAntes, 2) }}
                                </div>

                                <!-- Pago realizado -->
                                <div style="color: #0f0; font-size: 30px; margin: 20px 0;">
                                    – {{ number_format($transaction->amount, 2) }}
                                </div>

                                <!-- Resultado -->
                                <div style="font-size: 40px; font-weight: 700; color: #fff; text-shadow: 0 0 25px #ff9;">
                                    = {{ number_format(max(0, $saldoFinal), 2) }}
                                </div>

                                <!-- Mensaje final -->
                                @if($saldoFinal <= 0)
                                    <div style="color: #0f0; font-size: 22px; margin-top: 25px; font-weight: bold;">
                                        ¡Contrato pagado completo! 🎉
                                    </div>
                                @else
                                    <div style="color: #ff9; font-size: 22px; margin-top: 25px; font-weight: bold;">
                                        Saldo pendiente: S/ {{ number_format(max(0, $saldoFinal), 2) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Método de pago -->
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: rgba(255,255,255,0.1);">
                                    @switch($transaction->payment_method)
                                        @case('efectivo') <i class="fas fa-money-bill-wave" style="color: #0f0;"></i> Efectivo @break
                                        @case('transferencia') <i class="fas fa-university" style="color: #66f;"></i> Transferencia @break
                                        @case('yape') <i class="fas fa-mobile-alt" style="color: #9f3;"></i> Yape @break
                                        @case('plin') <i class="fas fa-mobile-alt" style="color: #f39;"></i> Plin @break
                                        @case('tarjeta') <i class="fas fa-credit-card" style="color: #fc6;"></i> Tarjeta @break
                                        @case('cheque') <i class="fas fa-file-invoice-dollar" style="color: #aaa;"></i> Cheque @break
                                        @default <i class="fas fa-question" style="color: #ccc;"></i> Otro @break
                                    @endswitch
                                </span>
                            </td>

                            <td style="padding: 15px; color: var(--text-light);">{{ $transaction->reference ?? 'Sin referencia' }}</td>

                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('transactions.edit', $transaction) }}" style="color: var(--primary); margin: 0 10px;">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f66; cursor: pointer;" 
                                            onclick="return confirm('¿Seguro que quieres eliminar esta transacción?')">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay transacciones registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
@endsection