<?php

use Illuminate\Database\Seeder;
use App\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			'username' => 'admin',
			'email' => 'melvin.empleo.test@gmail.com',
            'password' => \Hash::make("admin123"),
            'user_type' => User::$user_types['admin']['code']
		]);
    }
}
