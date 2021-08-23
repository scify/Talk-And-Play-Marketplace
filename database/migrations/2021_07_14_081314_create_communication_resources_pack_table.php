<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunicationResourcesPackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communication_resources_pack', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->references('id')->on('resources');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('resource_statuses_lkp');
            $table->unsignedBigInteger('lang_id')->nullable();
            $table->foreign('lang_id')->references('id')->on('content_languages_lkp');
            $table->unsignedBigInteger('creator_user_id');
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->foreign('admin_user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communication_resources_pack');
    }
}
