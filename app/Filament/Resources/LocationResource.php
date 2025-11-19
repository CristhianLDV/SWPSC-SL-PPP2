<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\Location;
use App\Traits\HasCustomFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class LocationResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Responsables y áreas';
    protected static ?string $navigationLabel = 'Ubicaciones ';
    protected static ?string $modelLabel = 'Ubicaciones ';
    protected static ?string $pluralModelLabel = 'Ubicaciones ';
    protected static ?string $recordTitleAttribute = 'name'; 
 
    protected static ?int $navigationSort = 15;

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
             /*    self::customFieldsSchema(self::getModel()), */
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
                ->modalHeading('Editar Ubicación')
                ->using(function (Model $record, array $data) {
                $updated = static::handleRecordUpdateStatic($record, $data);

                Notification::make()
                ->title('Ubicación actualizada exitosamente')
                ->body('La ubicación ha sido actualizada correctamente.')
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
                        ->title('Ubicación eliminada exitosamente')
                        ->body('La ubicación ha sido eliminada correctamente.')
                        ->success()
                        ),
            ])
            ->bulkActions([
                 Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Ubicaciones eliminadas exitosamente')
                            ->body('Las ubicaciones seleccionadas fueron eliminadas correctamente.')
                            ->success()
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLocations::route('/'),
        ];
    }
}
