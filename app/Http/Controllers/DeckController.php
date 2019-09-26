<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DeckController extends Controller
{

    private $cards = [
        'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '0S', 'JS', 'QS', 'KS',
        'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '0D', 'JD', 'QD', 'KD',
        'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '0C', 'JC', 'QC', 'KC',
        'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '0H', 'JH', 'QH', 'KH'
    ];

    public function newDeck($decks) {

        $deck = $this->createDecks($decks);

        $data = [
            'status' => 'success',
            'deck_id' => $deck->deck_id,
            'shuffled' => true,
            'remaining' => $deck->remaining
        ];

        return response()->json($data, 201);
    }

    private function createDecks($decks) {

        $brand = 'false';
        if (!is_int($decks) && $decks === 'new') {
            $decks = 1;
            $brand = 'true';
        }

        $deck = new Deck();
        $deck->deck_id = Str::random(15);
        $deck->shuffled = true;
        $deck->remaining= 52 * $decks;
        $deck->decks = $decks;
        $deck->brand_deck = $brand;
        $deck->save();

        $deck->cards()->create([
            'cards' => json_encode($this->generateCards($decks))
        ]);

        return $deck;
    }

    public function drawCard($deck, $numberCards) {


        $deckFounded = ($deck === 'new') ? $this->createDecks(1) : Deck::where('deck_id', $deck)->first();

        if($deckFounded === null) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet.'], 404);
        }

        $cards = array_values(json_decode($deckFounded->cards->cards, true));
        $countCards = count($cards);

        if ($countCards === 0) {
            return response()->json(['status' => 'error', 'message' => 'Nu mai sunt cărți...'], 404);
        }

        if($countCards < $numberCards) {
            $numberCards = $countCards;
        }

        if ($deckFounded->brand_deck === 'true') {

            $numberKey = $numberCards-1;
            foreach ($cards as $key => $card) {
                $selectedCards[] = $card;
                if ($key === $numberKey){
                    break;
                }
            }

        } else {
            $selectedCards = Arr::random($cards, $numberCards);
        }


        $remainingCards = array_diff($cards,$selectedCards);

        $deckFounded->cards()->update([
            'cards' => json_encode($remainingCards)
        ]);

        $suits = ['S' => 'SPADES', 'D' => 'DIAMONDS', 'C' => 'CLUBS', 'H' => 'HEARTS'];
        $values = ['A' => 'ACE', 'J' => 'JACK', 'Q' => 'QUEEN', 'K' => 'KING', '0' => '10'];

        $formatCards = [];
        foreach ($selectedCards as $key => $card) {

            $formatCards[] = [
                'image' => url('/storage/img/'.$card.'.png'),
                'value' => (is_numeric($card[0]) && $card[0] !== "0") ? $card[0] : $values[$card[0]],
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


        return response()->json($data, 200);

    }

    public function deckShuffle($id) {

        $deckFounded = Deck::where('deck_id', $id)->first();

        if($deckFounded === null) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet.'], 404);
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

        return response()->json($data);

    }

    private function generateCards($number) {

        $newCards = [];
        $x = 0;

        for ($x; $x < $number; $x++) {

            $newCards = array_merge($newCards, $this->cards);

        }

        return $newCards;

    }

    public function partialDeck($cards) {

        if (strpos($cards, ',')) {

            $cards = explode(',', $cards);

            foreach ($cards as $key => $card) {
                if(!in_array($card, $this->cards)) {
                    $error = true;
                    break;
                }
            }

            if (isset($error)) {
                return response()->json(['status' => 'error', 'message' => 'Ai introdus o valoare gresita...'], 400);
            }

            $countCards = count($cards);

            $deck = new Deck();
            $deck->deck_id = Str::random(15);
            $deck->shuffled = true;
            $deck->remaining = $countCards;
            $deck->decks = 1;
            $deck->brand_deck = 'false';
            $deck->save();

            $deck->cards()->create([
                'cards' => json_encode($cards)
            ]);

            $data = [
                'status' => 'success',
                'deck_id' => $deck->deck_id,
                'shuffled' => true,
                'remaining' => $deck->remaining
            ];

            return response()->json($data);

        } else {
            return response()->json(['status' => 'error', 'message' => 'Ceva nu a mers bine...'], 400);
        }

    }
}
