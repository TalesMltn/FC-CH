@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div style="
        min-height: 100vh;
        background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)),
                    url('/images/mi-fondito.jpg') no-repeat center center/cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 50px;
    ">
        <h1 style="
            font-size: 52px;
            color: #ff6600;
            margin-bottom: 20px;
            text-shadow: 0 0 30px rgba(255,102,0,0.8);
            font-weight: 700;
        ">
            ¡Bienvenido al Sistema, {{ auth()->user()->name }}!
        </h1>

        <p style="
            font-size: 24px;
            color: #ccc;
            margin-bottom: 40px;
        ">
            Sistema de Gestión - Concretera Huancayo
        </p>

        <p style="
            font-size: 20px;
            color: #ff6600;
            margin-bottom: 60px;
            font-weight: 600;
        ">
            <strong>Rol:</strong> 
            {{ auth()->user()->role == 'developer' ? 'Desarrollador Principal' : 'Administrador' }}
        </p>

        <div style="margin-top: 40px;">
            <i class="fas fa-hard-hat" style="
                font-size: 120px;
                color: #ff6600;
                opacity: 0.9;
                text-shadow: 0 0 40px rgba(255,102,0,0.7);
            "></i>
        </div>
    </div>
@endsection