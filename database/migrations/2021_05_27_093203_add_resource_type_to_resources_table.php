<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResourceTypeToResourcesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->after('status_id');
            $table->foreign('type_id')->references('id')->on('resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign('resources_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
}
