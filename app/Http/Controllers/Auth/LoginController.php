<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $loginPath = '/login';

    protected $maxLoginAttempts = 6; // Amount of bad attempts user can make
    protected $lockoutTime = 3600; // Time for which user is going to be blocked in seconds

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest')->except('logout');
    }

    protected function getThrottleKey(Request $request)
    {
        return $request->input('email');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $this->addSuccessLogin($this->auth->user());
            return $this->sendLoginResponse($request);
        } else {
            $this->addFailLogin($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
        $email = $this->checkUserBeforeLogin($request);
        $creds = [
            'email' => $email,
            'password' => $request->input('password')
        ];

        return $this->guard()->attempt(
            $creds, $request->filled('remember')
        );
    }

    private function checkUserBeforeLogin($request)
    {
        $user_name = $request->input('email');
        $query = User::where('id', '>', 0);
        $query->where(function ($inner) use ($user_name){
            $inner->orWhere('user_name', $user_name)->orWhere('email', $user_name);
        });
        $user = $query->first();
        if($user) {
            return $user->email;
        }
        return '';
    }

    private function addSuccessLogin($user)
    {
        if(!isset($_SERVER['REMOTE_ADDR']))
            return false;
        $userLogin = $user->login()->firstOrCreate(['ip' => $_SERVER['REMOTE_ADDR']]);
        $userLogin->success = $userLogin->success + 1;
        $userLogin->save();
    }

    private function addFailLogin(Request $request)
    {
        $email = $this->checkUserBeforeLogin($request);
        $userExist = User::where('email', '=', $email)->first();
        if($userExist and isset($_SERVER['REMOTE_ADDR'])) {
            // add history in case of fail login attempt
            $userLogin = $userExist->login()->firstOrCreate(['ip' => $_SERVER['REMOTE_ADDR']]);
            $userLogin->fail = $userLogin->fail + 1;
            $userLogin->save();
        }
    }
}
