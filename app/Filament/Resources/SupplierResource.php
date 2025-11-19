<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use App\Traits\HasCustomFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class SupplierResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Proveedores y Fabricante';
    protected static ?string $navigationLabel = 'Fabricantes ';
    protected static ?string $modelLabel = 'Fabricante';
    protected static ?string $pluralModelLabel = 'Fabricantes ';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 17;


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
                    ->modalHeading('Editar proveedor')
                    ->using(function (Model $record, array $data) {
                $updated = static::handleRecordUpdateStatic($record, $data);

                        Notification::make()
                            ->title('Proveedor actualizado exitosamente')
                            ->body('El proveedor ha sido actualizado correctamente.')
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
                            ->title('Proveedor eliminado exitosamente')
                            ->body('El proveedor ha sido eliminado correctamente.')
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                ->color('danger')
            ->successNotification(
                Notification::make()
                    ->title('Proveedores eliminados exitosamente')
                    ->body('Los proveedores seleccionados fueron eliminados correctamente.')
                    ->success()
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSuppliers::route('/'),
        ];
    }
}
