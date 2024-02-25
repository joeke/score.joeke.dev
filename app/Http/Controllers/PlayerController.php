<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PlayerController extends Controller
{
    public function overview(): Response
    {
        $players = User::where('created_by', auth()->user()->id)
            ->orWhere(function ($query) {
                $query->whereNotNull(['email', 'password']);
            })
            ->get();

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

        return back()->with('success', 'Player successfully updated.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $user = User::where('id', $request->id)->first();

        $this->authorize('delete', $user);

        // TODO: check if user has games linked
        // return back()->with('error', 'Error! This player cannot be deleted, because there are games linked to this player. Please delete those games first.');

        $user->delete();
    }
}
