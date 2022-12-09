<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersionToAnnouncementsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->double('min_version')->default(0.0)->after('status');
            $table->double('max_version')->default(0.0)->after('min_version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->dropColumn('min_version');
            $table->dropColumn('max_version');
        });
    }
}
