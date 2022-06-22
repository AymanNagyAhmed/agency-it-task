<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatefeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->unsignedBigInteger('review_id');
            $table->index('review_id');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');

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
        Schema::dropIfExists('feedbacks');
    }
}
