<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $user = new App\User([
            'name' => 'Tester Name',
            'email' => 'tester@email.com',
            'password' => bcrypt('tester')
        ]);

        $user->save();
    }
}
