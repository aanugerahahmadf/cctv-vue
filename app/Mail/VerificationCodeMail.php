<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $type;

    public function __construct(string $code, string $type)
    {
        $this->code = $code;
        $this->type = $type;
    }

    public function build()
    {
        $subject = match ($this->type) {
            'register' => 'Kode Verifikasi Pendaftaran',
            'reset' => 'Kode Reset Password',
            'change_email' => 'Kode Verifikasi Perubahan Email',
            'change_password' => 'Kode Verifikasi Perubahan Password',
            default => 'Kode Verifikasi',
        };

        return $this->subject($subject)
            ->view('emails.verification_code');
    }
}