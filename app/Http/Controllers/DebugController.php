<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function index()
    {
        // Página de debug solo para developer/admin
        return view('debug.index'); // crea esta vista después
        // O simplemente:
        // return "Panel de Debug - Solo para Developers y Admins";
    }
}