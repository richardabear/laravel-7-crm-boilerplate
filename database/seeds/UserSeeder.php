<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $user = new User(['name' => 'Richard Abear', 'email' => 'richard@bearzu.com', 'password' => Hash::make('password')]);
            $user->save();
        } catch (\Exception $e) {
            // Duplicate user exception
        }
    }
}
