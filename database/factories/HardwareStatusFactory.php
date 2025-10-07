<?php

namespace Database\Factories;

use App\Models\HardwareStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareStatusFactory extends Factory
{
    protected $model = HardwareStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Activo',
                'Inactivo',
                'En mantenimiento',
                'Retirado',
                'Perdido o robado',
            ]),
            'color' => $this->faker->safeColorName(),
            'notes' => $this->faker->sentence(),
            'files' => null,
        ];
    }
}
