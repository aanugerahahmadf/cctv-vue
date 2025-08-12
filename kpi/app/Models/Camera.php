<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id','room_id','name','ip_address','rtsp_url','hls_playlist_path','status','latitude','longitude','is_public',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
