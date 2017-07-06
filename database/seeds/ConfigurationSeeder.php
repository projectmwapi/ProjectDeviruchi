<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Configuration Seeder
        DB::table('configuration')->insert([
            // PASSWORD CONFIG
            [
                "configuration" => "PASSWORD_HISTORY_COUNT",
                "description" => "History count of the password",
                "value" => "6",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_AGE",
                "description" => "Password age",
                "value" => "90",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_MINIMUM_LENGTH",
                "description" => "Password minimum length",
                "value" => "8",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_REQUIRE_NUMERIC",
                "description" => "Require 1 numeric character in password",
                "value" => "1",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_REQUIRE_UPPER_LOWER_CASE",
                "description" => "Password require combination uppercase and lowercase characters",
                "value" => "1",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_REQUIRE_SPECIAL_CHARACTER",
                "description" => "Require at least 1 special character in password",
                "value" => "1",
                "config_type" => 1,
            ],
            [
                "configuration" => "ACCOUNT_INVALID_LOGIN_THRESHOLD",
                "description" => "Invalid login count",
                "value" => "5",
                "config_type" => 1,
            ],
            [
                "configuration" => "ACCOUNT_LOCKOUT_DURATION_MINS",
                "description" => "Account lock duration",
                "value" => "5",
                "config_type" => 1,
            ],
            [
                "configuration" => "PASSWORD_INACTIVE_DAYS_THRESHOLD",
                "description" => "Password inactive count",
                "value" => "60",
                "config_type" => 1,
            ],
            [
                "configuration" => "ACCOUNT_AUTO_LOG_OUT_MINS",
                "description" => "Auto log out time",
                "value" => "15",
                "config_type" => 1,
            ],
            // SMTP
            [
                "configuration" => "SMTP_HOST",
                "description" => "SMTP host of the email server of Megawide",
                "value" => "smtp.gmail.com",
                "config_type" => 2,
            ],
            [
                "configuration" => "SMTP_USERNAME",
                "description" => "Login credentials to be used to access the email server",
                "value" => "yondutesting@gmail.com",
                "config_type" => 2,
            ],
            [
                "configuration" => "SMTP_PASSWORD",
                "description" => "Login credentials to be used to access the email server",
                "value" => "myY0nduP@ssword01",
                "config_type" => 2,
            ],
            [
                "configuration" => "SMTP_PORT",
                "description" => "Port of the email server of Megawide",
                "value" => "587",
                "config_type" => 2,
            ],
            [
                "configuration" => "SMTP_SECURE",
                "description" => "Refers to email server of Megawides",
                "value" => NULL,
                "config_type" => 2,
            ],
            // DEFAULT RENTAL CHARGE
            [
                "configuration" => "RENTAL_CHARGE",
                "description" => "Monthly % rate used for items tagged as Rental during a quotation(ex. 3%)",
                "value" => NULL,
                "config_type" => 3,
            ],
            // DISCOUNT 
            [
                "configuration" => "DISCOUNT_TYPE",
                "description" => "Choice between Percentage or Amount",
                "value" => NULL,
                "config_type" => 4,
            ],
            [
                "configuration" => "DISCOUNT_TYPE_VALUE",
                "description" => "Represents value to be applied during quotation based on selected Discount Typess",
                "value" => NULL,
                "config_type" => 4,
            ],
            // RETURN AFTER DAY
            [
                "configuration" => "RETURN_AFTER_DAY",
                "description" => "Refers the # of Days after an activity before an item can be automatically considered for Return",
                "value" => NULL,
                "config_type" => 5,
            ],
            // DELIVER BEFORE DAY
            [
                "configuration" => "DELIVER_BEFORE_DAY",
                "description" => "Refers to the # of Days before the activity date can the delivery guy view  Delivery Requests",
                "value" => NULL,
                "config_type" => 6,
            ],
            // COMPANY LOGO 
            [
                "configuration" => "LOGO",
                "description" => "Maintenance for the company logo on the website",
                "value" => NULL,
                "config_type" => 7,
            ],
            // REPORT LOGO 
            [
                "configuration" => "REPORT_LOGO",
                "description" => "Maintenance for the report logo on the website",
                "value" => NULL,
                "config_type" => 7,
            ],
        ]);
    }
}
