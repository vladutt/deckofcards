<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DeckController extends Controller
{

    public function newDeck($number) {

        $deck = new Deck();
        $deck->deck_id = Str::random(15);
        $deck->shuffled = true;
        $deck->remaining= 52 * $number;
        $deck->decks = $number;
        $deck->save();

        $deck->cards()->create([
            'cards' => json_encode($this->generateCards($number))
        ]);

        $data = [
            'status' => 'success',
            'deck_id' => $deck->deck_id,
            'shuffled' => true,
            'remaining' => $deck->remaining
        ];

        return $this->response($data);
    }

    public function drawCard($deck, $numberCards) {

        $deckFounded = Deck::where('deck_id', $deck)->first();

        if($deckFounded === null) {
            return $this->response(['status' => 'error', 'message' => 'Nu am gasit acest pachet.']);
        }

        $cards = json_decode($deckFounded->cards->cards, true);
        $countCards = count($cards);

        if ($countCards === 0) {
            return $this->response(['status' => 'error', 'message' => 'Nu mai sunt cărți...']);
        }

        if($countCards < $numberCards) {
            $numberCards = $countCards;
        }

        $selectedCards = Arr::random($cards, $numberCards);

        $remainingCards = array_diff($cards,$selectedCards);

        $deckFounded->cards()->update([
            'cards' => json_encode($remainingCards)
        ]);

        $suits = ['S' => 'SPADES', 'D' => 'DIAMONDS', 'C' => 'CLUBS', 'H' => 'HEARTS'];
        $values = ['A' => 'ACE', 'J' => 'JACK', 'Q' => 'QUEEN', 'K' => 'KING', '0' => '10'];

        $formatCards = [];
        foreach ($selectedCards as $key => $card) {
            $cardValue = (int)$card[0];
            $formatCards[] = [
                'image' => url('/') . '/storage/img/'.$card.'.png',
                'value' => is_int($cardValue) ? $card[0] : $values[$card[0]],
                'suits' => $suits[$card[1]],
                'code' => $card
            ];
        }

        $data = [
            'status' => 'success',
            'deck_id' => $deckFounded->deck_id,
            'remaining' => count($remainingCards),
            'cards' => $formatCards,
        ];


        return $this->response($data);

    }

    public function deckShuffle($id) {

        $deckFounded = Deck::where('deck_id', $id)->first();

        if($deckFounded === null) {
            return $this->response(['status' => 'error', 'message' => 'Nu am gasit acest pachet.']);
        }
        $decks = $deckFounded->decks;
        $deckFounded->remaining = 52 * $decks;
        $deckFounded->increment('times_shuffled');
        $deckFounded->cards()->update([
           'cards' =>  json_encode($this->generateCards($decks))
        ]);
        $deckFounded->save();

        $data = [
            'status' => 'success',
            'deck_id' => $deckFounded->deck_id,
            'shuffled' => true,
            'remaining' => $deckFounded->remaining
        ];

        return $this->response($data);

    }

    private function generateCards($number) {

        $cards = ['AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '0S', 'JS', 'QS', 'KS',
                'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '0D', 'JD', 'QD', 'KD',
                'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '0C', 'JC', 'QC', 'KC',
                'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '0H', 'JH', 'QH', 'KH'];

        $newCards = [];
        $x = 0;

        for ($x; $x < $number; $x++) {

            $newCards = array_merge($newCards, $cards);

        }

        return $newCards;

    }

    private function response(array $data) {
        return response()->json($data);
    }
}
