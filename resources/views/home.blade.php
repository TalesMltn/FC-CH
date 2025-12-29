@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div style="text-align: center; padding: 50px;">
        <h1 style="font-size: 48px; color: #ff6600; margin-bottom: 20px;">
            ¡Bienvenido al Sistema, {{ auth()->user()->name }}!
        </h1>
        <p style="font-size: 20px; color: #ccc;">
            Sistema de Gestión - Concretera Huancayo
        </p>
        <p style="margin-top: 40px; color: #ff6600;">
            <strong>Rol:</strong> 
            {{ auth()->user()->role == 'developer' ? 'Desarrollador Principal' : 'Administrador' }}
        </p>
        <div style="margin-top: 60px;">
            <i class="fas fa-hard-hat" style="font-size: 100px; color: #ff6600; opacity: 0.8;"></i>
        </div>
    </div>
@endsection