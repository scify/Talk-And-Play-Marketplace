<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesktopAppAnnouncementTranslationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('desktop_app_announcement_translations', function (Blueprint $table) {
            $table->unsignedBigInteger('announcement_id');
            $table->unsignedBigInteger('lang_id');
            $table->foreign('announcement_id')->references('id')->on('desktop_app_announcements');
            $table->foreign('lang_id')->references('id')->on('content_languages_lkp');
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
            $table->primary(['announcement_id', 'lang_id'], 'desktop_app_ann_trans_ann_id_lang_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('desktop_app_announcement_translations');
    }
}
