<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class PlayerController extends Controller
{
    public function overview(): Response
    {
        $players = User::where('created_by', auth()->user()->id)->orWhere('id', auth()->user()->id)->get();

        return Inertia::render('Players', [
            'players' => $players
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'created_by' => auth()->user()->id
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);

        $player = User::where('id', $request->id)->first();

        $player->name = $request->name;
        $player->save();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $user = User::where('id', $request->id)->first();

        if ($user->id === auth()->user()->id) {
            abort(403);
        }

        $user->delete();
    }
}
