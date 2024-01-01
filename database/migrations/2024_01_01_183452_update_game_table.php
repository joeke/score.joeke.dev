<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->default(0)->after('id');

            $table->foreign('type_id')->references('id')->on('game_types');
        });

        Schema::table('game_scores', function (Blueprint $table) {
            $table->integer('balls_left')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
