<?php

namespace Alamia\RestApi\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\User;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Typically Role ID 1 is Administrator in Krayin
        $roleId = 1;

        $email = 'amr.shah@gmail.com';
        $user = User::where('email', $email)->first();

        if (!$user) {
            User::create([
                'name' => 'Amr Shah',
                'email' => $email,
                'password' => bcrypt('password'),
                'status' => 1,
                'role_id' => $roleId,
                'view_permission' => 'global',
            ]);
        } else {
            $user->password = bcrypt('password');
            $user->save();
        }
    }
}

