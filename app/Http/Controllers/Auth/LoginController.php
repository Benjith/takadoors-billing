<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

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
       * Get the needed authorization credentials from the request.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return array
       */
      protected function credentials(Request $request)
      {
        return ['phone' => $request->get('phone'), 'password'=>$request->get('password')];
      }

      public function index()
      {
          return view('auth.login');
      }  

      public function login(Request $request)
        {
            $rules = [
                'phone' => 'required',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(),$rules);
            $credentials = $request->only('phone', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->route('home');
            }
            if ($validator->fails()) {
            //     $msg = $validator->messages();
                return redirect("login")->withErrors($validator);
            }
            return redirect("login")->withErrors('Oops! You have entered invalid credentials');
        }
}
