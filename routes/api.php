<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('decks/{decks}', 'DeckController@newDeck')->name('deck.new_deck');
Route::get('decks/{deck}/draw/{cards}', 'DeckController@drawCard')->where('cards', '[0-9]+')->name('deck.draw_card');
Route::get('decks/{deck}/shuffle', 'DeckController@deckShuffle')->name('deck.deck_shuffle');
Route::get('decks/partial/{cards}', 'DeckController@partialDeck')->name('deck.partial_deck');