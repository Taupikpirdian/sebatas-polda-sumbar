<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // kriminal
        if($user->divisi == "Ditreskrimsus" || $user->divisi == "Ditreskrimum" || $user->divisi == "Ditresnarkoba" || $user->divisi == "Satreskrim" || $user->divisi == "Satnarkoba" || $user->divisi == "Unit Reskrim" ){
            return redirect('/');
        }elseif($user->divisi == "Ditlantas" || $user->divisi == "Satlantas"){ // laka lantas
            return redirect('/dashboard/laka-lantas'); 
        }else{ // jika user adalah admin
            return redirect('/');
        }

    }
}
