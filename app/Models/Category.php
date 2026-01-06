<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Valores por defecto para los atributos.
     */
    protected $attributes = [
        'active' => true, // Nueva categoría siempre activa por defecto
        'description' => null,
    ];

    /**
     * Scope para obtener solo categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para búsqueda por nombre o descripción
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Accessor para mostrar el estado en texto legible
     */
    public function getStatusAttribute()
    {
        return $this->active ? 'Activa' : 'Inactiva';
    }

    /**
     * Accessor para color de estado (útil en vistas)
     */
    public function getStatusColorAttribute()
    {
        return $this->active ? 'green' : 'red';
    }
}