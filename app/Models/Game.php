<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GameScore;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_id',
        'opponent_id',
        'score_goal',
        'start_date',
        'end_date',
        'is_finished',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'start_date_formatted',
        'end_date_formatted',
        'player_name',
        'opponent_name'
    ];

    /**
     * Get the scores/innings for this game.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(GameScore::class);
    }

    /**
     * @return mixed
     */
    public function getStartDateFormattedAttribute() {
        $date = $this->start_date;

        if ($date) {
            return $date->format('Y-m-d');
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getEndDateFormattedAttribute() {
        $date = $this->end_date;

        if ($date) {
            return $date->format('Y-m-d');
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getPlayerNameAttribute() {
        $player = User::where('id', $this->player_id)->first();

        if ($player) {
            return $player->name;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getOpponentNameAttribute() {
        $opponent = User::where('id', $this->opponent_id)->first();

        if ($opponent) {
            return $opponent->name;
        }

        return null;
    }
}
