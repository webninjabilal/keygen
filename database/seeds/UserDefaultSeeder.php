<?php

use App\Company;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('users')->delete();
        $company = Company::create([
            'name' => 'Erchonia',
            'address' => 'Erchonia'
        ]);

        $user = User::create([
            'first_name'    => 'Rafferty',
            'last_name'     => 'Pendary',
            'email'         => 'rafferty@studio98.com',
            'password'      => bcrypt('admin1234'),
            'phone'         => '123456',
            'gender'        => 'Male',
            'user_name'     => 'ahmad',
            'is_admin'      => 1,
        ]);
        $company->assignContact($user,[]);
        $user->assignRole(1);
    }
}
