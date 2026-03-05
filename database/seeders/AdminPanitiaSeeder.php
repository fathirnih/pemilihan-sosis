<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Panitia;

class AdminPanitiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test admin
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'email' => 'admin@pemilihan.local',
                'aktif' => true,
            ]
        );

        // Create test panitia members
        $panitiaMembers = [
            [
                'nama' => 'Budi Santoso',
                'username' => 'panitia',
                'password' => 'panitia123',
                'email' => 'panitia@pemilihan.local',
                'jabatan' => 'Ketua Panitia',
            ],
            [
                'nama' => 'Citra Dewi',
                'username' => 'panitia_2',
                'password' => 'panitia123',
                'email' => 'panitia2@pemilihan.local',
                'jabatan' => 'Wakil Ketua',
            ],
            [
                'nama' => 'Dina Kusuma',
                'username' => 'panitia_3',
                'password' => 'panitia123',
                'email' => 'panitia3@pemilihan.local',
                'jabatan' => 'Anggota',
            ],
        ];

        foreach ($panitiaMembers as $panitia) {
            Panitia::updateOrCreate(
                ['username' => $panitia['username']],
                [
                    'nama' => $panitia['nama'],
                    'username' => $panitia['username'],
                    'password' => bcrypt($panitia['password']),
                    'email' => $panitia['email'],
                    'jabatan' => $panitia['jabatan'],
                    'aktif' => true,
                ]
            );
        }

        $this->command->info('Admin dan Panitia seeder completed successfully!');
        $this->command->info('Admin login: admin / admin123');
        $this->command->info('Panitia login: panitia / panitia123');
    }
}
