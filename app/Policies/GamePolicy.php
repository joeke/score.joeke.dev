<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Game $game): bool
    {
        return $user->id === $game->player_id || $user->id === $game->opponent_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Game $game): bool
    {
        return $user->id === $game->player_id || $user->id === $game->opponent_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Game $game): bool
    {
        return $user->id === $game->player_id || $user->id === $game->opponent_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Game $game): bool
    {
        return $user->id === $game->player_id || $user->id === $game->opponent_id;
    }
}
