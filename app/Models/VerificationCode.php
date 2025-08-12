<?php

namespace App\Models;

use App\Mail\VerificationCodeMail;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'code',
        'type',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public static function generateCode(int $length = 6): string
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= random_int(0, 9);
        }
        return $digits;
    }

    public static function createAndSend(string $email, string $type, ?int $userId = null, int $ttlMinutes = 10): self
    {
        // Invalidate previous unused codes of same type
        static::query()
            ->where('email', $email)
            ->where('type', $type)
            ->whereNull('used_at')
            ->delete();

        $code = static::generateCode();

        $record = static::query()->create([
            'user_id' => $userId,
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'expires_at' => CarbonImmutable::now()->addMinutes($ttlMinutes),
        ]);

        Mail::to($email)->send(new VerificationCodeMail($code, $type));

        return $record;
    }

    public function isValidFor(string $email, string $type): bool
    {
        return $this->email === $email
            && $this->type === $type
            && is_null($this->used_at)
            && $this->expires_at->isFuture();
    }
}