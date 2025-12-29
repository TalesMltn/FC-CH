@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Panel de Debug</h1>
        <p>Bienvenido al panel de desarrollo. Solo visible para roles: admin o developer.</p>

        <div class="card mt-4">
            <div class="card-header">Información del usuario</div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Roles:</strong> {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
            </div>
        </div>
    </div>
@endsection