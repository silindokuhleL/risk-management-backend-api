<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Control extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_id',
        'owner_id',
        'title',
        'description',
        'type',
        'effectiveness',
        'status',
        'due_at',
        'tested_at',
    ];

    protected $casts = [
        'due_at' => 'date',
        'tested_at' => 'date',
    ];

    public function risk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
