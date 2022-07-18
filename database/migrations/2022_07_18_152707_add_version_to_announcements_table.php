<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersionToAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->double('version')->default(0.0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
}
