@extends('layouts.app')

@section('page-title', 'Nuevo Pago de Nómina')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 1000px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Registrar Pago de Nómina
        </h2>

        <form method="POST" action="{{ route('payrolls.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Trabajador *</label>
                    <select name="person_id" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        <option value="">Seleccione un trabajador</option>
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
                    <label class="field-label" style="color: var(--primary);">Fecha del Pago *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('payment_date')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Año *</label>
                    <input type="number" name="year" value="{{ old('year', now()->year) }}" required min="2020" max="2030"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('year')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Mes *</label>
                    <select name="month" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('month', now()->month) == $m ? 'selected' : '' }}>
                                {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('month')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Salario Base (S/) *</label>
                    <input type="number" step="0.01" name="base_salary" id="base_salary" value="{{ old('base_salary') }}" required min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('base_salary')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Bono (S/)</label>
                    <input type="number" step="0.01" name="bonus" id="bonus" value="{{ old('bonus', 0) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Descuentos (S/)</label>
                    <input type="number" step="0.01" name="discounts" id="discounts" value="{{ old('discounts', 0) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(200,0,0,0.3); border: none; color: #f66; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Deducción Préstamo (S/)</label>
                    <input type="number" step="0.01" name="loan_deduction" id="loan_deduction" value="{{ old('loan_deduction', 0) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(200,0,0,0.3); border: none; color: #f66; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Método de Pago *</label>
                    <select name="payment_method" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        <option value="efectivo" {{ old('payment_method') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="yape" {{ old('payment_method') == 'yape' ? 'selected' : '' }}>Yape</option>
                        <option value="plin" {{ old('payment_method') == 'plin' ? 'selected' : '' }}>Plin</option>
                        <option value="otro" {{ old('payment_method') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Total a Pagar (S/)</label>
                    <input type="text" id="total_paid" value="0.00" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 24px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas</label>
                <textarea name="notes" rows="4" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes') }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Registrar Pago de Nómina
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('payrolls.index') }}" style="
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
        // Cálculo automático del total pagado
        const inputs = ['base_salary', 'bonus', 'discounts', 'loan_deduction'];
        inputs.forEach(id => {
            document.getElementById(id).addEventListener('input', calculateTotal);
        });

        function calculateTotal() {
            const base = parseFloat(document.getElementById('base_salary').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const discounts = parseFloat(document.getElementById('discounts').value) || 0;
            const loan = parseFloat(document.getElementById('loan_deduction').value) || 0;
            const total = base + bonus - discounts - loan;
            document.getElementById('total_paid').value = total.toFixed(2);
        }

        calculateTotal();
    </script>
@endsection