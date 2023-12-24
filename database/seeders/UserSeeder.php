<?php

namespace Database\Seeders;

use App\Models\admin\User;
use Illuminate\Support\Str;
use App\Models\admin\UserGroup;
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
        $user_group = UserGroup::create([
            'name' => 'dev',
            'status' => 1,
        ]);
        User::create([
            'name' => 'dev',
            'email' => 'dev@daysf.com',
            'kode' => 'daysf',
            'password' => Hash::make('ikhsannwwi'),
            'status' => 1,
            'user_group_id' => 0,
            'remember_token' => Str::random(60),
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'kode' => 'admin',
            'password' => Hash::make('adminadmin'),
            'status' => 1,
            'user_group_id' => $user_group->id,
            'remember_token' => Str::random(60),
        ]);
    }
}
