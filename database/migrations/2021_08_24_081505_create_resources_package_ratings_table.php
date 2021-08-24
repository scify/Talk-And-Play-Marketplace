<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesPackageRatingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('resources_package_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resources_package_id');
            $table->foreign('resources_package_id')->references('id')->on('resources_package');
            $table->unsignedBigInteger('voter_user_id');
            $table->foreign('voter_user_id')->references('id')->on('users');
            $table->integer('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('resources_package_ratings');
    }
}
