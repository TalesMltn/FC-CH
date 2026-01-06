@extends('layouts.app')

@section('page-title', 'Nueva Categoría')

@section('content')
    <div class="card" style="background: var(--dark-light); border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 800px; margin: 0 auto;">
        <h2 style="color: var(--primary); font-size: 32px; text-align: center; margin-bottom: 40px;">
            Crear Nueva Categoría
        </h2>

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary);">Nombre de la Categoría *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px;"
                       placeholder="Ej. Cemento, Arena, Grava, Aditivos">
                @error('name')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 30px;">
                <label class="field-label" style="color: var(--primary);">Descripción</label>
                <textarea name="description" rows="5" 
                          style="width: 100%; padding: 16px; border-radius: 15px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 16px; resize: vertical;"
                          placeholder="Descripción opcional de la categoría...">{{ old('description') }}</textarea>
                @error('description')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkbox activo - CORREGIDO -->
            <div style="margin-bottom: 40px;">
                <input type="hidden" name="active" value="0">

                <label style="display: flex; align-items: center; color: var(--text-light); font-size: 16px;">
                    <input type="checkbox" 
                           name="active" 
                           value="1" 
                           {{ old('active', 1) ? 'checked' : '' }} 
                           style="margin-right: 12px; transform: scale(1.5); width: 20px; height: 20px;">
                    <span style="color: var(--primary);">Categoría activa</span> (desmarcar para desactivar)
                </label>
                
                @error('active')
                    <p style="color: #f66; margin-top: 8px; font-size: 14px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-login" style="padding: 18px 50px; font-size: 20px; width: 100%; max-width: 400px;">
                    <i class="fas fa-save"></i> Guardar Categoría
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
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection