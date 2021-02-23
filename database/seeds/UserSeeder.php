<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'Luis',
            'last_name' => 'Henrique',
            'email' => 'junimhs10@gmail.com',
            'password' => bcrypt('junior')
        ]);

        factory(User::class, 5)->create();
    }
}
