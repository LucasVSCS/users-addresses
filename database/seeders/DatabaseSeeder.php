<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 2. Cria 20 usuários (do tipo 'user' ou 'admin')
        // Para cada usuário, cria 2 endereços e os associa.
        // Isso irá popular as tabelas 'users', 'addresses' e 'users_addresses'.
        User::factory(20)
            ->has(Address::factory()->count(2)) // Cada usuário terá 2 endereços
            ->create();

        // 3. (Opcional) Cria alguns usuários sem endereços
        User::factory(5)->create();

        // 4. (Opcional) Cria alguns endereços que não pertencem a ninguém
        Address::factory(3)->create();
    }
}
