<?php

namespace App\Filament\Resources\HardwareResource\RelationManagers;

use App\Models\Licence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LicencesRelationManager extends RelationManager
{
    protected static string $relationship = 'licences';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->licences()->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('Nombre')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->url(fn (Licence $record) => "/admin/licences/{$record->licence_id}/edit")
                    ->getStateUsing(fn (Licence $record): string => $record->name)
                    ->iconPosition('after')
                    ->searchable()
                    ->icon('heroicon-o-arrow-right'),

                TextColumn::make('licensed_to_name')
                    ->label('Licenciado a')
                    ->searchable(),

                TextColumn::make('product_key')
                    ->label('Clave del producto')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('checked_out_at')
                    ->label('Fecha de asignación')
                    ->alignRight(),

                TextColumn::make('checked_in_at')
                    ->label('Fecha de devolución')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Vincular licencia'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->action(function (Licence $record) {
                        $record->pivot->find($record->pivot_id)?->touch('checked_in_at');
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Licence $record) => empty($record->checked_in_at)),
            ]);
    }
}
