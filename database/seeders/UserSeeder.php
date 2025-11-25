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
            'name' => 'Penyedia',
            'email' => 'penyedia@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyedia2',
            'email' => 'penyedia2@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyedia3',
            'email' => 'penyedia3@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyedia4',
            'email' => 'penyedia4@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyedia5',
            'email' => 'penyedia5@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyedia6',
            'email' => 'penyedia6@bolaga.com',
            'password' => Hash::make('penyedia123'),
            'role' => 'penyedia',
        ]);
        
        User::create([
            'name' => 'Penyewa',
            'email' => 'penyewa@bolaga.com',
            'password' => Hash::make('penyewa123'),
            'role' => 'penyewa',
        ]);
    }
}
