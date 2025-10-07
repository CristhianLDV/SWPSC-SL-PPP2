<?php

namespace Database\Factories;

use App\Models\Hardware;
use App\Models\HardwareModel;
use App\Models\HardwareStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareFactory extends Factory
{
    protected $model = Hardware::class;

    public function definition(): array
    {
        $hardwareModel = HardwareModel::factory()->create();
        $hardwareStatus = HardwareStatus::factory()->create();

        return [
            'name' => $this->faker->word(),
            'order_number' => $this->faker->randomNumber(5),
            'serial_number' => $this->faker->uuid(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'purchase_cost' => $this->faker->randomFloat(2, 100, 5000),
            'purchase_date' => $this->faker->date(),
            'hardware_model_id' => $hardwareModel->id,
            'hardware_status_id' => $hardwareStatus->id,
            'notes' => $this->faker->sentence(),
        ];
    }
}
