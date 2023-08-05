<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use App\PCInfo;
use Aws\Laravel\AwsFacade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $email = Session::get('email');
        $phone = Session::get('phone');
        $redirect = Session::get('redirect');
        $pc_info = Session::get('pc_info');
        $info = array(
            "email" => $email,
            'pc_info' => $pc_info,
            'redirect' => $redirect,
        );

        $user = User::where('email', $email)->first();
        $user->sms_code = mt_rand(100000, 999999);
        $user->phone_time = Carbon::parse()->addMinutes(5);
        $user->save();

        $this->send_sms_aws($phone, 'Your Verification Code is'  . $user->sms_code);

        return view('otp.verify', $info);
    }

    public function send_sms_aws($to, $message)
    {
        $sms = AwsFacade::createClient('sns');
return;
        $sms->publish([
            'Message' => $message,
            'PhoneNumber' => $to,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType'  => [
                    'DataType'    => 'String',
                    'StringValue' => 'Transactional',
                ]
            ],
        ]);

        // $params = array(
        //     'credentials' => array(
        //         'key' => 'AKIAYHZRBISG7OTSZIGT',
        //         'secret' => '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',
        //     ),
        //     'region' => 'us-east-1',
        //     'version' => 'latest'
        // );
        // $sns = new \Aws\Sns\SnsClient($params);

        // $args = array(
        //     "MessageAttributes" => [
        //         'AWS.SNS.SMS.SMSType' => [
        //             'DataType' => 'String',
        //             'StringValue' => 'Transactional'
        //         ]
        //     ],
        //     "Message" => $message,
        //     "PhoneNumber" => $to
        // );

        // $result = $sns->publish($args);
    }

    public function sendVerification(Request $request)
    {
        $url = 'https://bthkoaaau0.execute-api.us-east-1.amazonaws.com/AcerStage/pycreate';

        $user = User::where('email', $request->email)->first();

        // $data = array(
        //     'phone' => array(
        //         'S' => $user->phone
        //     ),
        //     'pcinfo' => array(
        //         'S' => $request->pc_info
        //     ),
        //     'email' => array(
        //         'S' => $request->email
        //     ),
        // );

        // $payload = json_encode($data);

        // // Initialize cURL session
        // $ch = curl_init();

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1); // Use POST method
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Send payload as POST data
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set content type to JSON

        // // Execute cURL request and get response
        // $response = curl_exec($ch);

        // // Close cURL resource
        // curl_close($ch);

        // echo $response;
        if (Carbon::parse($user->phone_time)->addMinutes(1) > Carbon::now()) {
            $this->send_sms_aws($user->phone, 'Your Verification Code is'  . $user->sms_code);
        } else {
            $code = mt_rand(100000, 999999);
            $user->phone_time = Carbon::now();
            $user->sms_code = $code;
            $user->save();
            $this->send_sms_aws($user->phone, 'Your Verification Code is' . $code);
        }
    }

    public function verifyCode(Request $request)
    {
        $url = 'https://bthkoaaau0.execute-api.us-east-1.amazonaws.com/AcerStage/pycreate';

        $user = User::where('email', $request->email)->first();

        // $data = array(
        //     'pcinfo' => array(
        //         'S' => $request->pc_info
        //     ),
        //     'email' => array(
        //         'S' => $request->email
        //     ),
        //     'OTP' => array(
        //         'S' => $request->otp_code
        //     ),
        // );

        // $payload = json_encode($data);

        // // Initialize cURL session
        // $ch = curl_init();

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Use PUT method
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Send payload as POST data
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set content type to JSON

        // // Execute cURL request and get response
        // $response = curl_exec($ch);

        // // Close cURL session
        // curl_close($ch);

        // $user = User::where('email', $request->email)->first();

        // $responseString = (string)$response;
        // $temp = json_decode($response, true);
        // if (isset($temp['success']) && $temp['success'] === true) {
        //     $pc = PCInfo::where('uid', $user->id)->where('info', $request->pc_info)->first();
        //     $pc->is_verified = 1;
        //     $pc->save();
        //     Auth::login($user);
        // }

        // echo $response;

        if ($user->sms_code == $request->otp_code) {
            $pc = PCInfo::where('uid', $user->id)->where('info', $request->pc_info)->first();
            if ($pc) {
                $pc->is_verified = 1;
                $pc->save();

        $redirect = Session::get('redirect');
        if($redirect==='login')
                Auth::login($user);
            } else {
//                 return redirect()->route('/login');
            }
            $response = true;
        } else {
            $response = false;
        }

        echo json_encode($response);
    }
}
