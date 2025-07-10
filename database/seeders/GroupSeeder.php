<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Familia López',
                'description' => 'Grupo privado para compartir recetas familiares.',
                'image' => null,
                'creator_id' => User::where('username', 'marial')->first()->id,
                'category' => 'Familia',
                'is_private' => true,
                'invite_code' => 'FAMLOPEZ2025',
            ],
            [
                'name' => 'Cocina Saludable',
                'description' => 'Recetas sanas y fáciles para todos.',
                'image' => null,
                'creator_id' => User::where('username', 'juanp')->first()->id,
                'category' => 'Salud',
                'is_private' => false,
                'invite_code' => 'SALUDABLE2025',
            ],
            [
                'name' => 'Abuela Ilda',
                'description' => 'Lo mejor del cuaderno de la abuela va a estar acá',
                'image' => null,
                'creator_id' => User::where('username', 'nicosmico')->first()->id,
                'category' => 'Familia',
                'is_private' => true,
                'invite_code' => 'ILDA2025',
            ]
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}
