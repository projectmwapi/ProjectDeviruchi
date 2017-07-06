<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class SystemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // System Group Seeder
        DB::table('system_group')->insert([
            // System group
            [
                "system_group_name" => "system group 1",
                "description" => "test description",
                "is_active" => 1,
                "deleted_at" => NULL,
                "created_at" => Carbon::now(),
                "updated_at" => NULL
            ],
            [
                "system_group_name" => "system group 2",
                "description" => "test description",
                "is_active" => 1,
                "deleted_at" => NULL,
                "created_at" => Carbon::now(),
                "updated_at" => NULL
            ],
            [
                "system_group_name" => "system group 3",
                "description" => "test description",
                "is_active" => 1,
                "deleted_at" => NULL,
                "created_at" => Carbon::now(),
                "updated_at" => NULL
            ],
        ]);
    }
}
