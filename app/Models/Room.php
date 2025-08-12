<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'name',
        'floor',
        'description',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function cameras(): HasMany
    {
        return $this->hasMany(Camera::class);
    }

    public function getOnlineCamerasCountAttribute(): int
    {
        return $this->cameras()->where('status', 'online')->count();
    }

    public function getOfflineCamerasCountAttribute(): int
    {
        return $this->cameras()->where('status', 'offline')->count();
    }

    public function getMaintenanceCamerasCountAttribute(): int
    {
        return $this->cameras()->where('status', 'maintenance')->count();
    }
}
