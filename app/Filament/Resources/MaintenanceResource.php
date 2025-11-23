<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\Maintenance;
use App\Traits\HasCustomFields;
use Filament\Facades\Filament;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class MaintenanceResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $navigationGroup = 'Soporte Técnico';
    protected static ?string $navigationLabel = 'Mantenimientos ';
    protected static ?string $modelLabel = 'Mantenimiento';
    protected static ?string $pluralModelLabel = 'Mantenimientos ';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 25;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
     public static function getModelLabel(): string
    {
        return __('Mantenimientos');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Section::make('Información básica')
                        ->collapsible()
                        ->compact()
                        ->columns(3)
                        ->columnSpan(4)
                        ->schema([
                            BelongsToSelect::make('hardware_id')
                                ->relationship('hardware', 'name')
                                ->label('Equipo')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('maintenance_type')
                                ->label('Tipo de mantenimiento'),
                            DatePicker::make('maintenance_date')
                                ->label('Fecha de mantenimiento'),
                            TextInput::make('performed_by')
                                ->label('Realizado por'),
                            TextInput::make('cost')
                                ->label('Costo')
                                ->numeric()
                                ->minValue(0.0)
                                ->prefix('S/'),
                        ]),
                
                ])->columns(3),
                /* self::customFieldsSchema(self::getModel()), */
                ImagesAndNoteComponent::render(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->sortable()
                ->label('ID')
                ->searchable(),
                TextColumn::make('hardware.name')
                ->sortable()
                ->label('Equipo')
                ->searchable(),
                TextColumn::make('hardware.hardware_model.name')
                ->sortable()
                ->label('Modelo')
                ->searchable(),
                TextColumn::make('maintenance_type')
                ->sortable()
                ->label('Tipo')
                ->searchable()
                ->badge(),
                TextColumn::make('performed_by')
                ->sortable()
                ->label('Realizado por')
                ->searchable(),
                TextColumn::make('maintenance_date')
                ->sortable()
                ->label('Fecha de mantenimiento')
                ->date()
                ->alignRight(),
                TextColumn::make('cost')
                ->sortable()
                ->label('Costo')
                ->prefix('S/')
                ->alignRight(),
            
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button()
                ->color('success')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Editar fabricante')
                ->using(function (Model $record, array $data) {
                $updated = static::handleRecordUpdateStatic($record, $data);

                Notification::make()
                ->title('Mantenimiento actualizado exitosamente')
                ->body('El mantenimiento ha sido actualizado correctamente.')
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
                        ->title('Mantenimiento eliminado exitosamente')
                        ->body('El mantenimiento ha sido eliminado correctamente.')
                        ->success()
                        ),
            ])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Mantenimientos eliminados exitosamente')
                            ->body('Los mantenimientos seleccionados fueron eliminados correctamente.')
                            ->success()
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMaintenances::route('/'),
        ];
    }
}
