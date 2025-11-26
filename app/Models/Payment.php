<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'inscription_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'payment_type',
        'notes',
        'receipt_number'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'payment_method' => 'string',
        'payment_type' => 'string'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function generateReceiptNumber(): string
    {
        $lastPayment = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastPayment ? intval(substr($lastPayment->receipt_number, -4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return 'REC-' . date('Y') . '-' . $newNumber;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->receipt_number)) {
                $payment->receipt_number = $payment->generateReceiptNumber();
            }
        });
    }
}
