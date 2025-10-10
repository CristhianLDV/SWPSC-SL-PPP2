<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomFieldResource\Pages;
use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Models\CustomField;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomFieldResource extends Resource
{
    protected static ?string $model = CustomField::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
       public static function getModelLabel(): string
    {
        return __('Campos personalizados');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Section::make('información básica del campo personalizado')
                        ->description('Proporcione detalles para facilitar un seguimiento preciso y efectivo')
                        ->collapsible()
                        ->compact()
                        ->columns(3)
                        ->label('Detalles')
                        ->columnSpan(3)
                        ->schema([
                            TextInput::make('name')
                            ->label('Nombre del campo')
                            ->required(),
                            Select::make('field_type')
                                ->options([
                                    'text' => 'Texto',
                                    'number' => 'Número',
                                    'date' => 'Fecha',
                                ])
                                ->default('text')
                                ->label('Tipo de campo')
                      
                                
                                ->required(),
                            Select::make('applicable_model')
                                ->options([
                                    'App\Models\Hardware' => 'Hardware',
                                    'App\Models\Component' => 'Componente',
                                    'App\Models\Consumable' => 'Consumible',
                                    'App\Models\Department' => 'Departamento',
                                    'App\Models\Depreciation' => 'Depreciación',
                                    'App\Models\Licence' => 'Licencia',
                                    'App\Models\Location' => 'Ubicación',
                                    'App\Models\Maintenance' => 'Mantenimiento',
                                    'App\Models\Manufacturer' => 'Fabricante',
                                    'App\Models\Person' => 'Persona',
                                    'App\Models\Supplier' => 'Proveedor',
                                ])
                                ->label('Modelo aplicable')
                                ->required(),
                             
                                
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable()->label('ID'),
                TextColumn::make('name')->searchable()->sortable()->label('Nombre'),
                TextColumn::make('field_type')->searchable()->sortable()->badge()->label('Tipo de campo'),
                TextColumn::make('applicable_model')
                    ->searchable()
                    ->formatStateUsing(function (string $state): string {

                        \Filament\Notifications\Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->sendToDatabase(auth()->user());

                        return class_basename($state);
                    })
                    ->sortable()
                    ->badge(),
                ...CreatedAtUpdatedAtComponent::render(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCustomFields::route('/'),
        ];
    }
}
