<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrator
        $user_id = DB::table('user')->insertGetId([ //,
                "email" => "lyaneza@yondu.com",
                "role_id" => 1,
                "password" => Hash::make('test123'),
                "user_token" => Hash::make('secret'),
                "user_image" => NULL,
                "first_login" => 1,
                "invalid_attempt" => 0,
                "is_active" => 1,
                "last_login_date" => NULL,
                "locked_at" => NULL,
                "deleted_at" => NULL,
                "created_at" => Carbon::now(),
                "updated_at" => NULL,
        ]);
        
        // Password History
        DB::table('password_history')->insert([ //,
            "user_id" => $user_id,
            "password" => Hash::make('test123'),
            "count" => 1,
            "deleted_at" => NULL,
            "created_at" => Carbon::now(),
            "updated_at" => NULL,
        ]);
        
        // Emplyoee Info
        DB::table('employee')->insert([ //,
            "user_id" => $user_id,
            "employee_number" => "22-00643",
            "department_id" => 1,
            "department_id" => 1,
            "first_name" => "Leishan",
            "middle_name" => "Abanes",
            "last_name" => "Yaneza",
            "remarks" => "Sample Remarks",
        ]);
        
        // Users Access
        DB::table('user_access')->insert([ //,
            "user_id" => $user_id,
            "access_token" => NULL,
            "date_expiry" => NULL,
        ]);

    }
}
