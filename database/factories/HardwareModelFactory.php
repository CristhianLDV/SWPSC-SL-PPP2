<?php

namespace Database\Factories;

use App\Models\HardwareModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareModelFactory extends Factory
{
    protected $model = HardwareModel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'number' => $this->faker->numerify('MOD-###'),
            'requestable' => $this->faker->boolean(80),
            'image' => null,
            'notes' => $this->faker->sentence(),
            'files' => null,
        ];
    }
}
