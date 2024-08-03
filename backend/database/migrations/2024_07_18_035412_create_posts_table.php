<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
			$table->id('post_id');
			$table->unsignedBigInteger('user_id'); // Foreign key referencing users table
			$table->string('title');
			$table->text('content');
			$table->string('status');
			$table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
