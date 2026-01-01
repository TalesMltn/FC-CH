<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'loan_date',
        'amount',
        'installments',
        'installment_amount',
        'paid_amount',
        'pending_amount',
        'status',
        'notes',
        'active',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    // Calcular cuota y saldo pendiente automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($loan) {
            $loan->installment_amount = $loan->amount / $loan->installments;
            $loan->pending_amount = $loan->amount - $loan->paid_amount;

            // Cambiar estado automáticamente
            if ($loan->pending_amount <= 0) {
                $loan->status = 'pagado';
            } elseif ($loan->pending_amount < $loan->amount) {
                $loan->status = 'activo';
            }
        });
    }
}