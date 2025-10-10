<x-filament::page>
    <div class="space-y-6">
        {{-- Encabezado del reporte --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Reporte de Inventario</h2>
            <p class="text-sm text-gray-500">
                Filtra la información y exporta en PDF o Excel.
            </p>

            {{-- Acciones de exportación --}}
            <div class="mt-4 flex gap-3">
                <x-filament::button color="success" wire:click="exportarPDF">
                    <x-heroicon-o-printer class="w-4 h-4 mr-2" /> Exportar PDF
                </x-filament::button>

                <x-filament::button color="info" wire:click="exportarExcel">
                    <x-heroicon-o-document-arrow-down class="w-4 h-4 mr-2" /> Exportar Excel
                </x-filament::button>
            </div>
        </div>

        {{-- Tabla de datos --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-2">
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>
