<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Tabla: consumable_person
         * (Personas que tienen consumibles asignados)
         */
        Schema::create('consumable_person', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('consumable_id');

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('consumable_id')->references('id')->on('consumables')->onDelete('cascade');
        });

        /**
         * Tabla: licence_person
         * (Licencias asignadas a personas)
         */
        Schema::create('licence_person', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('licence_id');

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('licence_id')->references('id')->on('licences')->onDelete('cascade');
        });

        /**
         * Tabla: hardware_licence
         * (Licencias vinculadas a hardware)
         */
        Schema::create('hardware_licence', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('licence_id');

            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->foreign('licence_id')->references('id')->on('licences')->onDelete('cascade');
        });

        /**
         * Tabla: component_hardware
         * (Componentes físicos asignados a hardware)
         */
        Schema::create('component_hardware', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();

            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('component_id');

            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->foreign('component_id')->references('id')->on('components')->onDelete('cascade');
        });

        /**
         * Tabla: hardware_person
         * (Relación entre personas y hardware — préstamos)
         */
        Schema::create('hardware_person', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();

            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('person_id');

            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hardware_person');
        Schema::dropIfExists('component_hardware');
        Schema::dropIfExists('hardware_licence');
        Schema::dropIfExists('licence_person');
        Schema::dropIfExists('consumable_person');
    }
};
