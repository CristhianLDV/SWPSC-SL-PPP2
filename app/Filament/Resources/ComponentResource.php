<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Filament\Resources\ComponentResource\RelationManagers\HardwareRelationManager;
use App\Filament\Resources\Shared\ClsmComponent;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Filament\Resources\Shared\NcqComponent;
use Filament\Notifications\Notification;
use App\Models\Component;
use App\Traits\HasCustomFields;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ComponentResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

  protected static ?string $navigationGroup = 'Inventario Institucional';
 
    protected static ?string $navigationLabel = 'Componentes';
    protected static ?string $modelLabel = 'Componentes';
    protected static ?string $pluralModelLabel = 'Componentes';
    protected static ?string $recordTitleAttribute = 'name'; 
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['id', 'qr_code', 'name', 'quantity', 'model_number', 'order_number', 'notes'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Numero Modelo' => $record->model_number,
            'Cantidad' => $record->quantity,
            'Área' => $record->department?->name ?? 'Unknown',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              /*   NcqComponent::render(), */
                /* self::customFieldsSchema(self::getModel()), */
                ClsmComponent::render(),

                Section::make('Fecha de compra y costo')
                    ->description('Por favor complete el siguiente formulario')
                    ->collapsible()
                    ->compact()
                    ->columns(4)
                    ->schema([
                        DatePicker::make('purchase_date'),
                        TextInput::make('purchase_cost')
                            ->label('Costo de compra')
                            ->numeric()
                            ->prefix('S/')
                            ->minValue(0),
                        TextInput::make('model_number')
                            ->label('Modelo'),
                        TextInput::make('order_number')
                            ->label('Orden'),
                    ]),

                ImagesAndNoteComponent::render(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->iconPosition('after')
                    ->label('Nombre')
                    ->searchable()->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad total')
                    ->searchable()
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalQuantityLeft()." de $state")
                    ->sortable()
                    ->color(fn (Model $record): string => $record->totalQuantityLeft() <= 0 ? 'danger' : 'gray')
                    ->alignRight()
                    ->label('Cantidad'),
                TextColumn::make('hardware_count')
                    ->counts('hardware')
                    ->label('Cantidad total')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['hardware'])." de $state")
                    ->sortable()
                    ->color('gray')
                    ->alignRight()
                    ->label('Equipo'),
                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->label('Área')
                    ->alignRight(),
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
                            ->title('Componente eliminado exitosamente')
                            ->body('El componente ha sido eliminado correctamente.')
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
            HardwareRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }
}
