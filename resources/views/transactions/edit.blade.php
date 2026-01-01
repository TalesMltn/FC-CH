@extends('layouts.app')

@section('page-title', 'Editar Transacción')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 900px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Editar Transacción: S/ {{ number_format($transaction->amount, 2) }}
        </h2>

        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary);">Contrato</label>
                <input type="text" value="{{ $transaction->contract->code }} - {{ $transaction->contract->person->getFullNameAttribute() }}" disabled 
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Pago *</label>
                    <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Monto Pagado (S/) *</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Método de Pago *</label>
                    <select name="payment_method" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
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
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas</label>
                <textarea name="notes" rows="4" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes', $transaction->notes) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Actualizar Transacción
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('transactions.index') }}" style="color: var(--text-light); text-decoration: underline;">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </form>
    </div>
@endsection