<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Shared\CreatedAtUpdatedAtComponent;
use App\Filament\Resources\Shared\ImagesAndNoteComponent;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

  
    protected static ?string $navigationIcon = 'heroicon-o-user';
    
  protected static ?string $navigationGroup = 'Filament Shield';
    protected static ?string $navigationLabel = 'Usuarios ';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios ';
    protected static ?string $recordTitleAttribute = 'name';
    
 
    protected static ?int $navigationSort = 36;

    public static function scopeEloquentQueryToTenant(Builder $query, ?Model $tenant): Builder
    {
        return $query;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(255)
                     ->dehydrateStateUsing(fn ($state) => trim($state)) // 🔹 elimina espacios antes y 
                       ->rule('regex:/^(?!\s*$)[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u') // 🔹 no permite solo espacios, ni caracteres especiales
                    ->validationMessages([
                        'required' => 'El nombre es obligatorio.',
                        'regex' => 'El nombre solo puede contener letras y espacios (sin números ni símbolos).',
                        'max' => 'El nombre no puede tener más de :max caracteres.',
                    ]),
                TextInput::make('email')
                    ->email()
                    ->label('Correo electrónico')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $context) => $context === 'create')
                    ->dehydrateStateUsing(fn ($state) => bcrypt(trim($state))) // 🔹 limpia antes de encriptar
                    ->minLength(8) // 🔹 mínimo 8 caracteres
                    ->maxLength(255)
                    ->rule('regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/') // 🔹 al menos una mayúscula, una minúscula y un número
                    ->validationMessages([
                        'min' => 'La contraseña debe tener al menos :min caracteres.',
                        'regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número.',
                        'required' => 'El campo contraseña es obligatorio.',
                    ])
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->required(fn (string $context) => $context === 'create'),
                 Select::make('roles')
                    ->required()
                    ->label('Rol')
                    ->relationship('roles', 'name') // Usa la relación de Spatie Permission
                    ->options(Role::pluck('name', 'id')) // Lista de roles disponibles
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Asigna uno o más roles a este usuario.'),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->label('Correo electrónico')
                    ->searchable(),
                 Tables\Columns\TagsColumn::make('roles.name')
                    ->label('Roles')
                    ->limit(2),
            ])
            ->filters([
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
                            ->title('Usuario eliminado exitosamente')
                            ->body('El usuario ha sido eliminado correctamente.')
                            ->success()
                    ),
            ])
            ->headerActions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

   /*  public static function canCreate(): bool
    {
        return false;
    }
 */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
