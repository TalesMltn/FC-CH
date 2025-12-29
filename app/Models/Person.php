<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $fillable = [
        'dni',
        'name',
        'lastname',
        'phone',
        'email',
        'type',
        'address',
        'notes',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function rules()
    {
        return [
            'dni' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'name' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'lastname' => 'required|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+()]*$/',
            'email' => 'nullable|email|max:255',
            'type' => 'required|in:trabajador,cliente,proveedor,otro',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
    
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->lastname}";
    }
}