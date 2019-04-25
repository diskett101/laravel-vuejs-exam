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
        User::create([
			'username' => 'admin',
			'email' => 'melvin.empleo.test@gmail.com',
			'password' => \Hash::make('admin#1234')
		]);
    }
}
