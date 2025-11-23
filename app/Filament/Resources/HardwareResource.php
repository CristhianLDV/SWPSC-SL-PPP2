<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HardwareResource\Pages;
use App\Filament\Resources\HardwareResource\RelationManagers\ComponentsRelationManager;
use App\Filament\Resources\HardwareResource\RelationManagers\LicencesRelationManager;
use App\Filament\Resources\HardwareResource\RelationManagers\PeopleRelationManager;
use App\Filament\Resources\Shared\ClsmComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Models\Hardware;
use App\Traits\HasCustomFields;
use Filament\Facades\Filament;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HardwareResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Hardware::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationGroup = 'Inventario Institucional';
 
    protected static ?string $navigationLabel = 'Equipos informáticos';
    protected static ?string $modelLabel = 'Equipo ';
    protected static ?string $pluralModelLabel = 'Equipos informáticos';
    protected static ?string $recordTitleAttribute = 'name'; 

    
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->hardware_model?->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['id', 'qr_code', 'hardware_model.name', 'serial_number', 'purchase_cost', 'order_number', 'notes'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Serial No.' => $record->serial_number,
            'Estado' => $record->hardware_status?->name,
            'Área' => $record->department?->name ?? 'Desconocido',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Section::make('Información básica')
                        ->description('Proporcionar detalles para facilitar un seguimiento preciso y efectivo')
                        ->schema([   
                            TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2]),
                            BelongsToSelect::make('hardware_model_id')
                                ->relationship('hardware_model', 'name')
                                ->label('Modelo')
                                ->searchable()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->preload()
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2])
                                ->required(),
                      
                            BelongsToSelect::make('hardware_status_id')
                                ->relationship('hardware_status', 'name')
                                ->label('Estado')
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2])
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre del estado')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre del estado')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('serial_number')
                                ->label('Número de Serie')
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2]),
                        ])
                        ->collapsible()
                        ->compact()
                        ->columns(['default' => 2, 'sm' => 4, 'md' => 6])
                        ->columnSpan(['default' => 'full', 'lg' => 4]),
                ])->columns(['default' => 1, 'lg' => 2]),
          /*       self::customFieldsSchema(self::getModel()),  */
                ClsmComponent::render(false),

            //POR APLICAR

                
                    KeyValue::make('specifications')
                        ->label('Especificaciones Técnicas')
                        ->keyLabel('Atributo (Ej. Procesador, RAM, Velocidad, etc.)')
                        ->valueLabel('Descripción')
                        ->addButtonLabel('Agregar atributo')
                        ->reorderable()
                        ->nullable()
                        ->live()
                        ->columnSpan('full')
                        ->extraAttributes([
                            'style' => 'max-height: 400px; overflow-y: auto; display: block;'
                        ]),   
              

                Section::make('Fecha y costo de compra')
                    ->description('Por favor, completa el siguiente formulario')
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de compra'),
                        TextInput::make('purchase_cost')
                            ->label('Costo de compra')
                            ->numeric()
                            ->prefix('S/'),
                     
                        TextInput::make('order_number')
                            ->label('Número de orden'),
                        DatePicker::make('end_of_life_date')
                            ->label('Fecha de fin de vida útil'),
                    ])
                    ->collapsible()
                    ->compact()
                    ->columns(['default' => 1, 'sm' => 2, 'md' => 4]),

                Section::make('Disponibilidad para solicitud')
                    ->description('¿Este activo puede ser solicitado por otros?')
                    ->schema([
                        Toggle::make('requestable')
                            ->label('Disponible para solicitud'),
                        // Toggle::make('notify_me')
                        //     ->helperText('Get notified when status changes.'),
                    ])
                    ->collapsible()
                    ->compact()
                    ->columns(1),

                ImagesAndNoteComponent::render(),
            ])->columns(['default' => 1, 'lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nombre')
                    ->wrap()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                    
                TextColumn::make('hardware_model.name')
                    ->sortable()
                    ->searchable()
                    ->label('Modelo')
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
                    
                TextColumn::make('hardware_status.name')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('success')
                    ->label('Estado')
                    ->toggleable(),
                    
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('Número de serie')
                    ->badge()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: false),
                                
                TextColumn::make('location.name')
                    ->label('Ubicación')
                    ->toggleable(),

                TextColumn::make('specifications')
                    ->label('Especificaciones Técnicas')
                    ->formatStateUsing(function ($state) {
                        return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    })
                    ->wrap(),
                TextColumn::make('people_count')
                    ->counts('people')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['people'])."/".$state)
                    ->sortable()
                    ->color('gray')
                    ->label('Responsable')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
                    
                TextColumn::make('components_count')
                    ->counts('components')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['components'])."/".$state)
                    ->sortable()
                    ->color('gray')
                    ->label('Componente')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
                    
                TextColumn::make('licences_count')
                    ->counts('licences')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['licences'])."/".$state)
                    ->sortable()
                    ->color('gray')
                    ->label('Licencia')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
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
                            ->title('Equipo informático eliminado exitosamente')
                            ->body('El equipo informático ha sido eliminado correctamente.')
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ])
            ->striped()
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            LicencesRelationManager::class,
            ComponentsRelationManager::class,
            PeopleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHardware::route('/'),
            'create' => Pages\CreateHardware::route('/create'),
            'edit' => Pages\EditHardware::route('/{record}/edit'),
        ];
    }
}