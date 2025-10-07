<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === Limpieza previa (opcional) ===
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('departments')->truncate();
        DB::table('locations')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('hardware_statuses')->truncate();
        DB::table('hardware_models')->truncate();
        DB::table('manufacturers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // === Crear rol super_admin ===
        $role = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['name' => 'super_admin']
        );

        // === Usuario administrador ===
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
            ]
        );

        // Asignar el rol
        $admin->assignRole($role);

        // === Departamentos base ===
        DB::table('departments')->insert([
            ['name' => 'Administración', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Docencia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mantenimiento', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Ubicaciones ===
        DB::table('locations')->insert([
            ['name' => 'Oficina Central', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laboratorio de Cómputo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aula de Ciencias', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Proveedores ===
        DB::table('suppliers')->insert([
            ['name' => 'Proveedor General S.A.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tech Solutions', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Fabricantes ===
        DB::table('manufacturers')->insert([
            ['name' => 'HP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dell', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Estados de hardware ===
        DB::table('hardware_statuses')->insert([
            ['name' => 'Operativo', 'color' => '#22c55e', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'En reparación', 'color' => '#eab308', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fuera de servicio', 'color' => '#ef4444', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Modelos de hardware ===
        DB::table('hardware_models')->insert([
            ['name' => 'Laptop HP Pavilion', 'number' => 'HP-15', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Proyector Epson X200', 'number' => 'EPX-200', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Impresora Canon LBP6030', 'number' => 'CNL-6030', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === Mensaje final ===
        $this->command->info('✅ Seeder ejecutado correctamente.');
        $this->command->warn('➡️ Usuario: admin@example.com / 12345678');
        $this->command->info('➡️ Rol asignado: super_admin');
    }
}
