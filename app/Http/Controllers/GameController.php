<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Game;
use App\User;

class GameController extends Controller
{
	/*
	 * Gets game page.
	 */
    public function getIndex() {

    	$game = Game::active()->orderBy('id', 'desc')->first();

    	if (!$game) {
    		 $game = new Game();
    		 $game->save();
    	}

    	return $this->getShow($game->id);
    }

    /*
	 * Gets game by id.
	 */
    public function getShow($id) {
    	$users = User::all();
		$subject = Auth::user();
		$game = Game::findOrFail($id);

    	return view('content.game.board', compact('game', 'subject', 'users'));
    }

    /*
     * Start game.
     */
    public function putStartGame() {
    	$game = Game::active()->orderBy('id', 'desc')->first();
    	$game->start();

    	return redirect('/game');
    }

    /*
     * Delete game.
     */
    public function deleteDeleteGame() {
    	$game = Game::active()->orderBy('id', 'desc')->first();
    	$game->delete();

    	return redirect('/game');
    }

    /*
	 * Add user by id.
	 */
	public function putAddUser($id)
	{
		$user = User::withTrashed()->findOrFail($id);
		$game = Game::active()->orderBy('id', 'desc')->first();

		$game->addPlayer($user);

		return redirect()->to(\URL::previous() . '#players');
	}

	/*
	 * Remove user by id.
	 */
	public function putRemoveUser($id)
	{
		$user = User::withTrashed()->findOrFail($id);
		$game = Game::active()->orderBy('id', 'desc')->first();

		$game->removePlayer($user);

		return redirect()->to(\URL::previous() . '#players');
	}
}
