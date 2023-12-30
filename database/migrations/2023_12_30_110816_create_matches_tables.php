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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('player_user_id')->default(0);
            $table->integer('opponent_user_id')->default(0);
            $table->integer('score_goal')->default(0);
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->nullable();
            $table->integer('is_finished')->default(0);
            $table->timestamps();
        });

        Schema::create('game_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id')->default(0);
            $table->integer('player_id')->default(0);
            $table->integer('points')->default(0);
            $table->integer('foul_points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('game_scores');
    }
};
