<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('en_title');
            $table->string('en_slug')->unique();
            $table->text('en_description');

            $table->string('vi_title')->nullable();
            $table->string('vi_slug')->nullable();
            $table->text('vi_description')->nullable();

            $table->string('background_image');

            $table->json('categories');
            $table->uuid('author_id');
            $table->datetime('event_date');
            $table->string('qr_code')->nullable();
            $table->json('participants')->nullable();

            $table->boolean('promotion')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->index(['is_completed']);

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
        Schema::dropIfExists('events');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
