<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use App\Models\Hardware;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarioExport;

class ReporteInventario extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Centro de Reportes';
    protected static ?string $title = 'Reporte de Inventario';
    protected static string $view = 'filament.pages.reporte-inventario';

    // Variables para filtros activos
    public ?int $f_status = null;
    public ?int $f_department = null;
    public ?int $f_supplier = null;
    public ?int $f_location = null;
    public ?string $f_desde = null;
    public ?string $f_hasta = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Hardware::query()->with([
                    'hardware_status', 'department', 'location', 'supplier', 'hardware_model'
                ])
            )
            ->columns([
                TextColumn::make('name')->label('Equipo')->searchable()->sortable(),
                TextColumn::make('hardware_status.name')->label('Estado')->badge(),
                TextColumn::make('department.name')->label('Departamento'),
                TextColumn::make('location.name')->label('Ubicación'),
                TextColumn::make('supplier.name')->label('Proveedor'),
                TextColumn::make('hardware_model.name')->label('Modelo'),
                TextColumn::make('purchase_date')->label('Fecha compra')->date(),
                TextColumn::make('purchase_cost')->label('Costo (S/.)')->money('PEN'),
            ])
            ->filters([
                SelectFilter::make('hardware_status_id')
                    ->label('Estado')
                    ->relationship('hardware_status', 'name'),

                SelectFilter::make('department_id')
                    ->label('Departamento')
                    ->relationship('department', 'name'),

                SelectFilter::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name'),

                SelectFilter::make('location_id')
                    ->label('Ubicación')
                    ->relationship('location', 'name'),

                Filter::make('purchase_date')
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['desde'] ?? null) {
                            $query->whereDate('purchase_date', '>=', $data['desde']);
                        }

                        if ($data['hasta'] ?? null) {
                            $query->whereDate('purchase_date', '<=', $data['hasta']);
                        }

                        $this->f_desde = $data['desde'] ?? null;
                        $this->f_hasta = $data['hasta'] ?? null;

                        return $query;
                    }),
            ])
             ->actions([
            Tables\Actions\Action::make('ver_detalle')
                ->label('Ver Detalle')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->action(function (Hardware $record) {
                    $data = $record->load(['components', 'licences', 'maintenances', 'people']);
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.detalle-hardware', compact('data'));
                    return response()->streamDownload(fn () => print($pdf->output()), 'detalle_hardware.pdf');
                }),        
        ])

            ->headerActions([
               /*  Tables\Actions\Action::make('pdf')
                    ->label('Exportar PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(fn() => $this->exportarPDF()),

                Tables\Actions\Action::make('excel')
                    ->label('Exportar Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(fn() => $this->exportarExcel()), */
               Tables\Actions\Action::make('detalle')
                ->label('Ver detalle')
                ->icon('heroicon-o-eye')
                ->action(fn (Hardware $record) => $this->verDetalle($record)), 
                
     
                    
            ]);
    }

    // Exportar PDF
    public function exportarPDF()
    {
        $q = Hardware::with(['hardware_status', 'department', 'location', 'supplier', 'hardware_model']);

        if ($this->f_status)     $q->where('hardware_status_id', $this->f_status);
        if ($this->f_department) $q->where('department_id', $this->f_department);
        if ($this->f_supplier)   $q->where('supplier_id', $this->f_supplier);
        if ($this->f_location)   $q->where('location_id', $this->f_location);
        if ($this->f_desde)      $q->whereDate('purchase_date', '>=', $this->f_desde);
        if ($this->f_hasta)      $q->whereDate('purchase_date', '<=', $this->f_hasta);

        $data = $q->get();

        $pdf = Pdf::loadView('reportes.inventario', compact('data'));
        return response()->streamDownload(fn() => print($pdf->output()), 'reporte_inventario.pdf');
    }

    // Exportar Excel
    public function exportarExcel()
    {
        return Excel::download(
            new InventarioExport(
                $this->f_status,
                $this->f_department,
                $this->f_supplier,
                $this->f_location,
                $this->f_desde,
                $this->f_hasta,
            ),
            'reporte_inventario.xlsx'
        );
    }
}
