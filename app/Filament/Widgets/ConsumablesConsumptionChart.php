<?php

namespace App\Filament\Widgets;

use App\Models\ConsumablePerson;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ConsumablesConsumptionChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'consumables-consumption';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'Consumo de Consumibles';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
        // Get the last 6 months in a human-readable format.
    /*     $months = collect()->times(6, function ($number) {
            return now()->subMonths($number - 1)->format('F');
        })->reverse()->values()->all();

        // Get the count of consumables checked out per month for the last 6 months.
        $consumableData = collect()->times(6, function ($number) {
            return ConsumablePerson::whereMonth('checked_out_at', now()->subMonths($number - 1)->month)
                ->whereYear('checked_out_at', now()->subMonths($number - 1)->year)
                ->count();
        })->reverse()->values()->all(); */
         // Forzar Carbon en español
   
    // Forzar Carbon en español
    \Carbon\Carbon::setLocale('es');

    // Obtener los últimos 6 meses en español
    $months = collect()->times(6, function ($number) {
        return now()->subMonths($number - 1)->translatedFormat('F');
    })->reverse()->values()->all();

    // Obtener conteo por mes
    $consumableData = collect()->times(6, function ($number) {
        return \App\Models\ConsumablePerson::whereMonth(
                'checked_out_at',
                now()->subMonths($number - 1)->month
            )
            ->whereYear(
                'checked_out_at',
                now()->subMonths($number - 1)->year
            )
            ->count();
    })->reverse()->values()->all();

    // Retornar configuración del gráfico
    return [
        'chart' => [
            'type' => 'area',
            'height' => 170,
            'toolbar' => ['show' => false],
        ],
        'grid' => ['show' => false],
        'series' => [
            [
                'name' => 'Checked Out Consumables',
                'data' => $consumableData,
            ],
        ],
        'xaxis' => [
            'categories' => $months,
            'labels' => [
                'style' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ],
        'yaxis' => [
            'labels' => [
                'style' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ],
        'colors' => ['#AAB434'],
    ];


    }
}
