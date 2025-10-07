<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'components',
            'consumables',
            'depreciations',
            'hardware',
            'licences',
            'maintenances',
            'people',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'qr_code')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('qr_code')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'components',
            'consumables',
            'depreciations',
            'hardware',
            'licences',
            'maintenances',
            'people',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'qr_code')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('qr_code');
                });
            }
        }
    }
};
