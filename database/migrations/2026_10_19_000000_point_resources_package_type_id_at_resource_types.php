<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 2021_07_20_075547 pointed type_id at resources.id instead of the type lookup table, so creating
 * a package failed whenever no resource row had the type's id. The MySQL key still has the name
 * from before the table was renamed; SQLite (tests) names keys by column.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('resources_package', function (Blueprint $table) {
            $table->dropForeign($this->oldForeignKeyName() ?? ['type_id']);
            $table->foreign('type_id')->references('id')->on('resource_types_lkp');
        });
    }

    public function down(): void {
        Schema::table('resources_package', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->foreign('type_id', $this->oldForeignKeyName())->references('id')->on('resources');
        });
    }

    private function oldForeignKeyName(): ?string {
        return DB::getDriverName() === 'mysql' ? 'communication_resources_pack_type_id_foreign' : null;
    }
};
