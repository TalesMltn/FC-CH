<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'person_id',
        'category_id',
        'date',
        'quantity',
        'price_per_m3',
        'total',
        'status',
        'delivery_date',
        'notes',
        'active',
    ];

    protected $casts = [
        'date' => 'date',
        'delivery_date' => 'date',
        'quantity' => 'decimal:2',
        'price_per_m3' => 'decimal:2',
        'total' => 'decimal:2',
        'active' => 'boolean',
    ];

    // Relaciones
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Calcular total automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($contract) {
            $contract->total = $contract->quantity * $contract->price_per_m3;
        });
    }

    // Generar código automático (CONT-001, CONT-002...)
    public static function generateCode()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? (int)substr($last->code, 5) + 1 : 1;
        return 'CONT-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}