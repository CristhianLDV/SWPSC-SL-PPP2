<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
   use \Sushi\Sushi;

    public function getRows(): array
    {
        $records = [];

        $consumables = Consumable::all();
        foreach ($consumables as $consumable) {
            if ($consumable->totalQuantityLeft() <= $consumable->threshold) {
                $records[] = [
                    'record_id' => $consumable->id,
                    'record' => 'Consumible',
                    'record_name' => $consumable->name,
                    'record_url' => 'consumibles',
                    'threshold' => 'Menor o igual a '.$consumable->threshold,
                    'quantity_left' => $consumable->totalQuantityLeft(),
                    'quantity' => $consumable->quantity,
                ];
            }
        }

        $licences = Licence::all();
        foreach ($licences as $licence) {
            if ($licence->totalQuantityLeft() <= $licence->threshold) {
                $records[] = [
                    'record_id' => $licence->id,
                    'record' => 'Licencia',
                    'record_name' => $licence->name,
                    'record_url' => 'licencias',
                    'threshold' => 'Menor o igual a '.$licence->threshold,
                    'quantity_left' => $licence->totalQuantityLeft(),
                    'quantity' => $licence->quantity,
                ];
            }
        }

        $components = Component::all();
        foreach ($components as $component) {
            if ($component->totalQuantityLeft() <= $component->threshold) {
                $records[] = [
                    'record_id' => $component->id,
                    'record' => 'Componente',
                    'record_name' => $component->name,
                    'record_url' => 'componentes',
                    'threshold' => 'Menor o igual a '.$component->threshold,
                    'quantity_left' => $component->totalQuantityLeft(),
                    'quantity' => $component->quantity,
                ];
            }
        }

        return $records;
    }
}
