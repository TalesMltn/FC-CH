@extends('layouts.app')

@section('page-title', 'Registrar Nueva Persona')

@section('content')
<div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 800px; margin: 0 auto;">
    <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
        Registrar Nueva Persona
    </h2>

    <form method="POST" action="{{ route('persons.store') }}" autocomplete="off" novalidate>
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- DNI - SOLO 8 NÚMEROS -->
            <div>
                <label class="field-label" style="color: var(--primary);">DNI *</label>
                <input type="text" 
                       name="dni" 
                       value="{{ old('dni') }}" 
                       required 
                       pattern="[0-9]{8}"
                       maxlength="8" 
                       inputmode="numeric"
                       autocomplete="off"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,8)"
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="72638274">
                @error('dni')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Persona - OBLIGATORIO -->
            <div>
                <label class="field-label" style="color: var(--primary);">Tipo de Persona *</label>
                <select name="type" required style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: var(--primary); font-size: 16px; appearance: none; cursor: pointer; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27%23FF6B00%27%3e%3cpath d=%27M7 10l5 5 5-5z%27/%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 16px center; background-size: 16px;">
                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Selecciona un tipo</option>
                    <option value="cliente" {{ old('type') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="trabajador" {{ old('type') == 'trabajador' ? 'selected' : '' }}>Trabajador</option>
                    <option value="proveedor" {{ old('type') == 'proveedor' ? 'selected' : '' }}>Proveedor</option>
                    <option value="otro" {{ old('type') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('type')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Nombres - SOLO LETRAS -->
            <div>
                <label class="field-label" style="color: var(--primary);">Nombres *</label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{2,255}"
                       autocomplete="off"
                       oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑ\s]/g, '').replace(/\s+/g, ' ').trim()"
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="Juan Carlos">
                @error('name')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Apellidos - SOLO LETRAS -->
            <div>
                <label class="field-label" style="color: var(--primary);">Apellidos *</label>
                <input type="text" 
                       name="lastname" 
                       value="{{ old('lastname') }}" 
                       required 
                       pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{2,255}"
                       autocomplete="off"
                       oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑ\s]/g, '').replace(/\s+/g, ' ').trim()"
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="Pérez Gómez">
                @error('lastname')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Teléfono -->
            <div>
                <label class="field-label" style="color: var(--primary);">Teléfono</label>
                <input type="tel"
                       name="phone"
                       value="{{ old('phone') }}"
                       pattern="[0-9]{9}"
                       maxlength="9"
                       minlength="9"
                       inputmode="numeric"
                       autocomplete="tel"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,9)"
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="987654321">
            
                <small style="color: var(--text-light); font-size: 13px; display: block; margin-top: 6px;">
                    Solo 9 dígitos numéricos (ej: 987654321)
                </small>
            
                @error('phone')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="field-label" style="color: var(--primary);">Email</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       autocomplete="email"
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="usuario@ejemplo.com">
                @error('email')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Dirección -->
        <div style="margin-bottom: 30px;">
            <label class="field-label" style="color: var(--primary);">Dirección</label>
            <input type="text" 
                   name="address" 
                   value="{{ old('address') }}" 
                   autocomplete="street-address"
                   style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                   placeholder="Av. Principal 123, Huancayo">
            @error('address')
                <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notas -->
        <div style="margin-bottom: 40px;">
            <label class="field-label" style="color: var(--primary);">Notas</label>
            <textarea name="notes" 
                      rows="4" 
                      autocomplete="off"
                      style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;">
                {{ old('notes') }}
            </textarea>
            @error('notes')
                <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Checkbox activo -->
        <div style="margin-bottom: 40px;">
            <label style="display: flex; align-items: center; color: var(--text-light); font-size: 16px;">
                <input type="checkbox" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }} style="margin-right: 12px; transform: scale(1.5); width: 20px; height: 20px;">
                <span style="color: var(--primary);">Persona activa</span> (desmarcar para desactivar)
            </label>
            @error('active')
                <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="text-align: center;">
            <button type="submit" class="btn-login" style="padding: 18px 50px; font-size: 20px; width: 100%; max-width: 400px;">
                <i class="fas fa-save"></i> Guardar Persona
            </button>
        </div>

        <div style="display: flex; justify-content: flex-start; margin-top: 40px; padding: 0 20px;">
            <a href="{{ route('categories.index') }}" class="btn-secondary" style="
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
// Validación extra en tiempo real para mejor UX
document.querySelector('form').addEventListener('submit', function(e) {
    const dni = document.querySelector('input[name="dni"]').value;
    const type = document.querySelector('select[name="type"]').value;
    
    if (!/^[0-9]{8}$/.test(dni)) {
        e.preventDefault();
        alert('El DNI debe tener exactamente 8 números');
        return false;
    }
    
    if (!type) {
        e.preventDefault();
        alert('Debes seleccionar un tipo de persona');
        return false;
    }
});
</script>
@endsection