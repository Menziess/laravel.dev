<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Config;
use Carbon\Carbon;
use Auth;
use App\User;
use App\Resource;
use App\Http\Requests;

class SocialController extends Controller
{
	const SCOPES = ['public_profile', 'email', 'user_birthday', 'user_location', 'user_hometown'];
	const FIELDS = ['first_name', 'last_name', 'email', 'location', 'birthday', 'gender', 'updated_time'];

	/**
	 * Facebook login.
	 *
	 * @return view.
	 */
	public function getFbredirect()
	{
		$scopes = self::SCOPES;
		return \Socialite::driver('facebook')->scopes($scopes)->redirect();
	}

	/**
	 * Facebook callback.
	 *
	 * @return void
	 */
	public function getFbcallback()
	{
		$fb = \Socialite::driver('facebook')->fields(self::FIELDS)->user();

		# Get user or create new user
		$newUser = false;
		$user = User::withTrashed()->where('facebook_id', $fb->id)->first();
		if (!$user) {
			$user = User::firstOrNew(['facebook_id' => $fb->id]);
			$newUser = true;
			$user->save();
		}

		if (!$newUser) {
			# Check if user is active or restore when trashed
			if ($user->is_active === false) {
				abort(403, User::USER_IS_BLOCKED_BY_ADMIN);
			} elseif ($user->trashed()) {
				$user->restore();
			}
		}

		# See if user needs to be updated
		// if ($fb->user['updated_time'] > $user->updated_at) {
			self::fillUser($user, $fb);
		// }

		# See if a user exists with this email
		$user = self::checkExistingUser($user, $fb->user['email']);

		Auth::login($user);

		return redirect()->back();
	}

	/**
	 * Fills user attributes from fb socialite instance.
	 *
	 * @return void
	 */
	private static function fillUser($user, $fb)
	{

		$user->first_name = isset($fb->user['first_name']) ? $fb->user['first_name'] : null;
		$user->last_name = isset($fb->user['last_name']) ? $fb->user['last_name'] : null;
		$user->email = isset($fb->user['email']) ? $fb->user['email'] : null;

		$user->profile->gender = isset($fb->user['gender']) ? $fb->user['gender'] : null;

		# Birthday
		if (isset($fb->user['birthday'])) {
			$user->profile->date_of_birth =
				Carbon::parse($fb->user['birthday'])->toDateTimeString();
		};

		# Avatar
		if (isset($fb->avatar_original) || isset($fb->avatar)) {
			self::uploadAvatar($user, $fb);
		}

		# Location
		if (isset($fb->user['location'])) {
			self::setLocation($user, $fb);
		}

		# Save profile for now
		$user->profile->save();
		$user->save();
	}

	/**
	 * Checks if user has avatar, then uploads new.
	 *
	 * @return void
	 */
	private static function uploadAvatar($user, $fb)
	{
		// if avatar_original else avatar
		// upload image to storage
		// set profile resource id
		// $user->profile->resource->save();
	}

	/**
	 * Sets users latitude and longitude.
	 *
	 * @return  void
	 */
	private static function setLocation($user, $fb)
	{
		$url = 'https://maps.googleapis.com/maps/api/geocode/json'
			. '?address=' . urlencode($fb->user['location']['name'])
			. '&key=' . Config::get('services.google.key');
		$response = json_decode(file_get_contents($url),true);
		if (isset($response['results'][0]['geometry']['location'])) {
			$user->profile->latitude = $response['results'][0]['geometry']['location']['lat'];
			$user->profile->longitude = $response['results'][0]['geometry']['location']['lng'];
		}
	}

	/**
	 * Email exists in database.
	 *
	 * @return App\User
	 */
	private static function checkExistingUser($user, $email)
	{
		$existing = User::where('email', $email)->whereNull('facebook_id')->first();

		if ($existing) {
			// $existing merge with $user
			dd($existing);
			$user->save();
		};

		return $user;
	}
}
