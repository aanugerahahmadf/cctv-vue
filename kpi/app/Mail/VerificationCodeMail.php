<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $purpose;

    public function __construct(string $code, string $purpose)
    {
        $this->code = $code;
        $this->purpose = $purpose;
    }

    public function build(): self
    {
        return $this->subject('Kode Verifikasi: '.$this->purpose)
            ->view('emails.verification-code')
            ->with([ 'code' => $this->code, 'purpose' => $this->purpose ]);
    }
}
