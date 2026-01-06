@extends('layouts.app')

@section('page-title', 'Nuevo Contrato')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 1000px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Crear Nuevo Contrato de Venta
        </h2>

        <form method="POST" action="{{ route('contracts.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Código del Contrato</label>
                    <input type="text" value="{{ $code }}" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.1); border: none; color: white; font-size: 16px;">
                    <small style="color: #ccc;">Se genera automáticamente</small>
                </div>

                <div>
                <label class="field-label" style="color: var(--primary); font-weight: bold;">Cliente *</label>
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
                    background-size: 16px;">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('person_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->getFullNameAttribute() }} (DNI: {{ $client->dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('person_id')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary); font-weight: bold;">
                        Tipo de Concreto *
                    </label>
                    
                    <select name="category_id" id="category" required style="
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
                        background-size: 16px;">
                        
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }} style="color: #aaa; font-weight: bold;">
                            Seleccione categoría
                        </option>
                        
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }} 
                                    style="font-weight: bold;">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                
                    @error('category_id')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha del Contrato *</label>
                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('date')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Fecha de Entrega Prevista</label>
                    <input type="date" name="delivery_date" value="{{ old('delivery_date') }}" 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('delivery_date')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <label class="field-label" style="color: var(--primary);">Cantidad (m³) *</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" value="{{ old('quantity') }}" required min="0.01"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('quantity')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Precio por m³ (S/) *</label>
                    <input type="number" step="0.01" name="price_per_m3" id="price_per_m3" value="{{ old('price_per_m3') }}" required min="0"
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;">
                    @error('price_per_m3')
                        <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label" style="color: var(--primary);">Total (S/)</label>
                    <input type="text" id="total" value="0.00" disabled 
                           style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(0,200,0,0.3); border: none; color: #0f0; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary); font-weight: bold;">
                    Estado del Contrato *
                </label>
                
                <select name="status" required style="
                    width: 100%; 
                    padding: 16px; 
                    border-radius: 15px; 
                    background: rgba(0,0,0,0.7); /* Fondo negro semi-transparente */
                    border: none; 
                    color: var(--primary); 
                    font-size: 16px; 
                    font-weight: bold; /* Opciones en negrita */
                    appearance: none; 
                    cursor: pointer;
                    background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e');
                    background-repeat: no-repeat;
                    background-position: right 16px center;
                    background-size: 16px;">
                    
                    <option value="" disabled {{ old('status') ? '' : 'selected' }} style="color: #aaa; font-weight: bold;">
                        Selecciona un estado
                    </option>
                    <option value="pendiente" {{ old('status', 'pendiente') == 'pendiente' ? 'selected' : '' }} style="font-weight: bold;">
                        Pendiente
                    </option>
                    <option value="en_produccion" {{ old('status') == 'en_produccion' ? 'selected' : '' }} style="font-weight: bold;">
                        En Producción
                    </option>
                    <option value="entregado" {{ old('status') == 'entregado' ? 'selected' : '' }} style="font-weight: bold;">
                        Entregado
                    </option>
                    <option value="pagado" {{ old('status') == 'pagado' ? 'selected' : '' }} style="font-weight: bold;">
                        Pagado
                    </option>
                    <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }} style="font-weight: bold;">
                        Cancelado
                    </option>
                </select>
            
                @error('status')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 40px;">
                <label class="field-label" style="color: var(--primary);">Notas adicionales</label>
                <textarea name="notes" rows="5" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">{{ old('notes') }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 60px; font-size: 20px; width: 100%; max-width: 500px;">
                    <i class="fas fa-save"></i> Guardar Contrato
                </button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('contracts.index') }}" style="
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
        // Cálculo automático del total
        document.getElementById('quantity').addEventListener('input', calculateTotal);
        document.getElementById('price_per_m3').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('price_per_m3').value) || 0;
            const total = quantity * price;
            document.getElementById('total').value = total.toFixed(2);
        }

        // Calcular al cargar la página (para edit)
        calculateTotal();
    </script>
@endsection