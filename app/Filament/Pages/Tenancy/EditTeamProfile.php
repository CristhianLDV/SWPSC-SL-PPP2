<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Settings';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configuración del equipo')
                    ->collapsible()
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del equipo')
                            ->required(),
                        Select::make('currency')
                            ->label('Moneda')
                            ->options([
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                            ])
                            ->required(),
                    ]),

                Section::make('Configuración de notificaciones')
                    ->collapsible()
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('discordWebhookUrl')
                            ->label('Discord')
                            ->helperText('Ingrese la URL de su webhook de Discord para recibir notificaciones en caso de una alerta.'),

                        TextInput::make('slackWebhookUrl')
                            ->label('Slack')
                            ->helperText('Ingrese la URL de su webhook de Slack para recibir notificaciones en caso de una alerta.'),
                    ]),
            ]);
    }
}
