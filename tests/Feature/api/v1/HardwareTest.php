<?php

namespace Tests\Feature\Api\V1;

use App\Models\Hardware;
use App\Models\HardwareModel;
use App\Models\HardwareStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\ApiCase;

class HardwareTest extends ApiCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_hardware(): void
    {
        $response = $this->getJson('/api/v1/hardware');
        $response->assertStatus(200);
    }

    public function test_can_create_hardware(): void
    {
        $hardwareModel = HardwareModel::factory()->create();
        $hardwareStatus = HardwareStatus::factory()->create();

        $hardwareData = [
            'name' => 'Sample Hardware',
            'order_number' => '12345',
            'hardware_model_id' => $hardwareModel->id,
            'hardware_status_id' => $hardwareStatus->id,
            'serial_number' => 'SN-123',
            'quantity' => 1,
            'purchase_cost' => 200.00,
        ];

        $response = $this->postJson('/api/v1/hardware', $hardwareData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('hardware', [
            'name' => 'Sample Hardware',
        ]);
    }

    public function test_can_update_hardware(): void
    {
        $hardware = Hardware::factory()->create();
        $updatedName = 'Updated Hardware Name';

        $response = $this->putJson("/api/v1/hardware/{$hardware->id}", [
            'name' => $updatedName,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('hardware', [
            'id' => $hardware->id,
            'name' => $updatedName,
        ]);
    }

    public function test_can_show_hardware(): void
    {
        $hardware = Hardware::factory()->create();

        $response = $this->getJson("/api/v1/hardware/{$hardware->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $hardware->name,
        ]);
    }

    public function test_can_delete_hardware(): void
    {
        $hardware = Hardware::factory()->create();

        $response = $this->deleteJson("/api/v1/hardware/{$hardware->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('hardware', [
            'id' => $hardware->id,
        ]);
    }

    public function test_show_returns_404_if_hardware_not_found(): void
    {
        $response = $this->getJson('/api/v1/hardware/999');
        $response->assertStatus(404);
    }

    public function test_destroy_returns_404_if_hardware_not_found(): void
    {
        $response = $this->deleteJson('/api/v1/hardware/999');
        $response->assertStatus(404);
    }

    public function test_update_returns_404_if_hardware_not_found(): void
    {
        $response = $this->putJson('/api/v1/hardware/999', [
            'name' => 'Updated Name',
        ]);
        $response->assertStatus(404);
    }
}
