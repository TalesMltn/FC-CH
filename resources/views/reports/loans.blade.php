@extends('layouts.app')

@section('page-title', 'Reporte de Préstamos')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 style="color: var(--primary); font-size: 36px; text-align: center; margin-bottom: 20px;">
            Reporte de Préstamos
        </h2>
        <div style="text-align: center; margin-bottom: 40px;">
            <p style="font-size: 24px; color: var(--text-light);">Total Otorgado: S/ {{ number_format($totalPrestamos, 2) }}</p>
            <p style="font-size: 28px; color: #f66; font-weight: 700;">Pendiente por Cobrar: S/ {{ number_format($totalPendiente, 2) }}</p>
        </div>

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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay préstamos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $loans->links() }}
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