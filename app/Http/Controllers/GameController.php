<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    /**
     * Display the new game form.
     */
    public function new(Request $request): Response
    {
        return Inertia::render('Game/New');
    }
}
