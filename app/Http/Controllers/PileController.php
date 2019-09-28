<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Pile;
use Illuminate\Support\Arr;

class PileController extends Controller
{
    private $cards = [
        'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '0S', 'JS', 'QS', 'KS',
        'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '0D', 'JD', 'QD', 'KD',
        'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '0C', 'JC', 'QC', 'KC',
        'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '0H', 'JH', 'QH', 'KH'
    ];
    private $suits = ['S' => 'SPADES', 'D' => 'DIAMONDS', 'C' => 'CLUBS', 'H' => 'HEARTS'];
    private $values = ['A' => 'ACE', 'J' => 'JACK', 'Q' => 'QUEEN', 'K' => 'KING', '0' => '10'];


    /**
     *  Create a pile with the specified cards
     *
     * @param string $deck -> deck_id
     * @param string $pile -> pile_name
     * @param string $cards
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function createPile ($deck, $pile, $cards) {

        $deckFounded = $this->checkDeck($deck);

        if (!$deckFounded) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet...'], 404);
        }

        $piles = $deckFounded->piles !== null ? $deckFounded->piles->piles : null;
        $deckCards = json_decode($deckFounded->cards->cards, true);
        $explodedCards = explode(',', $cards);

        if (count($deckCards) < count($explodedCards) ) {
            return response()->json(['status' => 'error', 'message' => 'Pachetul conține mai puține cărți decât ai specificat...'], 400);
        }


        if (!$this->checkCards($cards, $deckCards)) {
            return response()->json(['status' => 'error', 'message' => 'Valorile cartilor este incorecta sau sunt deja utilizate...'], 400);
        }

        $newPile = [$pile => $explodedCards];
        $currentPile = $newPile;

        if ($piles === null) {

            Pile::create([
                'deck_id' => $deckFounded->id,
                'piles' => json_encode($newPile)
            ]);


        } else {

            $piles = json_decode($piles, true);

            if (isset($piles[$pile])) {
                return response()->json(['status' => 'error', 'message' => 'Numele acestui pachet este utilizat deja'], 400);
            }
            $newPiles = array_merge($newPile, $piles);

            $deckFounded->piles->update([
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
            'piles' => $currentPile
        ];

        return response()->json($data, 200);


    }

    /**
     * Shown the piles
     *
     * @param string $deck -> deck_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPiles ($deck) {

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

    /**
     * Shown a specific pile and the cards
     *
     * @param string $deck -> deck_id
     * @param string $pile -> pile_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPile ($deck, $pile) {

        $deckFounded = $this->checkDeck($deck);

        if (!$deckFounded) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet...'], 404);
        }

        $cards = @json_decode($deckFounded->piles->piles, true)[$pile];

        if (!isset($cards)) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest teanc de cărți...'], 404);
        }

        foreach($cards as $key => &$card) {

            $card = [
                'image' => asset('/images/cards/'.$card.'.png'),
                'value' => (is_numeric($card[0]) && $card[0] !== "0") ? $card[0] : $this->values[$card[0]],
                'suits' => $this->suits[$card[1]],
                'code' => $card
            ];
        }
        $data
            = [
            'status' => 'success',
            'deck_id' => $deckFounded->deck_id,
            'remaining' => $deckFounded->remaining,
            'pile' => $pile,
            'cards' => $cards
        ];

        return response()->json($data, 200);
    }

    /**
     * Draw cards from a pile
     *
     * @param string $deck
     * @param string $pile
     * @param string $cards
     */
    public function drawCards ($deck, $pile, $numberCards) {

        $deckFounded = $this->checkDeck($deck);

        if (!$deckFounded) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest pachet...'], 404);
        }

        $piles = json_decode($deckFounded->piles->piles, true);
        $cards = $piles[$pile];

        if (!isset($cards)) {
            return response()->json(['status' => 'error', 'message' => 'Nu am gasit acest teanc de cărți...'], 404);
        }

        $countCards = count($cards);

        if ($countCards === 0) {
            return response()->json(['status' => 'error', 'message' => 'Nu mai sunt cărți...'], 404);
        }

        if($countCards < $numberCards) {
            $numberCards = $countCards;
        }

        $selectedCards = Arr::random($cards, $numberCards);
        $remainingCards = array_diff($cards,$selectedCards);
        $piles[$pile] = $remainingCards;

        $deckFounded->piles()->update([
            'piles' => json_encode($piles)
        ]);

        $formatCards = [];
        foreach ($selectedCards as $key => $card) {

            $formatCards[] = [
                'image' => asset('/images/cards/'.$card.'.png'),
                'value' => (is_numeric($card[0]) && $card[0] !== "0") ? $card[0] : $this->values[$card[0]],
                'suits' => $this->suits[$card[1]],
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

    /* Private functions */

    /**
     * Check if the cards are valid
     *
     * @param string $cards
     * @param array $deckCards
     * @return bool
     */
    private function checkCards ($cards, $deckCards) {

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

    /**
     * Check if the deck exists
     *
     * @param $deck
     * @return bool
     */
    private function checkDeck ($deck) {

        $deckFounded = Deck::where('deck_id', $deck)->first();

        return $deckFounded === null ? false : $deckFounded;

    }

}
