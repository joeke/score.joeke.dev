<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
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
        'end_date_formatted'
    ];

    /**
     * Get the scores/innings for this game.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(GameScore::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opponent_id')->withDefault();
    }

    public function type(): HasOne
    {
        return $this->hasOne(GameType::class, 'id', 'type_id')->withDefault();
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
}
