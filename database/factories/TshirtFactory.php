<?php

namespace Database\Factories;

use App\Models\Tshirt;
use Illuminate\Database\Eloquent\Factories\Factory;

class TshirtFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tshirt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'image' => Tshirt::DEFAULT_IMAGE,
            'status' => Tshirt::STATUS_ACTIVE,
        ];
    }
}
