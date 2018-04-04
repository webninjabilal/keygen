<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        $roles = User::allRoles();
        foreach($roles as $key=>$value) {
            Role::create(['id' => $key,'name' => $value, 'display_name' => $value]);
        }
    }
}
