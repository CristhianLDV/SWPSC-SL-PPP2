<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManufacturerResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\Manufacturer;
use App\Traits\HasCustomFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class ManufacturerResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Manufacturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Proveedores y Fabricante';
    protected static ?string $navigationLabel = 'Proveedores ';
    protected static ?string $modelLabel = 'Proveedore';
    protected static ?string $pluralModelLabel = 'Proveedores ';
    protected static ?string $recordTitleAttribute = 'name';

    
    protected static ?int $navigationSort = 19;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->placeholder('Ingrese el nombre')
                    ->required()
                    ->maxLength(255),
                // self::customFieldsSchema(self::getModel()),
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
                     ...CreatedAtUpdatedAtComponent::render(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button()
                ->color('success')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Editar fabricante')
                ->using(function (Model $record, array $data) {
                $updated = static::handleRecordUpdateStatic($record, $data);

                Notification::make()
                ->title('Fabricante actualizado exitosamente')
                ->body('El fabricante ha sido actualizado correctamente.')
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
                        ->title('Fabricante eliminado exitosamente')
                        ->body('El fabricante ha sido eliminado correctamente.')
                        ->success()
                        ),
            ])
            ->bulkActions([
                  Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Fabricantes eliminados exitosamente')
                            ->body('Los fabricantes seleccionados fueron eliminados correctamente.')
                            ->success()
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageManufacturers::route('/'),
        ];
    }
}
