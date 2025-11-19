<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumableResource\Pages;
use App\Filament\Resources\ConsumableResource\RelationManagers\PeopleRelationManager;
use App\Filament\Resources\Shared\ClsmComponent;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Filament\Resources\Shared\NcqComponent;
use App\Models\Consumable;
use Filament\Notifications\Notification;
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

class ConsumableResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Consumable::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Inventario Institucional';
 
    protected static ?string $navigationLabel = 'Consumibles';
    protected static ?string $modelLabel = 'Consumibles';
    protected static ?string $pluralModelLabel = 'Consumibles';
    protected static ?string $recordTitleAttribute = 'name'; 
    protected static ?int $navigationSort = 7;
   

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
            'Número Modelo' => $record->model_number,
            'Cantidad' => $record->quantity,
            'Área' => $record->department?->name ?? 'Desconocido',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                NcqComponent::render(),
                /*      self::customFieldsSchema(self::getModel()), */
                ClsmComponent::render(),

                Section::make('Fecha de compra y costo')
                    ->description('Por favor complete el siguiente formulario')
                    ->collapsible()
                    ->compact()
                    ->columns(['default' => 1, 'sm' => 2, 'md' => 4])
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de compra'),
                            
                        TextInput::make('purchase_cost')
                            ->label('Costo de compra')  
                            ->numeric()
                            ->prefix('S/'),
                            
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                    
                TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalQuantityLeft()."/".$state)
                    ->color(fn (Model $record): string => $record->totalQuantityLeft() <= 0 ? 'danger' : 'gray')
                    ->alignCenter()
                    ->label('Cantidad'),
                    
                TextColumn::make('people_count')
                    ->counts('people')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['people'])."/".$state)
                    ->sortable()
                    ->color('gray')
                    ->alignCenter()
                    ->label('Responsable')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->label('Área')
                    ->wrap()
                    ->limit(25)
                    ->toggleable()
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 25) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //FilamentExportHeaderAction::make('export'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('success')
                    ->label(''),
                    
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->label('')
                    ->successNotification(
                        Notification::make()
                            ->title('Consumible eliminado exitosamente')
                            ->body('El consumible ha sido eliminado correctamente.')
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            PeopleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsumables::route('/'),
            'create' => Pages\CreateConsumable::route('/create'),
            'edit' => Pages\EditConsumable::route('/{record}/edit'),
        ];
    }
}