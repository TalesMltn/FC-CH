@extends('layouts.app')

@section('page-title', 'Reporte de Cobros')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 style="color: var(--primary); font-size: 36px; text-align: center; margin-bottom: 40px;">
            Reporte de Cobros - Total Cobrado: S/ {{ number_format($totalCobrado, 2) }}
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray);">
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Fecha</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Contrato</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Cliente</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Monto</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Método</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $payment->date->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $payment->contract->code }}</td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $payment->contract->person->getFullNameAttribute() }}</td>
                            <td style="padding: 15px; color: #0f0; font-weight: 700; font-size: 18px;">S/ {{ number_format($payment->amount, 2) }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                    @switch($payment->payment_method)
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
                            <td style="padding: 15px; color: var(--text-light);">{{ $payment->reference ?? 'Sin referencia' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay cobros registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $payments->links() }}
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('reports.index') }}" style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                padding: 12px 32px;
                font-size: 16px;
                font-weight: 600;
                background: rgba(255, 102, 0, 0.15);
                color: var(--primary);
                border: 2px solid var(--primary);
                border-radius: 50px;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(255, 102, 0, 0.2);
            "
            onmouseover="this.style.background='var(--primary)'; this.style.color='white'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(255,102,0,0.4)';"
            onmouseout="this.style.background='rgba(255, 102, 0, 0.15)'; this.style.color='var(--primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255,102,0,0.2)';">
                <i class="fas fa-arrow-left"></i>
                Volver a Reportes Generales
            </a>
        </div>
    </div>
@endsection