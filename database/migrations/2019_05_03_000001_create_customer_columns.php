<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === USERS ===
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });

        // === PASSWORD RESET TOKENS ===
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // === DEPARTMENTS ===
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        // === LOCATIONS ===
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        // === SUPPLIERS ===
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        // === HARDWARE STATUSES ===
        Schema::create('hardware_statuses', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // === HARDWARE MODELS ===
        Schema::create('hardware_models', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->string('number')->nullable();
            $table->boolean('requestable')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // === MANUFACTURERS ===
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        // === COMPONENTS ===
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('order_number')->nullable();
            $table->string('model_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('name');
            $table->timestamps();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });

        // === HARDWARE ===
        Schema::create('hardware', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name')->nullable();
            $table->string('order_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('hardware_model_id');
            $table->foreign('hardware_model_id')->references('id')->on('hardware_models')->onDelete('cascade');
            $table->unsignedBigInteger('hardware_status_id');
            $table->foreign('hardware_status_id')->references('id')->on('hardware_statuses')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->date('expected_checkin_date')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('end_of_life_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->boolean('requestable')->default(true);
            $table->timestamps();
        });

        // === LICENCES ===
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->string('licensed_to_name')->nullable();
            $table->string('licensed_to_email')->nullable();
            $table->string('product_key')->nullable();
            $table->string('order_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('name');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });

        // === PEOPLE ===
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // === CONSUMABLES ===
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->string('model_number')->nullable();
            $table->string('order_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('name');
            $table->timestamps();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });

        // === DEPRECIATIONS ===
        Schema::create('depreciations', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->unsignedBigInteger('hardware_id');
            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->enum('method', ['straight_line', 'double_declining', 'units_of_production']);
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('residual_value', 15, 2);
            $table->integer('useful_life_years');
            $table->decimal('depreciation_expense', 15, 2)->nullable();
            $table->decimal('accumulated_depreciation', 15, 2)->nullable();
            $table->decimal('current_book_value', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // === MAINTENANCES ===
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->json('files')->nullable();
            $table->unsignedBigInteger('hardware_id');
            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->date('maintenance_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('maintenance_type')->nullable();
            $table->string('performed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('depreciations');
        Schema::dropIfExists('consumables');
        Schema::dropIfExists('people');
        Schema::dropIfExists('licences');
        Schema::dropIfExists('hardware');
        Schema::dropIfExists('components');
        Schema::dropIfExists('hardware_models');
        Schema::dropIfExists('hardware_statuses');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('users');
    }
};
