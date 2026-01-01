@extends('layouts.app')

@section('page-title', 'Reporte de Ventas')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 style="color: var(--primary); font-size: 36px; text-align: center; margin-bottom: 40px;">
            Reporte de Ventas - Total: S/ {{ number_format($totalVentas, 2) }}
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray);">
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Fecha</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Código</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Cliente</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Concreto</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Cantidad (m³)</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Precio/m³</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Total</th>
                        <th style="padding: 15px; text-align: left; color: var(--primary);">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $contract)
                        <tr style="border-bottom: 1px solid rgba(255,102,0,0.2);">
                            <td style="padding: 15px; color: var(--text-light);">{{ $contract->date->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ $contract->code }}</td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $contract->person->getFullNameAttribute() }}</td>
                            <td style="padding: 15px; color: var(--text-light);">{{ $contract->category->name }}</td>
                            <td style="padding: 15px; color: white;">{{ $contract->quantity }}</td>
                            <td style="padding: 15px; color: var(--text-light);">S/ {{ number_format($contract->price_per_m3, 2) }}</td>
                            <td style="padding: 15px; color: #0f0; font-weight: 700;">S/ {{ number_format($contract->total, 2) }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 8px 15px; border-radius: 20px; font-size: 13px; font-weight: 700;
                                    @switch($contract->status)
                                        @case('pendiente') background: rgba(200,200,0,0.3); color: #ff0; @break
                                        @case('en_produccion') background: rgba(0,100,200,0.3); color: #66f; @break
                                        @case('entregado') background: rgba(0,200,200,0.3); color: #0ff; @break
                                        @case('pagado') background: rgba(0,200,0,0.3); color: #0f0; @break
                                        @case('cancelado') background: rgba(200,0,0,0.3); color: #f66; @break
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-light); font-size: 18px;">
                                No hay ventas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $sales->links() }}
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('reports.index') }}" style="
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 12px 30px;
                font-size: 16px;
                background: rgba(255, 102, 0, 0.15);
                color: var(--primary);
                border: 2px solid var(--primary);
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(255, 102, 0, 0.2);
            " onmouseover="this.style.background='var(--primary)'; this.style.color='white';"
               onmouseout="this.style.background='rgba(255, 102, 0, 0.15)'; this.style.color='var(--primary)';">
                <i class="fas fa-arrow-left"></i> Volver a Reportes Generales
            </a>
        </div>
    </div>
@endsection