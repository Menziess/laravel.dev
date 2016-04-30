<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Http\Requests;

class AdminController extends Controller
{
    public function getIndex(Request $request)
    {
    	if (\Auth::guest() || !\Auth::user()->is_admin) {
    		return redirect()->action('HomeController@getIndex');
    	}

    	$users = User::withTrashed()->orderBy('id', 'desc')->paginate(15);
    	return view('auth.admin.users', compact('users'));
    }

    public function getShow($id)
	{
		if ($id && Auth::user()->is_admin) {
			$user = User::withTrashed()->find($id);
		} else {
			return redirect('home');
		}
		return view('auth.admin.profile', compact('user'));
	}

    /*
     * Restores a user and sets is_active on true.
     */
    public function getActivate($id)
    {
        if (Auth::user()->is_admin) {
            User::withTrashed()->find($id)->activate();
        }
        return redirect()->back();
    }

    /*
     * Soft deletes user and sets is_active on false.
     */
    public function getDeactivate($id)
    {
        if (Auth::user()->is_admin) {
            User::withTrashed()->find($id)->deactivate();
        }
        return redirect()->back();
    }
}
