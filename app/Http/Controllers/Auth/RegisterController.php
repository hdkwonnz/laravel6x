<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;////
use Illuminate\Auth\Events\Registered;////

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //아래는 RegistersUsers.php에서 overriding
    public function register(Request $request)
    {           
        $this->validator($request->all())->validate();
        
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user); //original

        ////$permissionNames = $user->getPermissionNames();
        //// $roleNames = $user->getRoleNames();
        
        //// if ($roleNames == '["patient"]') ////
        //// {
        ////     return redirect('patient/index');           
        //// }else {
        ////     return redirect('doctor/index');
        //// }

        //// return redirect('/login');
        //return redirect('/');

        //return $this->registered($request, $user) //original
        //                 ?: redirect($this->redirectPath()); //original

        // return $this->registered($request, $user)//이메일 인증하라는 메시가 보인다.//not for vue
        //                 ?: redirect('showVerificationMsg');//이메일 인증하라는 메시가 보인다.//not for vue

        return "done";//for vue use
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
