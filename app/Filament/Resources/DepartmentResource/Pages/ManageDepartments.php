<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageDepartments extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Actions\CreateAction::make()
            ->label('Registrar áreas')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
                 // ✅ Oculta el botón "Crear y crear otro"
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('área creada exitosamente')
                    ->body('El área ha sido registrado correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
