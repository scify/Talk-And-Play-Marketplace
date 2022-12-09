<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultTitleToAnnouncementsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->string('default_title')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->dropColumn('default_title');
        });
    }
}
