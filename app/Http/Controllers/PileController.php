<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Pile;
use Illuminate\Http\Request;

class PileController extends Controller
{
    private $cards = [
        'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '0S', 'JS', 'QS', 'KS',
        'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '0D', 'JD', 'QD', 'KD',
        'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '0C', 'JC', 'QC', 'KC',
        'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '0H', 'JH', 'QH', 'KH'
    ];

    public function createPile($deck, $pile, $cards) {

        $deckFounded = $this->checkDeck($deck);

        if (!$deckFounded) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet...'], 404);
        }

        $piles = $deckFounded->piles !== null ? $deckFounded->piles->piles : null;
        $deckCards = json_decode($deckFounded->cards->cards, true);

        if (!$this->checkCards($cards, $deckCards)) {
            return response()->json(['status' => 'error', 'message' => 'Valoarea cartilor este incorecta sau sunt deja utilizate...'], 400);
        }

        $explodedCards = explode(',', $cards);

        $newPiles = [$pile => $explodedCards];

        if ($piles === null) {

            Pile::create([
                'deck_id' => $deckFounded->id,
                'piles' => json_encode($newPiles)
            ]);


        } else {

            $piles = json_decode($piles, true);

            if (isset($piles[$pile])) {
                return response()->json(['status' => 'error', 'message' => 'Numele acestui pachet este utilizat deja'], 400);
            }

            $newPiles = array_merge($newPiles, $piles);

            $deckFounded->piles->update([
                'deck_id' => $deckFounded->id,
                'piles' => json_encode($newPiles)
            ]);

        }

        $remainingCards = array_diff($deckCards, $explodedCards);

        $deckFounded->update([
            'remaining' => count($remainingCards)
        ]);

        $deckFounded->cards()->update([
            'cards' => json_encode($remainingCards)
        ]);

        $data = [
            'status' => 'success',
            'deck_id' => $deck,
            'remaining' => $deckFounded->remaining,
            'piles' => $newPiles
        ];

        return response()->json($data, 200);


    }

    public function listPiles($deck) {

        $deckFounded = $this->checkDeck($deck);

        if (!$deckFounded) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet...'], 404);
        }

        $data = [
            'status' => 'success',
            'deck_id' => $deckFounded->deck_id,
            'remaining' => $deckFounded->remaining,
            'piles' => array_keys(json_decode($deckFounded->piles->piles, true))
        ];

        return response()->json($data, 200);

    }

    private function checkCards($cards, $deckCards) {

        if (strpos($cards, ',')) {

            $cards = explode(',', $cards);

            foreach ($cards as $key => $card) {
                if(!in_array($card, $deckCards)) {
                    $error = true;
                    break;
                }
            }

            if (isset($error)) {
                return false;
            }

        } else {
            return false;
        }

        return true;

    }

    private function checkDeck($deck_id) {

        $deckFounded = Deck::where('deck_id', $deck_id)->first();

        return $deckFounded === null ? false : $deckFounded;

    }

}
