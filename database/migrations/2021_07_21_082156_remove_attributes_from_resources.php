<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAttributesFromResources extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['lang_id']);
            $table->dropColumn('lang_id');
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }

    /**
     * Reverse the migrations.

     *
     * @return void
     */
    public function down() {
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('lang_id')->nullable();
            $table->foreign('lang_id')->references('id')->on('content_languages_lkp');
            $table->unsignedBigInteger('creator_user_id');
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->foreign('admin_user_id')->references('id')->on('users');
        });
    }
}
