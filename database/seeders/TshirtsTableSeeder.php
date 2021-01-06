<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tshirt;

class TshirtsTableSeeder extends Seeder
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

        DB::table('tshirts')->insert([
            'name' => $faker->word,
            'image' => Tshirt::DEFAULT_IMAGE,
            'status' => Tshirt::STATUS_ACTIVE,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
