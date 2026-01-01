@extends('layouts.app')

@section('page-title', 'Exportar Reportes')

@section('content')
    <div class="container" style="padding: 30px;">
        <h1 style="color: var(--primary); font-size: 40px; text-align: center; margin-bottom: 50px; text-shadow: 0 0 20px rgba(255,102,0,0.5);">
            Exportar Reportes - Concretera Huancayo
        </h1>

        <p style="text-align: center; color: var(--text-light); font-size: 20px; margin-bottom: 60px;">
            Descarga los reportes en formato Excel o PDF con un solo clic.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px;">
            <!-- Ventas -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <i class="fas fa-file-invoice-dollar" style="font-size: 80px; color: var(--primary); margin-bottom: 20px;"></i>
                <h3 style="color: var(--primary); font-size: 28px; margin-bottom: 20px;">Reporte de Ventas</h3>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="{{ route('exports.contracts.excel') }}" class="btn-login" style="padding: 16px 30px; background: #28a745;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('exports.contracts.pdf') }}" class="btn-login" style="padding: 16px 30px; background: #dc3545;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <!-- Cobros -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <i class="fas fa-money-bill-wave" style="font-size: 80px; color: #0f0; margin-bottom: 20px;"></i>
                <h3 style="color: #0f0; font-size: 28px; margin-bottom: 20px;">Reporte de Cobros</h3>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="{{ route('exports.transactions.excel') }}" class="btn-login" style="padding: 16px 30px; background: #28a745;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('exports.transactions.pdf') }}" class="btn-login" style="padding: 16px 30px; background: #dc3545;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <!-- Nómina -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <i class="fas fa-users-cog" style="font-size: 80px; color: #fc6; margin-bottom: 20px;"></i>
                <h3 style="color: #fc6; font-size: 28px; margin-bottom: 20px;">Reporte de Nómina</h3>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="{{ route('exports.payrolls.excel') }}" class="btn-login" style="padding: 16px 30px; background: #28a745;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('exports.payrolls.pdf') }}" class="btn-login" style="padding: 16px 30px; background: #dc3545;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <!-- Préstamos -->
            <div style="background: var(--dark-light); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center;">
                <i class="fas fa-hand-holding-usd" style="font-size: 80px; color: #f66; margin-bottom: 20px;"></i>
                <h3 style="color: #f66; font-size: 28px; margin-bottom: 20px;">Reporte de Préstamos</h3>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="{{ route('exports.loans.excel') }}" class="btn-login" style="padding: 16px 30px; background: #28a745;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('exports.loans.pdf') }}" class="btn-login" style="padding: 16px 30px; background: #dc3545;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 60px;">
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
                        <i class="fas fa-arrow-left"></i> Volver a Reportes
            </a>
        </div>
    </div>
@endsection