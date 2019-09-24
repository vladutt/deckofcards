<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{

    protected $hidden = ['id', 'decks'];

    public function cards() {
        return $this->hasOne(Card::class);
    }
}
