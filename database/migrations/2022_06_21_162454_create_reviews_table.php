<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->unsignedBigInteger('reviewee_id');
            $table->index('reviewee_id');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('reviewer_id');
            $table->index('reviewer_id');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
