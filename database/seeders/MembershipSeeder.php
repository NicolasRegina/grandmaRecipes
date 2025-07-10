<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberships = [
            [
                'user_id' => User::where('username', 'marial')->first()->id,
                'group_id' => Group::where('name', 'Familia López')->first()->id,
                'role' => 'admin',
            ],
            [
                'user_id' => User::where('username', 'marior')->first()->id,
                'group_id' => Group::where('name', 'Cocina Saludable')->first()->id,
                'role' => 'member',
            ],
            [
                'user_id' => User::where('username', 'nicosmico')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'role' => 'admin',
            ],
            [
                'user_id' => User::where('username', 'juanp')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'role' => 'admin',
            ],
            [
                'user_id' => User::where('username', 'marial')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'role' => 'member',
            ],
        ];

        foreach ($memberships as $membership) {
            Membership::create($membership);
        }
    }
}
