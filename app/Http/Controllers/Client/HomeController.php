<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Common\SMS_lib;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Department;
use Auth, DB, Validator, Hash, Image;

class HomeController extends Controller
{

    public function home()
    {
        @date_default_timezone_set(session('app.timezone'));
        // $departments = Department::where('status', 1)->pluck('name', 'id');

        $departments = Department::select(
            'department.name',
            'department.id'
        )
            ->join('token_setting', 'token_setting.department_id', '=', 'department.id')
            ->where('department.status', 1)
            ->pluck('name', 'id');

        return view('backend.client.home.home', compact('departments'));
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
            // $sms_lib = new SMS_lib;

            // $msg = "Hi " . auth()->user()->firstname . ", you're OTP is: $OTP";

            // $data = $sms_lib                
            //     ->to($request->phone)
            //     ->message($msg)
            //     ->response();

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
        // echo '<pre>';
        // print_r($OTP);
        // echo '</pre>';
        // echo '<pre>';
        // print_r($request->code);
        // echo '</pre>';
        // die();
     
        if ($request->code == $OTP) {
            $update = User::where('id', auth()->user()->id)
                ->update([
                    'mobile' => $request->phone,
                    'updated_at'  => date('Y-m-d'),
                ]);

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
        $waiting = Token::where('status',0)->where('department_id',$request->id)->count();

        $waittime = 0;

        $waittime = ($dept->avg_wait_time != null)? $dept->avg_wait_time * $waiting :$waiting * 1;
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
}
