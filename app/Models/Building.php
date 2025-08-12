<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
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
