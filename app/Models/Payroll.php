<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'payment_date',
        'year',
        'month',
        'base_salary',
        'bonus',
        'discounts',
        'loan_deduction',
        'total_paid',
        'payment_method',
        'notes',
        'active',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'year' => 'integer',
        'month' => 'integer',
        'base_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'discounts' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    // Calcular total pagado automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payroll) {
            $payroll->total_paid = $payroll->base_salary + $payroll->bonus - $payroll->discounts - $payroll->loan_deduction;
        });
    }
}