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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
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

    protected static ?string $navigationGroup = 'Activos físicos';
 
    protected static ?int $navigationSort = 0;

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
            'Departamento' => $record->department?->name ?? 'Desconocido',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Section::make('informacion basica')
                        ->description('Proporcionar detalles para facilitar un seguimiento preciso y efectivo')
                        ->schema([
                            BelongsToSelect::make('hardware_model_id')
                                ->relationship('hardware_model', 'name')
                                ->label('Modelo de hardware')
                                ->searchable()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre del modelo')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name')
                                        ->label('Nombre del modelo')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->preload()
                                ->columnSpan(2)
                                ->required(),
                            BelongsToSelect::make('hardware_status_id')
                                ->relationship('hardware_status', 'name')
                                ->label('Estado del hardware')
                                ->columnSpan(2)
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
                                ->label('Serial No.')
                                ->columnSpan(2),
                        ])
                        ->collapsible()
                        ->compact()
                        ->columns(6)
                        ->columnSpan(3),
                    Section::make('Código QR')
                        ->columnSpan(1)
                        ->collapsible()
                        ->compact()
                        ->schema([ViewField::make('qr_code')->view('filament.components.qr_code')]),
                ])->columns(4),

                self::customFieldsSchema(self::getModel()),

                ClsmComponent::render(false),

                Section::make('Fecha y costo de compra')
                    ->description('Por favor, completa el siguiente formulario')
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de compra'),
                        TextInput::make('purchase_cost')
                            ->label('Costo de compra')
                            ->numeric()
                            ->prefix('S/'),
                            /* ->prefix(Filament::getTenant()->currency), */
                        TextInput::make('order_number')
                            ->label('Número de orden'),
                        DatePicker::make('end_of_life_date')
                            ->label('Fecha de fin de vida útil'),
                    ])
                    ->collapsible()
                    ->compact()
                    ->columns(4),

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
                    ->columns(),

                ImagesAndNoteComponent::render(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('hardware_model.name')
                    ->sortable()
                    ->searchable()
                    ->label('Modelo de hardware')
                    ->iconPosition('after'),
                TextColumn::make('hardware_status.name')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('success')
                    ->label('Estado del hardware')
                    ->iconPosition('after'),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->alignRight()
                    ->searchable()
                    ->label('Serial No.')
                    ->badge(),
                TextColumn::make('people_count')
                    ->counts('people')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['people'])." out of $state")
                    ->sortable()
                    ->color('gray')
                    ->alignRight()
                    ->label('Personas'),
                TextColumn::make('components_count')
                    ->counts('components')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['components'])." out of $state")
                    ->sortable()
                    ->color('gray')
                    ->alignRight()
                    ->label('Componentes'),
                TextColumn::make('licences_count')
                    ->counts('licences')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->totalNotCheckedInFor(['licences'])." out of $state")
                    ->sortable()
                    ->color('gray')
                    ->alignRight()
                    ->label('Licencias'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //FilamentExportHeaderAction::make('export'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
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
