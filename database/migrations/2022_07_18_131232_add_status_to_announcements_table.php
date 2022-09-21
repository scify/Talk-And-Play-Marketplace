<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('desktop_app_announcements', function (Blueprint $table) {
            $table->boolean('status')->default(false)->after('default_title');
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
            $table->dropColumn('status');
        });
    }
}
