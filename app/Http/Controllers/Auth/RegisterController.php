<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PCInfo;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'min:11', 'max:16'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'captcha'  => ['required', 'string', 'min:1'],
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
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'pc_info' => 'required|string',
            'password' => Hash::make($data['password']),
        ]);
    }
    // protected function registered(Request $request, $user)
    // {
    //     Auth::logout();
    //     return redirect('/login');
    // }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
    }

    protected function registered(Request $request, $user)
    {
        $pc_info = $request->input('pc_info');
        $newPC = new PCInfo();
        $newPC->uid = $user->id;
        $newPC->info = $pc_info;
        $newPC->is_verified = 0;
        $newPC->save();
        Session::put('phone', $user->phone);
        Session::put('redirect', "register");
        Session::put('email', $user->email);
        Session::put('pc_info', $pc_info);
        return redirect()->route('otp.verify');
    }
}
