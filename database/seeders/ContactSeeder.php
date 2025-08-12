<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Admin Pertamina RU VI Balongan',
                'email' => 'admin@pertamina.com',
                'phone' => '+62-234-123456',
                'whatsapp' => '+62-812-34567890',
                'address' => 'Kilang Pertamina Internasional Refinery Unit VI Balongan, Indramayu, Jawa Barat',
                'type' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Support Teknis',
                'email' => 'support@pertamina.com',
                'phone' => '+62-234-123457',
                'whatsapp' => '+62-812-34567891',
                'address' => 'Kilang Pertamina Internasional Refinery Unit VI Balongan, Indramayu, Jawa Barat',
                'type' => 'support',
                'is_active' => true,
            ],
            [
                'name' => 'Darurat 24 Jam',
                'email' => 'emergency@pertamina.com',
                'phone' => '+62-234-123458',
                'whatsapp' => '+62-812-34567892',
                'address' => 'Kilang Pertamina Internasional Refinery Unit VI Balongan, Indramayu, Jawa Barat',
                'type' => 'emergency',
                'is_active' => true,
            ],
            [
                'name' => 'Keamanan',
                'email' => 'security@pertamina.com',
                'phone' => '+62-234-123459',
                'whatsapp' => '+62-812-34567893',
                'address' => 'Gerbang Utama, Kilang Pertamina Internasional Refinery Unit VI Balongan',
                'type' => 'emergency',
                'is_active' => true,
            ],
            [
                'name' => 'IT Support',
                'email' => 'it-support@pertamina.com',
                'phone' => '+62-234-123460',
                'whatsapp' => '+62-812-34567894',
                'address' => 'Gedung Kolaboratif, Kilang Pertamina Internasional Refinery Unit VI Balongan',
                'type' => 'support',
                'is_active' => true,
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::query()->firstOrCreate(
                ['email' => $contact['email']],
                $contact
            );
        }
    }
}
