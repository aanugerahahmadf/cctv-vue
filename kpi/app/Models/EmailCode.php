<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','email','purpose','code','expires_at'];

    protected $casts = [ 'expires_at' => 'datetime' ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
