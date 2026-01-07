@extends('layouts.app')

@section('page-title', 'Editar Pago / Transacción')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 900px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Registrar Nuevo Pago / Transacción
        </h2>

        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @method('PUT')
            @csrf

            <!-- Selección de Contrato -->
            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary); font-weight: bold;">Contrato *</label>
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
                                {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                            {{ $contract->code }} - {{ $contract->person->getFullNameAttribute() }} - Total: S/ {{ number_format($contract->total, 2) }} - {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                        </option>
                    @endforeach
                </select>
                @error('contract_id')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- TRES CASILLAS: Más equilibradas y con cifras de tamaño moderado -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 40px;">
                
                <!-- Casilla 1: Pendiente actual -->
                <div id="casilla-contrato" style="background: rgba(255,102,0,0.12); border-radius: 15px; padding: 20px; text-align: center; display: none; border: 1px solid rgba(255,102,0,0.3);">
                    <div style="color: #ccc; font-size: 14px; margin-bottom: 5px;">Deuda actual</div>
                    <div id="contrato-info" style="color: var(--primary); font-weight: bold; font-size: 15px; margin-bottom: 12px; line-height: 1.4;">
                        —
                    </div>
                    <div style="font-size: 28px; font-weight: 700; color: #ff9;">
                        S/ <span id="debia-antes">0.00</span>
                    </div>
                </div>

                <!-- Casilla 2: Monto a pagar (entrada) -->
                <div style="background: rgba(0,200,0,0.12); border-radius: 15px; padding: 20px; text-align: center; border: 1px solid rgba(0,200,0,0.3);">
                    <div style="color: #ccc; font-size: 14px; margin-bottom: 10px;">Monto Pagado (S/) *</div>
                    <input type="number" step="0.01" id="amount-input" name="amount" value="{{ old('amount') }}" required min="0.01"
                           style="width: 100%; padding: 14px; border-radius: 12px; background: rgba(255,255,255,0.2); border: none; color: white; font-size: 26px; text-align: center; font-weight: bold;"
                           placeholder="0.00">
                    @error('amount')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Casilla 3: Nuevo saldo -->
                <div id="casilla-saldo" style="background: rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; text-align: center; display: none; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="color: #ccc; font-size: 14px; margin-bottom: 5px;">Nuevo saldo pendiente</div>
                    <div style="font-size: 28px; font-weight: 700; color: #fff; margin: 12px 0;">
                        S/ <span id="saldo-resultado">0.00</span>
                    </div>
                    <div id="mensaje-saldo" style="color: #ff9; font-size: 16px; font-weight: bold;">
                        —
                    </div>
                </div>
            </div>

            <!-- Fecha y Método de Pago -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary); font-weight: bold;">Fecha del Pago *</label>
                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('date')
                        <p style="color: #f66; margin-top: 8px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary); font-weight: bold;">Método de Pago *</label>
                    <select name="payment_method" required style="
                        width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,0,0,0.7); border: none; 
                        color: var(--primary); font-size: 16px; font-weight: bold; appearance: none; cursor: pointer;
                        background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e');
                        background-repeat: no-repeat; background-position: right 16px center; background-size: 16px;">
                        <option value="efectivo" {{ old('payment_method') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                        <option value="yape" {{ old('payment_method') == 'yape' ? 'selected' : '' }}>Yape</option>
                        <option value="plin" {{ old('payment_method') == 'plin' ? 'selected' : '' }}>Plin</option>
                        <option value="tarjeta" {{ old('payment_method') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="otro" {{ old('payment_method') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <!-- Referencia y Notas -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
                <div>
                    <label class="field-label" style="color: var(--primary); font-weight: bold;">Referencia / Operación</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                           placeholder="Ej. número de operación, voucher">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary); font-weight: bold;">Notas</label>
                    <textarea name="notes" rows="3" 
                              style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Botones -->
            <div style="text-align: center; margin-bottom: 20px;">
                <button type="submit" style="
                    padding: 18px 60px; 
                    font-size: 20px; 
                    background: var(--primary); 
                    color: white; 
                    border: none; 
                    border-radius: 12px; 
                    cursor: pointer;
                    box-shadow: 0 4px 15px rgba(255,107,0,0.3);
                    transition: all 0.3s;">
                    <i class="fas fa-save"></i> Registrar Pago
                </button>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('transactions.index') }}" style="
                    padding: 16px 40px;
                    font-size: 18px;
                    background: transparent;
                    color: var(--primary);
                    border: 2px solid var(--primary);
                    border-radius: 12px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    transition: all 0.3s;">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </form>
    </div>

    <!-- JavaScript corregido y optimizado -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('contract-select');
            const amountInput = document.getElementById('amount-input');
            
            const casillaContrato = document.getElementById('casilla-contrato');
            const contratoInfo = document.getElementById('contrato-info');
            const debiaAntes = document.getElementById('debia-antes');
            
            const casillaSaldo = document.getElementById('casilla-saldo');
            const saldoResultado = document.getElementById('saldo-resultado');
            const mensajeSaldo = document.getElementById('mensaje-saldo');

            const update = () => {
                const opt = select.selectedOptions[0];
                if (!opt || !opt.value) {
                    casillaContrato.style.display = 'none';
                    casillaSaldo.style.display = 'none';
                    return;
                }

                const total = parseFloat(opt.dataset.total);
                const pagado = parseFloat(opt.dataset.pagado) || 0;
                const pendienteActual = total - pagado;
                const montoPago = parseFloat(amountInput.value) || 0;
                const nuevoPendiente = Math.max(0, pendienteActual - montoPago);

                // Casilla 1
                contratoInfo.textContent = opt.text.split(' - Total:')[0];
                debiaAntes.textContent = pendienteActual.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                casillaContrato.style.display = 'block';

                // Casilla 3
                saldoResultado.textContent = nuevoPendiente.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                if (nuevoPendiente === 0) {
                    mensajeSaldo.innerHTML = '<span style="color:#0f8;">¡Contrato pagado completo!</span>';
                } else {
                    mensajeSaldo.textContent = 'Quedará pendiente';
                }
                casillaSaldo.style.display = 'block';
            };

            select.addEventListener('change', update);
            amountInput.addEventListener('input', update);
            update();
        });
    </script>
@endsection