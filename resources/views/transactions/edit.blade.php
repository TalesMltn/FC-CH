@extends('layouts.app')

@section('page-title', 'Editar Transacción')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 900px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Editar Pago / Transacción
        </h2>

        <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary);">Contrato *</label>
                <select name="contract_id" id="contract-select" required style="
                    width: 100%; 
                    padding: 16px; 
                    border-radius: 15px; 
                    background: rgba(0,0,0,0.7);
                    border: none; 
                    color: var(--primary); 
                    font-size: 16px; 
                    font-weight: bold;
                    appearance: none; 
                    cursor: pointer; 
                    background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); 
                    background-repeat: no-repeat; 
                    background-position: right 16px center; 
                    background-size: 16px;">
                    <option value="">Seleccione un contrato</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}"
                                data-total="{{ $contract->total }}"
                                data-pagado="{{ $contract->total_pagado ?? 0 }}"
                                data-pendiente="{{ max(0, $contract->total - ($contract->total_pagado ?? 0)) }}"
                                {{ old('contract_id', $transaction->contract_id) == $contract->id ? 'selected' : '' }}>
                            {{ $contract->code }} - {{ $contract->person->getFullNameAttribute() }} - Total: S/ {{ number_format($contract->total, 2) }} - Estado: {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                        </option>
                    @endforeach
                </select>
                @error('contract_id')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- OPERACIÓN MATEMÁTICA EN TIEMPO REAL -->
            <div id="operacion-bloque" 
                 style="
                    background: rgba(255,102,0,0.08);
                    border-radius: 10px;
                    padding: 8px 10px;
                    margin-bottom: 15px;
                    text-align: center;
                    line-height: 1.4;
                    font-family: monospace;
                    font-size: 13px;
                    display: none;
                 ">
                <div id="contrato-info" style="color: #aaa; font-size: 18px; margin-bottom: 10px;">
                    <!-- Se llena con JS -->
                </div>

                <div id="debia-antes" style="color: #ff9; font-size: 40px; font-weight: 700; margin: 10px 0;">
                    0.00
                </div>

                <div style="color: #0f0; font-size: 36px; margin: 10px 0;">
                    – <span id="monto-pago">0.00</span>
                </div>

                <div id="saldo-resultado" style="font-size: 48px; font-weight: 700; color: #fff; text-shadow: 0 0 25px #ff9;">
                    = 0.00
                </div>

                <div id="mensaje-saldo" style="color: #ff9; font-size: 24px; margin-top: 10px; font-weight: bold;">
                    Saldo pendiente: S/ 0.00
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Pago *</label>
                    <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('date')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Monto Pagado (S/) *</label>
                    <input type="number" step="0.01" id="amount-input" name="amount" value="{{ old('amount', $transaction->amount) }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('amount')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Método de Pago *</label>
                    <select name="payment_method" required style="
                        width: 100%; 
                        padding: 16px; 
                        border-radius: 15px; 
                        background: rgba(0,0,0,0.7);
                        border: none; 
                        color: var(--primary); 
                        font-size: 16px; 
                        font-weight: bold;
                        appearance: none; 
                        cursor: pointer; 
                        background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); 
                        background-repeat: no-repeat; 
                        background-position: right 16px center; 
                        background-size: 16px;">
                        <option value="efectivo" {{ old('payment_method', $transaction->payment_method) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ old('payment_method', $transaction->payment_method) == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                        <option value="yape" {{ old('payment_method', $transaction->payment_method) == 'yape' ? 'selected' : '' }}>Yape</option>
                        <option value="plin" {{ old('payment_method', $transaction->payment_method) == 'plin' ? 'selected' : '' }}>Plin</option>
                        <option value="tarjeta" {{ old('payment_method', $transaction->payment_method) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="cheque" {{ old('payment_method', $transaction->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="otro" {{ old('payment_method', $transaction->payment_method) == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Referencia / Operación</label>
                    <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}" 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                           placeholder="Ej. número de operación, voucher">
                    @error('reference')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas</label>
                <textarea name="notes" rows="4" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes', $transaction->notes) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Actualizar Pago
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('transactions.index') }}" style="
                    padding: 18px 50px;
                    font-size: 20px;
                    background: rgba(255,255,255,0.15);
                    color: var(--primary);
                    border: 2px solid var(--primary);
                    border-radius: 12px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                    transition: all 0.2s;
                ">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </form>
    </div>

    <!-- JavaScript para cálculo en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('contract-select');
            const amount = document.getElementById('amount-input');
            const bloque = document.getElementById('operacion-bloque');
            const info = document.getElementById('contrato-info');
            const debia = document.getElementById('debia-antes');
            const pago = document.getElementById('monto-pago');
            const resultado = document.getElementById('saldo-resultado');
            const mensaje = document.getElementById('mensaje-saldo');

            const update = () => {
                const opt = select.selectedOptions[0];
                if (!opt?.value) return bloque.style.display = 'none';

                const total = +opt.dataset.total;
                const pagado = +opt.dataset.pagado || 0;
                const pendiente = total - pagado;
                const monto = +amount.value || 0;
                const nuevo = Math.max(0, pendiente - monto);

                info.textContent = `${opt.text.split(' - ')[0]} - ${opt.text.split(' - ')[1]} | Total: S/ ${total.toLocaleString('es-PE', {minimumFractionDigits:2})}`;
                bloque.style.display = 'block';
                debia.textContent = pendiente.toLocaleString('es-PE', {minimumFractionDigits:2});
                pago.textContent = monto.toLocaleString('es-PE', {minimumFractionDigits:2});
                resultado.textContent = '= ' + nuevo.toLocaleString('es-PE', {minimumFractionDigits:2});
                
                mensaje.innerHTML = nuevo === 0 
                    ? '<span style="color:#0f0;">¡Contrato pagado completo!</span>'
                    : `Nuevo saldo pendiente: S/ ${nuevo.toLocaleString('es-PE', {minimumFractionDigits:2})}`;
            };

            select.addEventListener('change', update);
            amount.addEventListener('input', update);
            update(); // Ejecutar al cargar para mostrar los valores actuales
        });
    </script>
@endsection