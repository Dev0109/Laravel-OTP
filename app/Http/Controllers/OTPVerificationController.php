<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use App\PCInfo;

use Illuminate\Support\Facades\Auth;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $email = Session::get('email');
        $pc_info = Session::get('pc_info');
        $info = array(
            "email" => $email,
            'pc_info' => $pc_info,
        );

        return view('otp.verify', $info);
    }
    public function sendVerification(Request $request) {
        $url = 'https://bthkoaaau0.execute-api.us-east-1.amazonaws.com/AcerStage/pycreate';

        $user = User::where('email', $request->email)->first();
        
        $data = array(
            'phone' => array(
                'S' => $user->phone
            ),
            'pcinfo' => array(
                'S' => $request->pc_info
            ),
            'email' => array(
                'S' => $request->email
            ),
        );
        
        $payload = json_encode($data);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Send payload as POST data
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set content type to JSON

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // Close cURL resource
        curl_close ($ch);
        
        echo $response;
    }

    public function verifyCode(Request $request) {
        $url = 'https://bthkoaaau0.execute-api.us-east-1.amazonaws.com/AcerStage/pycreate';

        $user = User::where('email', $request->email)->first();

        $data = array(
            'pcinfo' => array(
                'S' => $request->pc_info
            ),
            'email' => array(
                'S' => $request->email
            ),
            'OTP' => array(
                'S' => $request->otp_code
            ),
        );
        
        $payload = json_encode($data);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Use PUT method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Send payload as POST data
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set content type to JSON

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        $user = User::where('email', $request->email)->first();

        $responseString = (string)$response;
        $temp = json_decode($response, true);
        if (isset($temp['success']) && $temp['success'] === true){
            $pc = PCInfo::where('uid', $user->id)->where('info', $request->pc_info)->first();
            $pc->is_verified = 1;
            $pc->save();
            Auth::login($user);
        }

        echo $response;
    }
}
