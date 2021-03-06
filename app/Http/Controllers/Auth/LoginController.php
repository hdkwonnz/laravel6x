<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;////
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        
        $roleNames = auth()->user()->getRoleNames();
               
        // if ($roleNames == '["admin"]') { //vue 사용하지 않을 때
        //     return redirect('admin/index'); //vue 사용하지 않을 때
        // } //vue 사용하지 않을 때

        if ($roleNames == '["admin"]') { //for vue use
            return "admin"; //for vue use
        } //for vue use
                        
        // return $this->authenticated($request, $this->guard()->user()) //original and vue 사용하지 않을때
        //          ?: redirect()->intended($this->redirectPath());//original and vue 사용하지 않을때

        return "noAdmin"; //for vue use
    }
}
