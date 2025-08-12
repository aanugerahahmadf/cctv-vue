<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'address',
        'type', // admin, support, emergency
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
