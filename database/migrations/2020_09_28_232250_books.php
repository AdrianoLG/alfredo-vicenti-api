<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Books extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 50);
            $table->string('author', 50);
            $table->string('category', 30);
            $table->text('image')->nullable();
            $table->smallInteger('pages')->nullable();
            $table->date('read_date', 50)->nullable();
            $table->smallInteger('rating')->nullable();
            $table->text('comments')->nullable();
            $table->string('editorial', 30)->nullable();
            $table->text('synopsis')->nullable();
            $table->text('lent_to', 30)->nullable();
            $table->date('lent_date')->nullable();
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
        //
    }
}
