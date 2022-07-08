<?php

namespace Database\Factories\Met;

use App\Models\Met\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hardware_id' => null,
            'label' => $this->faker->word,
            'type' => $this->faker->randomElement(['chinas', 'davis']),
            'longitude' => 0.123456,
            'latitude' => 0.123456,
            'altitude' => 1000.01,
        ];
    }
}
