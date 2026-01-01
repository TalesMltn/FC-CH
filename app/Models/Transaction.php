<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'amount',
        'date',
        'payment_method',
        'reference',
        'notes',
        'active',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}