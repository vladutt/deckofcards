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

Route::get('decks/{count}', 'DeckController@newDeck')->where('count', '[0-9]+')->name('deck.new_deck');
Route::get('deck/{deck}/draw/{cards}', 'DeckController@drawCard')->where('cards', '[0-9]+')->name('deck.draw_card');
Route::get('deck/{deck}/shuffle', 'DeckController@deckShuffle')->name('deck.shuffle');