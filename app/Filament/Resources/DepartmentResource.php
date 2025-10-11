<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\Pages\ManageDepartments;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Traits\HasCustomFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class DepartmentResource extends Resource
{
    use HasCustomFields;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Responsables y áreas';
    protected static ?string $navigationLabel = 'Áreas';
    protected static ?string $modelLabel = 'Áreas';
    protected static ?string $pluralModelLabel = 'Áreas';
    protected static ?string $recordTitleAttribute = 'name'; 

    protected static ?int $navigationSort = 12;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->placeholder('Ingrese el nombre')
                    ->maxLength(255),
                self::customFieldsSchema(self::getModel()),
                ImagesAndNoteComponent::render(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
          
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button()
                ->color('success')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Editar Áreas')
                ->using(function (Model $record, array $data) {
                $updated = static::handleRecordUpdateStatic($record, $data);

                Notification::make()
                ->title('Área actualizado exitosamente')
                ->body('El área ha sido actualizado correctamente.')
                ->success()
                ->send();

            return $updated;
        }),

            Tables\Actions\DeleteAction::make()
                ->button()
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->successNotification(
                    Notification::make()
                        ->title('Área eliminado exitosamente')
                        ->body('El área ha sido eliminado correctamente.')
                        ->success()
                        ),
                    ])

                    ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Áreas eliminadas exitosamente')
                            ->body('Las áreas seleccionados fueron eliminados correctamente.')
                            ->success()
                    ),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
        ];
    }
}
