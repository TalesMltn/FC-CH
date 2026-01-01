@extends('layouts.app')

@section('page-title', 'Editar Préstamo')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 900px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Editar Préstamo: {{ $loan->person->getFullNameAttribute() }}
        </h2>

        <form method="POST" action="{{ route('loans.update', $loan) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Trabajador</label>
                    <input type="text" value="{{ $loan->person->getFullNameAttribute() }} (DNI: {{ $loan->person->dni }})" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Préstamo *</label>
                    <input type="date" name="loan_date" value="{{ old('loan_date', $loan->loan_date->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Monto del Préstamo (S/) *</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $loan->amount) }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Número de Cuotas *</label>
                    <input type="number" name="installments" id="installments" value="{{ old('installments', $loan->installments) }}" required min="1" max="60"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Valor por Cuota (S/)</label>
                    <input type="text" id="installment_amount" value="{{ $loan->installment_amount }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,200,0.3); border: none; color: #0ff; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Monto Pagado (S/)</label>
                    <input type="text" value="{{ number_format($loan->paid_amount, 2) }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 18px; text-align: center;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Saldo Pendiente (S/)</label>
                    <input type="text" value="{{ number_format($loan->pending_amount, 2) }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(200,0,0,0.3); border: none; color: #f66; font-size: 24px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas</label>
                <textarea name="notes" rows="4" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes', $loan->notes) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Actualizar Préstamo
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('loans.index') }}" style="color: var(--text-light); text-decoration: underline;">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </form>
    </div>

    <script>
        // Cálculo automático de la cuota
        const amountInput = document.getElementById('amount');
        const installmentsInput = document.getElementById('installments');
        const installmentAmountInput = document.getElementById('installment_amount');

        function calculateInstallment() {
            const amount = parseFloat(amountInput.value) || 0;
            const installments = parseInt(installmentsInput.value) || 1;
            const installment = amount / installments;
            installmentAmountInput.value = installment.toFixed(2);
        }

        amountInput.addEventListener('input', calculateInstallment);
        installmentsInput.addEventListener('input', calculateInstallment);

        calculateInstallment();
    </script>
@endsection