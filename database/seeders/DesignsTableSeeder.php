<?php

namespace Database\Seeders;

use App\Models\Design;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DesignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $faker = \Faker\Factory::create();
        $date = $faker->dateTimeThisYear;

        DB::table('designs')->insert([
            'name' => $faker->word,
            'hash' => $faker->uuid,
            'status' => Design::STATUS_ACTIVE,
            'tshirt_id' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);

    }
}
