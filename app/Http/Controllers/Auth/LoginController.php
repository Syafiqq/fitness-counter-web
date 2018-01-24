<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Model\FirebaseUser;
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

    use \App\Custom\Illuminate\Foundation\Auth\AuthenticatesUsers
    {
        showLoginForm as public getLogin;
        logout as public getLogout;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view("layout.auth.login.auth_login_$this->theme");
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  FirebaseUser|mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route("{$user->getRole()}.dashboard.home");
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postLogin(LoginRequest $request)
    {
        return $this->login($request);
    }


}
