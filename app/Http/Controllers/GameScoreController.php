<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameScore;

class GameScoreController extends Controller
{
    /**
     * Add an inning/score to the game.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game' => 'required',
            'points' => 'required',
            'player_id' => 'required',
            'balls_remaining' => 'required'
        ]);

        Game::where('id', $request->game)->update([
            'balls_left' => $request->balls_remaining
        ]);

        GameScore::create([
            'game_id' => $request->game,
            'player_id' => $request->player_id,
            'points' => $request->points,
            'foul_points' => $request->foul_points ?? 0
        ]);
    }

     /**
     * Delete a game score.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $gamescore = GameScore::where('id', $request->id)->with('game')->first();

        if (!$request->user()->hasAccessToGame($gamescore->game)) {
            abort(403);
        }

        // TODO: Update balls left on game
        // $gamescore->game()->update([
        //     'balls_left' => $gamescore->game->balls_left + $gamescore->points
        // ]);

        $gamescore->delete();
    }
}
