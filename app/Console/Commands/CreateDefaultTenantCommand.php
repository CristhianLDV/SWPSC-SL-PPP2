<?php

namespace App\Console\Commands;

use App\Models\HardwareStatus;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDefaultTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa el sistema creando el usuario administrador y los estados de los equipos informáticos por defecto.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando configuración del sistema...');

        // 1️⃣ Crear usuario administrador si no existe
        if (! User::where('email', 'admin@marmotte.io')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@marmotte.io',
                'password' => Hash::make('marmotte.io'),
            ]);

            $this->info('Usuario administrador creado: admin@marmotte.io / marmotte.io');
        } else {
            $this->info('El usuario administrador ya existe.');
        }

        // 2️⃣ Crear los estados de hardware básicos
        $hardwareStatuses = [
            'En uso',
            'En inventario',
            'En reparación',
            'Retirado',
            'Perdido/robado',
        ];

        foreach ($hardwareStatuses as $name) {
            HardwareStatus::firstOrCreate(['name' => $name]);
        }

        $this->info('Estados de equipos informáticos inicializados correctamente.');
        $this->info('✅ Sistema configurado con éxito.');
    }
}
