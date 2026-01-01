@extends('layouts.app')

@section('page-title', 'Editar Contrato')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 1000px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Editar Contrato: {{ $contract->code }}
        </h2>

        <form method="POST" action="{{ route('contracts.update', $contract) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Código del Contrato</label>
                    <input type="text" value="{{ $contract->code }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Cliente *</label>
                    <select name="person_id" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('person_id', $contract->person_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->getFullNameAttribute() }} (DNI: {{ $client->dni }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Tipo de Concreto *</label>
                    <select name="category_id" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                        <option value="">Seleccione categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $contract->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Contrato *</label>
                    <input type="date" name="date" value="{{ old('date', $contract->date->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha de Entrega Prevista</label>
                    <input type="date" name="delivery_date" value="{{ old('delivery_date', optional($contract->delivery_date)->format('Y-m-d')) }}" 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Cantidad (m³) *</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" value="{{ old('quantity', $contract->quantity) }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Precio por m³ (S/) *</label>
                    <input type="number" step="0.01" name="price_per_m3" id="price_per_m3" value="{{ old('price_per_m3', $contract->price_per_m3) }}" required min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Total (S/)</label>
                    <input type="text" id="total" value="{{ $contract->total }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary);">Estado del Contrato *</label>
                <select name="status" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    <option value="pendiente" {{ old('status', $contract->status) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_produccion" {{ old('status', $contract->status) == 'en_produccion' ? 'selected' : '' }}>En Producción</option>
                    <option value="entregado" {{ old('status', $contract->status) == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="pagado" {{ old('status', $contract->status) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="cancelado" {{ old('status', $contract->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas adicionales</label>
                <textarea name="notes" rows="5" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes', $contract->notes) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Actualizar Contrato
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('contracts.index') }}" style="color: var(--text-light); text-decoration: underline;">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </form>
    </div>

    <script>
        // Cálculo automático del total
        document.getElementById('quantity').addEventListener('input', calculateTotal);
        document.getElementById('price_per_m3').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('price_per_m3').value) || 0;
            const total = quantity * price;
            document.getElementById('total').value = total.toFixed(2);
        }

        calculateTotal();
    </script>
@endsection