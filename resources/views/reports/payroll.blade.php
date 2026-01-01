@extends('layouts.app')

@section('page-title', 'Reporte de Nómina')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 style="color: var(--primary); font-size: 36px; text-align: center; margin-bottom: 40px;">
            Reporte de Nómina - Total Pagado: S/ {{ number_format($totalNomina, 2) }}
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray);">
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Fecha Pago</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Trabajador</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Período</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Salario Base</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Bono</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Descuentos + Préstamo</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Total Pagado</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Método</th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay pagos de nómina registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $payrolls->links() }}
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('reports.index') }}" class="btn-login" style="
            padding: 18px 50px;
            font-size: 20px;
            flex: 1;
            max-width: 300px;
            background: rgba(255,255,255,0.15);
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        ">
                        <i class="fas fa-arrow-left"></i> Volver a Reportes Generales
            </a>
        </div>
    </div>
@endsection