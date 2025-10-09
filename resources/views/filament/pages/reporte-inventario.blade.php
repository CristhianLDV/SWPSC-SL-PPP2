<x-filament::page>
    <x-filament::section>
        <x-slot name="heading">Reporte de Inventario</x-slot>
        <x-slot name="description">
            Filtra la información y exporta en PDF o Excel.
        </x-slot>
        {{ $this->table }}
    </x-filament::section>
</x-filament::page>
