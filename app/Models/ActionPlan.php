<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_id',
        'control_id',
        'owner_id',
        'title',
        'description',
        'priority',
        'status',
        'due_at',
        'completed_at',
    ];

    protected $casts = [
        'due_at' => 'date',
        'completed_at' => 'date',
    ];

    public function risk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    public function control(): BelongsTo
    {
        return $this->belongsTo(Control::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
