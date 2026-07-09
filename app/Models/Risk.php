<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'category',
        'inherent_likelihood',
        'inherent_impact',
        'residual_likelihood',
        'residual_impact',
        'status',
        'identified_at',
        'reviewed_at',
    ];

    protected $casts = [
        'identified_at' => 'date',
        'reviewed_at' => 'date',
        'inherent_likelihood' => 'integer',
        'inherent_impact' => 'integer',
        'residual_likelihood' => 'integer',
        'residual_impact' => 'integer',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function controls(): HasMany
    {
        return $this->hasMany(Control::class);
    }

    public function inherentScore(): int
    {
        return $this->inherent_likelihood * $this->inherent_impact;
    }

    public function residualScore(): ?int
    {
        if ($this->residual_likelihood === null || $this->residual_impact === null) {
            return null;
        }

        return $this->residual_likelihood * $this->residual_impact;
    }
}
