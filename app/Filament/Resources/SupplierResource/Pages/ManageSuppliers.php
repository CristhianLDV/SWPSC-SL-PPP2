<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageSuppliers extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Actions\CreateAction::make()
            ->label('Registrar Proveedor')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
    
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
       
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('Proveedor creado exitosamente')
                    ->body('El proveedor ha sido registrado correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
