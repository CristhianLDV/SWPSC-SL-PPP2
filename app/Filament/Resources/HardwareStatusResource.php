<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HardwareStatusResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\HardwareStatus;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HardwareStatusResource extends Resource
{
    protected static ?string $model = HardwareStatus::class;

   /*  protected static bool $shouldRegisterNavigation = false; */

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Configuraciones del sistema';
    protected static ?string $navigationLabel = 'Estados de equipos';
    protected static ?string $modelLabel = 'Estado de Equipo';
    protected static ?string $pluralModelLabel = 'Estado de Equipos';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 34;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Estado')
                    ->required()
                    ->maxLength(255),
                ImagesAndNoteComponent::render(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Estado')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                 Tables\Actions\EditAction::make()
                ->button()
                ->color('success'),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Estado eliminado exitosamente')
                            ->body('El estado de equipo informático  ha sido eliminado correctamente.')
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHardwareStatuses::route('/'),
            'create' => Pages\CreateHardwareStatus::route('/create'),
            'edit' => Pages\EditHardwareStatus::route('/{record}/edit'),
        ];
    }
}
