<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaptchaController extends Controller
{
    public function index()
    {
        $sessionLifetime = env('CAPTCHA', 15); // 15 minutes
        $lastActivity = session('last_activity');

        if ($lastActivity && time() - $lastActivity > $sessionLifetime * 60) {
            return view('captcha.index');
        } else {
            return redirect('/admin');
        }
    }

    public function captchaVerify(Request $request)
    {
        if ($request->verify) {
            session(['last_activity' => time()]);
            return json_encode(true);
        } else {
            Auth::logout();
            return json_encode(false);
        }
        return json_encode(false);
    }
}
