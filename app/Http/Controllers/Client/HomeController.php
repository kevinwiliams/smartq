<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Common\SMS_lib;
use App\Http\Controllers\Controller;
use App\Mail\OTP;
use App\Mail\OTPNotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Department;
use App\Models\DisplaySetting;
use Auth, DB, Validator, Hash, Image;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function home()
    {
        @date_default_timezone_set(session('app.timezone'));
        // $departments = Department::where('status', 1)->pluck('name', 'id');

        $current = Token::whereIn('status', ['0', '3'])
            ->where('client_id', auth()->user()->id)
            ->orderBy('is_vip', 'DESC')
            ->orderBy('id', 'ASC')
            ->first();

        if ($current) {
            return redirect('client/token/current');
        }

        $departments = Department::select(
            'department.name',
            'department.id'
        )
            ->join('token_setting', 'token_setting.department_id', '=', 'department.id')
            ->where('department.status', 1)
            ->pluck('name', 'id');

        $display = DisplaySetting::first();

        $smsalert = $display->sms_alert;

        $maskedemail = $this->maskEmail(auth()->user()->email);

        return view('backend.client.home.home', compact('departments', 'smsalert', 'maskedemail'));
    }


    /*-----------------------------------
    | Verify Phone 
    |-----------------------------------*/

    public function confirmMobile(Request $request)
    {

        $OTP = $this->generateNumericOTP(6);


        $update = User::where('id', auth()->user()->id)
            ->update([
                // 'mobile' => $request->phone,
                'otp'   => $OTP,
                'updated_at'  => date('Y-m-d'),
            ]);

        if ($update) {
            $display = DisplaySetting::first();

            $user = User::where('id', auth()->user()->id)->first();

            if ($display->sms_alert) {
                $sms_lib = new SMS_lib;

                $msg = "Hi " . auth()->user()->firstname . ", you're OTP is: $OTP";

                $data = $sms_lib
                    ->to($request->phone)
                    ->message($msg)
                    ->response();
            } else {

                Mail::to(auth()->user()->email)->send(new OTPNotification($user));
            }

            // return json_decode($data, true);
            return json_encode(array(
                'status'      => true,
                'request_url' => "",
                'error'       => "",
                'message'     => $OTP
            ));
        } else {
            return json_encode(array(
                'status'      => false,
                'request_url' => "",
                'error'       => "",
                'message'     => ""
            ));
        }
    }

    public function confirmOTP(Request $request)
    {
        $OTP = auth()->user()->otp;       

        if ($request->code == $OTP) {
            $display = DisplaySetting::first();
            
            if ($display->sms_alert) {
            $update = User::where('id', auth()->user()->id)
                ->update([
                    'mobile' => $request->phone,
                    'updated_at'  => date('Y-m-d'),
                ]);
            }else{
                $update = User::where('id', auth()->user()->id)
                ->update([                    
                    'updated_at'  => date('Y-m-d'),
                ]);
            }

            return json_encode(array(
                'status'      => true,
            ));
        } else {
            return json_encode(array(
                'status'      => false,
            ));
        }
    }


    public function getwaittime(Request $request)
    {
        $dept = Department::find($request->id);
        $waiting = Token::whereIn('status', [0, 3])->where('department_id', $request->id)->count();
        $waiting = $waiting - 1;

        $waittime = 0;

        $waittime = ($dept->avg_wait_time != null) ? $dept->avg_wait_time * $waiting : $waiting * 1;
        return json_encode(date('H:i', mktime(0, $waittime)));
    }

    // Function to generate OTP
    function generateNumericOTP($n)
    {

        // Take a generator string which consist of
        // all numeric digits
        $generator = "1234567890";

        // Iterate for n-times and pick a single character
        // from generator and append it to $result

        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        // Return result
        return $result;
    }

    function maskEmail($x)
    {
        $arr = explode("@", trim($x));

        return $arr[0][0] . str_repeat("*", strlen($arr[0])  - 2) . $arr[0][strlen($arr[0]) - 1] . "@" . $arr[1];
    }
}
