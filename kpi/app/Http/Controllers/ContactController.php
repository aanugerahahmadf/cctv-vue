<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Contact', [
            'email' => 'aanugerahahmad27@gmail.com',
            'phone' => '+62-812-0000-0000',
            'whatsapp' => '+62-812-0000-0000',
            'address' => 'Kilang Pertamina Internasional RU VI Balongan',
        ]);
    }
}
