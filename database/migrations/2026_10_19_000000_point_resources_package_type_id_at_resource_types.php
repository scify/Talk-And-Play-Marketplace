<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 2021_07_20_075547 pointed type_id at resources.id instead of the type lookup table, so creating
 * a package failed whenever no resource row had the type's id. MySQL cannot roll back DDL, so the
 * data is checked before any change, and the existing key is found by its column, not by a name
 * (the MySQL key still has the name from before the table was renamed).
 */
return new class extends Migration {
    public function up(): void {
        $unknownTypes = DB::table('resources_package')
            ->whereNotNull('type_id')
            ->whereNotIn('type_id', DB::table('resource_types_lkp')->select('id'))
            ->distinct()
            ->pluck('type_id');
        if ($unknownTypes->isNotEmpty()) {
            throw new RuntimeException('Packages with an unknown type_id: ' . $unknownTypes->implode(', ')
                . '. Fix these rows, then run the migration again.');
        }

        $this->replaceTypeForeignKey('resource_types_lkp');
    }

    public function down(): void {
        $this->replaceTypeForeignKey('resources', 'communication_resources_pack_type_id_foreign');
    }

    private function replaceTypeForeignKey(string $table, ?string $name = null): void {
        $current = collect(Schema::getForeignKeys('resources_package'))
            ->first(fn (array $key) => $key['columns'] === ['type_id']);
        if ($current !== null && $current['foreign_table'] === $table) {
            return;
        }

        Schema::table('resources_package', function (Blueprint $blueprint) use ($current, $table, $name) {
            if ($current !== null) {
                $blueprint->dropForeign($current['name'] ?: ['type_id']);
            }
            $blueprint->foreign('type_id', $name)->references('id')->on($table);
        });
    }
};
