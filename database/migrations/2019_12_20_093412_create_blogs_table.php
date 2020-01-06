<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('en_title', 255)->unique();
            $table->string('en_slug', 255)->unique();
            $table->longText('en_description');

            $table->string('vi_title', 255)->unique()->nullable();
            $table->string('vi_slug', 255)->unique()->nullable();
            $table->longText('vi_description')->nullable();

            $table->string('background_image');
            

            $table->integer('visits')->default(0);
            $table->json('categories');
            $table->uuid('author_id');

            $table->timestamps();

            // $table->index(['categories','visits']);

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('blogs');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
