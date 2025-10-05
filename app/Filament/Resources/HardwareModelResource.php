<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HardwareModelResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\HardwareModel;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HardwareModelResource extends Resource
{
    protected static ?string $model = HardwareModel::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Models & Statuses';
       public static function getModelLabel(): string
    {
        return __('Modelos de hardware');
    }


    protected static ?int $navigationSort = 101;

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
                    ->maxLength(255),
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
                    ->searchable()
                    ->label('Nombre'),
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
                            ->title('Modelo de hardware eliminado exitosamente')
                            ->body('El modelo de hardware ha sido eliminado correctamente.')
                            ->success()
                    ),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
     
     
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
        /*     'index' => Pages\ListHardwareModels::route('/'), */

            'index' => Pages\ManageHardwareModels::route('/'),
            'create' => Pages\CreateHardwareModel::route('/create'),
            'edit' => Pages\EditHardwareModel::route('/{record}/edit'),
        ];
    }
}
