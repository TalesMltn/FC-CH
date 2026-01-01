<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - Concretera Huancayo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { color: #ff6600; font-size: 32px; }
        .header p { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f4f4f4; color: #333; }
        .total { font-size: 24px; text-align: right; margin-top: 30px; color: #0f0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Concretera Huancayo</h1>
        <p>Reporte de Ventas - Generado el {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Concreto</th>
                <th>Cantidad (m³)</th>
                <th>Precio/m³</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
                <tr>
                    <td>{{ $contract->code }}</td>
                    <td>{{ $contract->date->format('d/m/Y') }}</td>
                    <td>{{ $contract->person->getFullNameAttribute() }}</td>
                    <td>{{ $contract->category->name }}</td>
                    <td>{{ $contract->quantity }}</td>
                    <td>S/ {{ number_format($contract->price_per_m3, 2) }}</td>
                    <td>S/ {{ number_format($contract->total, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $contract->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL VENTAS: S/ {{ number_format($total, 2) }}
    </div>
</body>
</html>