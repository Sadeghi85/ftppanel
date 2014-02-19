<?php

class AuthController extends BaseController {

	/**
	 * Account login.
	 *
	 * @return View
	 */
	public function getLogin()
	{
		// Is the user logged in?
		if (Sentry::check())
		{
			return Redirect::to('');
		}

		// Show the page
		return View::make('auth.login');
	}

	/**
	 * Account login form processing.
	 *
	 * @return Redirect
	 */
	public function postLogin()
	{
		// Declare the rules for the form validation
		$rules = array(
			'username'    => 'required',
			'password' => 'required|between:3,32',
		);

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password'))->withErrors($validator);
		}

		try
		{
			// Try to log the user in
			Sentry::authenticate(Input::only('username', 'password'), Input::get('remember-me', 0));
			
			// Get the page we were before
			$redirect = Session::get('loginRedirect', '');

			// Unset the page we were before from the session
			Session::forget('loginRedirect');

			// Redirect to the home page
			return Redirect::to($redirect);
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    //$message = 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    //$message = 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
		    //$message = 'Wrong password, try again.';
			$this->messageBag->add('password', Lang::get('auth/messages.password_is_wrong'));
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    //$message = 'User was not found.';
			$this->messageBag->add('username', Lang::get('auth/messages.user_not_found'));
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
		    //$message = 'User is not activated.';
			$this->messageBag->add('username', Lang::get('auth/messages.user_not_activated'));
		}
		// The following is only required if throttle is enabled
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    //$message = 'User is suspended.';
			$this->messageBag->add('username', Lang::get('auth/messages.user_is_suspended'));
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
		    //$message = 'User is banned.';
			$this->messageBag->add('username', Lang::get('auth/messages.user_is_banned'));
		}

		// Ooops.. something went wrong
		return Redirect::back()->withInput(Input::except('password'))->withErrors($this->messageBag);
	}

	/**
	 * Logout page.
	 *
	 * @return Redirect
	 */
	public function getLogout()
	{
		// Log the user out
		Sentry::logout();

		return Redirect::to('');
	}
}