<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    public $fillable = ['cards'];

    public function deck() {
        return $this->belongsTo(Deck::class);
    }
}
