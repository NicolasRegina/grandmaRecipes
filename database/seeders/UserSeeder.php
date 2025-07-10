<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nicolás Regina',
                'email' => 'nregina@mail.com',
                'password' => Hash::make('123456'),
                'username' => 'nicosmico',
                'avatar' => null,
                'bio' => 'Encontré el cuaderno de mi abuela con sus recetas secretas y quise buscar la manera de compartirlo con el mundo.',
                'is_admin' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Mario Rosales',
                'email' => 'mariorosales@mail.com',
                'password' => Hash::make('123456'),
                'username' => 'marior',
                'avatar' => null,
                'bio' => 'Cocinera de toda la vida, amante de las recetas tradicionales.',
                'is_admin' => false,
                'is_premium' => true,
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@mail.com',
                'password' => Hash::make('123456'),
                'username' => 'juanp',
                'avatar' => null,
                'bio' => 'Fanático de la cocina casera.',
                'is_admin' => false,
                'is_premium' => false,
            ],
            [
                'name' => 'María López',
                'email' => 'maria@mail.com',
                'password' => Hash::make('123456'),
                'username' => 'marial',
                'avatar' => null,
                'bio' => 'Me encanta compartir recetas dulces.',
                'is_admin' => false,
                'is_premium' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
