<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = Admin::where('email', 'empanel@ipa.com')->first();
        if (! $admin) {
            Admin::create([
                'name' => 'Super Admin',
                'email' => 'empanel@ipa.com',
                'password' => Hash::make('Csiith@1111'),
                'is_super' => true,
            ]);
        } else {
            $admin->is_super = true;
            $admin->password = Hash::make('Csiith@1111');
            $admin->save();
        }
    }
}
