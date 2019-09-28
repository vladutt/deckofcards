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

Route::get('decks/{deck_id}/draw/{cards}', 'DeckController@drawCards')->where('cards', '[0-9]+')->name('deck.draw_cards');
Route::get('decks/{deck_id}/shuffle', 'DeckController@deckShuffle')->name('deck.deck_shuffle');
Route::get('decks/partial/{cards}', 'DeckController@partialDeck')->name('deck.partial_deck');
Route::get('decks/{deck_id}/piles/{pile_name}/add/{cards}', 'PileController@createPile')->name('pile.create_pile');
Route::get('decks/{deck_id}/piles/list', 'PileController@listPiles')->name('pile.list_piles');
Route::get('decks/{deck_id}/piles/{pile_name}', 'PileController@showPile')->name('pile.show_pile');
Route::get('decks/{deck_id}/piles/{pile_name}/draw/{cards}', 'PileController@drawCards')->name('pile.draw_cards');
Route::get('decks/{decks}', 'DeckController@newDeck')->where('decks', '[^.]*')->name('deck.new_deck');
