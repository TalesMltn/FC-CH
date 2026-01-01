@extends('layouts.app')

@section('page-title', 'Editar Pago de Nómina')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 1000px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Editar Pago de Nómina: {{ $payroll->person->getFullNameAttribute() }}
        </h2>

        <form method="POST" action="{{ route('payrolls.update', $payroll) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Trabajador</label>
                    <input type="text" value="{{ $payroll->person->getFullNameAttribute() }} (DNI: {{ $payroll->person->dni }})" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Pago *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', $payroll->payment_date->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Año *</label>
                    <input type="number" name="year" value="{{ old('year', $payroll->year) }}" required min="2020" max="2030"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Mes *</label>
                    <select name="month" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('month', $payroll->month) == $m ? 'selected' : '' }}>
                                {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Salario Base (S/) *</label>
                    <input type="number" step="0.01" name="base_salary" id="base_salary" value="{{ old('base_salary', $payroll->base_salary) }}" required min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Bono (S/)</label>
                    <input type="number" step="0.01" name="bonus" id="bonus" value="{{ old('bonus', $payroll->bonus) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Descuentos (S/)</label>
                    <input type="number" step="0.01" name="discounts" id="discounts" value="{{ old('discounts', $payroll->discounts) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(200,0,0,0.3); border: none; color: #f66; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Deducción Préstamo (S/)</label>
                    <input type="number" step="0.01" name="loan_deduction" id="loan_deduction" value="{{ old('loan_deduction', $payroll->loan_deduction) }}" min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(200,0,0,0.3); border: none; color: #f66; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Método de Pago *</label>
                    <select name="payment_method" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        <option value="efectivo" {{ old('payment_method', $payroll->payment_method) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ old('payment_method', $payroll->payment_method) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="yape" {{ old('payment_method', $payroll->payment_method) == 'yape' ? 'selected' : '' }}>Yape</option>
                        <option value="plin" {{ old('payment_method', $payroll->payment_method) == 'plin' ? 'selected' : '' }}>Plin</option>
                        <option value="otro" {{ old('payment_method', $payroll->payment_method) == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Total a Pagar (S/)</label>
                    <input type="text" id="total_paid" value="{{ $payroll->total_paid }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 24px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas</label>
                <textarea name="notes" rows="4" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes', $payroll->notes) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Actualizar Pago de Nómina
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('payrolls.index') }}" style="color: var(--text-light); text-decoration: underline;">
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