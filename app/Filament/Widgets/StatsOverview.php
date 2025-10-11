<?php

namespace App\Filament\Widgets;

use App\Models\Component;
use App\Models\Consumable;
use App\Models\Hardware;
use App\Models\Licence;
use App\Models\Maintenance;
use App\Models\Person;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -100;

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Equipos informaticos', Hardware::count()),
            Stat::make('Licencias', Licence::count()),
            Stat::make('Consumibles', Consumable::count()),
            Stat::make('Componentes', Component::count()),
            Stat::make('Responsables', Person::count()),
            Stat::make('Mantenimientos', Maintenance::count()),
        ];
    }
}
