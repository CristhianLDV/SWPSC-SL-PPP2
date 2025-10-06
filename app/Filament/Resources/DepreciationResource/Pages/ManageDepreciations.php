<?php

namespace App\Filament\Resources\DepreciationResource\Pages;

use App\Filament\Resources\DepreciationResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageDepreciations extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = DepreciationResource::class;

    protected function getHeaderActions(): array
    {
      return [
        Actions\CreateAction::make()
            ->label('Registrar Depreciación')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
    
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
       
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('Depreciación creada exitosamente')
                    ->body('La depreciación ha sido registrada correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
