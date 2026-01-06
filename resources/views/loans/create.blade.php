@extends('layouts.app')

@section('page-title', 'Nuevo Préstamo')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 900px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Registrar Nuevo Préstamo a Trabajador
        </h2>

        <form method="POST" action="{{ route('loans.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Trabajador *</label>
                    <select name="person_id" required style="
                    width: 100%; 
                    padding: 16px; 
                    border-radius: 15px; 
                    background: rgba(0,0,0,0.7); /* Fondo negro semi-transparente */
                    border: none; 
                    color: var(--primary); 
                    font-size: 16px; 
                    font-weight: bold; /* Texto de las opciones en negrita */
                    appearance: none; 
                    cursor: pointer; 
                    background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); 
                    background-repeat: no-repeat; 
                    background-position: right 16px center; 
                    background-size: 16px;"><option value="">Seleccione un trabajador</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ old('person_id') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->getFullNameAttribute() }} (DNI: {{ $worker->dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('person_id')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Préstamo *</label>
                    <input type="date" name="loan_date" value="{{ old('loan_date', now()->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('loan_date')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Monto del Préstamo (S/) *</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('amount')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Número de Cuotas *</label>
                    <input type="number" name="installments" id="installments" value="{{ old('installments') }}" required min="1" max="60"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('installments')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Valor por Cuota (S/)</label>
                    <input type="text" id="installment_amount" value="0.00" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,200,0.3); border: none; color: #0ff; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-bottom: 40px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Notas</label>
                    <textarea name="notes" rows="4" 
                              style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Registrar Préstamo
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('loans.index') }}" style="
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