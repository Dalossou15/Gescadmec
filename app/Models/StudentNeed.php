<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentNeed extends Model
{
    protected $fillable = [
        'student_id',
        'need_type',
        'description',
        'priority',
        'status',
        'request_date',
        'resolution_date',
        'notes'
    ];

    protected $casts = [
        'request_date' => 'date',
        'resolution_date' => 'date',
        'priority' => 'string',
        'status' => 'string'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolution_date' => now()
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && 
               $this->request_date->diffInDays(now()) > 30;
    }
}
