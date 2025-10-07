<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Tabla: activities
         * Registra acciones o eventos realizados en el sistema
         */
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: "Equipo asignado", "Licencia creada"
            $table->text('content'); // Descripción o detalles del evento
            $table->timestamps();
        });

        /**
         * Tabla: backups
         * Registra los archivos de respaldo creados por el sistema
         */
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename'); // Nombre del archivo .zip/.sql del backup
            $table->timestamps();
        });

        /**
         * Tabla: files
         * Almacena archivos subidos o adjuntos genéricos
         */
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable(); // ruta del archivo
            $table->string('name')->nullable(); // nombre del archivo
            $table->string('mime_type')->nullable(); // tipo MIME (ej: image/png)
            $table->bigInteger('size')->nullable(); // tamaño en bytes
            $table->timestamps();
        });

        /**
         * Tabla: user_groups (opcional)
         * Agrupa usuarios por roles internos o equipos
         */
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('files');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('activities');
    }
};
