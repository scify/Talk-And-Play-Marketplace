<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadableBooleanFieldToResourcesPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resources_package', function (Blueprint $table) {
            $table->boolean('downloadable')->default(false)->after('lang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resources_package', function (Blueprint $table) {
            $table->dropColumn('downloadable');
        });
    }
}
