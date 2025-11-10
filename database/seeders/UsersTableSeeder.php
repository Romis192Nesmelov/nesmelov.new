<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Несмелов Роман',
            'email' => 'romis.nesmelov@gmail.com',
            'phone' => '+7(926)247-77-25',
            'password' => bcrypt('apg192'),
            'is_admin' => true
        ]);
    }
}
