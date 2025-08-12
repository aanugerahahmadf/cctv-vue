<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Camera extends Model
{
    protected $fillable = [
        'building_id',
        'room_id',
        'name',
        'ip_address',
        'rtsp_url',
        'hls_playlist_path',
        'status',
        'latitude',
        'longitude',
        'is_public',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_public' => 'boolean',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getRtspUrlAttribute($value): string
    {
        if (empty($value)) {
            // Generate RTSP URL based on IP address pattern
            $ipParts = explode('.', $this->ip_address);
            $lastOctet = end($ipParts);
            return "rtsp://admin:password.123@10.56.236.{$lastOctet}/streaming/channels/";
        }
        return $value;
    }

    public function getHlsUrlAttribute(): string
    {
        return "/hls/camera_{$this->id}/playlist.m3u8";
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'online' => 'green',
            'offline' => 'red',
            'maintenance' => 'yellow',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return ucfirst($this->status);
    }
}
