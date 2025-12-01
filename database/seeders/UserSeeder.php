<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Penyedia',
            'email' => 'penyedia@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyedia2',
            'email' => 'penyedia2@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyedia3',
            'email' => 'penyedia3@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyedia4',
            'email' => 'penyedia4@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyedia5',
            'email' => 'penyedia5@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyedia6',
            'email' => 'penyedia6@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'nama' => 'Penyewa',
            'email' => 'penyewa@bolaga.com',
            'password' => Hash::make('penyewa123'),
            'role' => 'penyewa',
        ]);
        
        User::create([
            'nama' => 'Admin User',
            'email' => 'admin@bolaga.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}