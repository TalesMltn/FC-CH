@extends('layouts.app')

@section('page-title', 'Reportes Generales')

@section('content')
    <div class="container" style="padding: 30px;">
        <h1 style="color: var(--primary); font-size: 40px; text-align: center; margin-bottom: 50px; text-shadow: 0 0 20px rgba(255,102,0,0.5);">
            Reportes Generales - Concretera Huancayo
        </h1>

        <!-- Tarjetas resumen -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <!-- Ventas -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Ventas Totales</h3>
                <p style="font-size: 48px; color: #0f0; font-weight: 700;">S/ {{ number_format($totalVentas, 2) }}</p>
                <p style="color: var(--text-light); margin-top: 10px;">{{ $contratosMes }} contratos este mes</p>
            </div>

            <!-- Cobrado -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Cobrado</h3>
                <p style="font-size: 48px; color: #0f0; font-weight: 700;">S/ {{ number_format($totalCobrado, 2) }}</p>
                <p style="color: var(--text-light); margin-top: 10px;">S/ {{ number_format($pagosMes, 2) }} este mes</p>
            </div>

            <!-- Pendiente de Cobro -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Pendiente de Cobro</h3>
                <p style="font-size: 48px; color: #f66; font-weight: 700;">S/ {{ number_format($totalPendienteCobro, 2) }}</p>
            </div>

            <!-- Nómina -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Nómina Pagada</h3>
                <p style="font-size: 48px; color: #fc6; font-weight: 700;">S/ {{ number_format($totalNomina, 2) }}</p>
                <p style="color: var(--text-light); margin-top: 10px;">S/ {{ number_format($nominaMes, 2) }} este mes</p>
            </div>

            <!-- Préstamos Otorgados -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Préstamos Otorgados</h3>
                <p style="font-size: 48px; color: #fc6; font-weight: 700;">S/ {{ number_format($totalPrestamos, 2) }}</p>
            </div>

            <!-- Pendiente Préstamos -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 20px;">Pendiente por Cobrar (Préstamos)</h3>
                <p style="font-size: 48px; color: #f66; font-weight: 700;">S/ {{ number_format($totalPendientePrestamos, 2) }}</p>
            </div>
        </div>

        <!-- Enlaces a reportes detallados -->
        <div style="text-align: center; margin-top: 50px;">
            <h3 style="color: var(--primary); margin-bottom: 30px;">Reportes Detallados</h3>
            <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                <a href="{{ route('reports.sales') }}" class="btn-login" style="padding: 20px 40px; font-size: 18px;">
                    <i class="fas fa-file-invoice-dollar"></i> Ventas
                </a>
                <a href="{{ route('reports.payments') }}" class="btn-login" style="padding: 20px 40px; font-size: 18px;">
                    <i class="fas fa-money-bill-wave"></i> Cobros
                </a>
                <a href="{{ route('reports.payroll') }}" class="btn-login" style="padding: 20px 40px; font-size: 18px;">
                    <i class="fas fa-users-cog"></i> Nómina
                </a>
                <a href="{{ route('reports.loans') }}" class="btn-login" style="padding: 20px 40px; font-size: 18px;">
                    <i class="fas fa-hand-holding-usd"></i> Préstamos
                </a>
            </div>
        </div>
    </div>
@endsection