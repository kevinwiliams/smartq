<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Display; 
use App\Models\DisplaySetting; 
use Carbon\Carbon;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
use App\Models\Token;
use DB, Response, File, Validator;


class CronjobController extends Controller
{ 
    public function sms()
    { 
        $setting = DisplaySetting::first();  

        if ($setting->display==5)
        {
            //display 5: hospital queue - like display 2
            return $this->display3();
        }
        elseif ($setting->display==4)
        {
            //display 4: department wise queue
            return $this->display4();
        }
        elseif ($setting->display==3)
        { 
            //display 3: counter wise queue 2
            return $this->display3();
        }
        elseif ($setting->display==2)
        {
            //display 2: counter wise queue
            return $this->display3();
        }
        else
        {
            //display 1: single line queue
            return $this->display1();
        }
    }

    //single line q
    public function display1()
    {  
        $setting   = DisplaySetting::first();  
        $tokenInfo = DB::table('token AS t')
            ->select(
                "t.id",
                "t.token_no AS token",
                "t.client_mobile AS mobile",
                "d.name AS department",
                "c.name AS counter",
                DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                "t.status",
                "t.sms_status", 
                "t.created_at AS date" 
            )
            ->leftJoin("department AS d", "d.id", "=", "t.department_id")
            ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
            ->leftJoin("user AS o", "o.id", "=", "t.user_id")
            ->where("t.status", "0")
            ->orderBy('t.is_vip', 'DESC')
            ->orderBy('t.id', 'ASC')
            ->offset($setting->alert_position)
            ->limit(1)
            ->get(); 

            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0 && ($tokenInfo->sms_status==0 || $tokenInfo->sms_status==2)) 
            {
                // send sms
                $data['status'] = true;
                $data['result'] = $tokenInfo;
                $this->sendSMS($tokenInfo, $setting->alert_position); 
            }
            else
            {
                //nothing
                $data['status'] = false;
                $data['result'] = $tokenInfo; 
            }  

        return Response::json($data);
    }

    //counter wise 
    public function display3()
    {
        $setting = DisplaySetting::first();
        $counters = DB::table('counter')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();

        $allToken = array();
        $data     = array();
        foreach ($counters as $counter) 
        {
            $tokens = DB::table('token AS t')
                ->select(
                    "t.id",
                    "t.token_no AS token",
                    "t.client_mobile AS mobile",
                    "d.name AS department",
                    "c.name AS counter",
                    DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                    "t.status",
                    "t.sms_status", 
                    "t.created_at" 
                )
                ->leftJoin("department AS d", "d.id", "=", "t.department_id")
                ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
                ->leftJoin("user AS o", "o.id", "=", "t.user_id")
                ->where("t.counter_id", $counter->id)
                ->where("t.status", "0")
                ->offset($setting->alert_position)
                ->orderBy('t.is_vip', 'DESC')
                ->orderBy('t.id', 'ASC')
                ->limit(1)
                ->get(); 

            foreach ($tokens as $token) 
            {
                $allToken[$counter->name] = (object)array(
                    'id'         => $token->id,
                    'token'      => $token->token,
                    'department' => $token->department,
                    'counter'    => $token->counter,
                    'officer'    => $token->officer,
                    'mobile'     => $token->mobile,
                    'date'       => $token->created_at,
                    'status'     => $token->status,
                    'sms_status' => $token->sms_status,
                ); 
            }   
        }  

        foreach ($allToken as $counter => $tokenInfo) 
        {  
            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0 && ($tokenInfo->sms_status==0 || $tokenInfo->sms_status==2)) 
            {
                $data['status'] = true;
                $data['result'][] = $tokenInfo;
                // send sms 
                $this->sendSMS($tokenInfo, $setting->alert_position); 
            }
            else 
            {
                $data['status'] = false;
                $data['result'][] = $tokenInfo;
            }
        }

        return Response::json($data);
    }

    //department wise 
    public function display4()
    {
        $setting = DisplaySetting::first();
        $departments = DB::table('department')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();

        $allToken = array();
        $data     = array();
        foreach ($departments as $department) 
        {
            $tokens = DB::table('token AS t')
                ->select(
                    "t.id",
                    "t.token_no AS token",
                    "t.client_mobile AS mobile",
                    "d.name AS department",
                    "c.name AS counter",
                    DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                    "t.status",
                    "t.sms_status", 
                    "t.created_at" 
                )
                ->leftJoin("department AS d", "d.id", "=", "t.department_id")
                ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
                ->leftJoin("user AS o", "o.id", "=", "t.user_id")
                ->where("t.department_id", $department->id)
                ->where("t.status", "0")
                ->orderBy('t.is_vip', 'DESC')
                ->orderBy('t.id', 'ASC')
                ->offset($setting->alert_position)
                ->limit(1)
                ->get(); 

            foreach ($tokens as $token) 
            {
                $allToken[$department->name] = (object)array(
                    'id'         => $token->id,
                    'token'      => $token->token,
                    'department' => $token->department,
                    'counter'    => $token->counter,
                    'officer'    => $token->officer,
                    'mobile'     => $token->mobile,
                    'date'       => $token->created_at,
                    'status'     => $token->status,
                    'sms_status' => $token->sms_status,
                ); 
            }   
        }  

        foreach ($allToken as $counter => $tokenInfo) 
        {  
            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0 && ($tokenInfo->sms_status==0 || $tokenInfo->sms_status==2)) 
            {
                $data['status'] = true;
                $data['result'][] = $tokenInfo;
                // send sms 
                $this->sendSMS($tokenInfo, $setting->alert_position); 
            }
            else 
            {
                $data['status'] = false;
                $data['result'][] = $tokenInfo;
            }
        }

        return Response::json($data);
    }
  
    /*
    *---------------------------------------------------------
    * SEND SMS
    *--------------------------------------------------------- 
    */
    public function sendSMS($token, $alert_position = null)
    {
        date_default_timezone_set(session('app.timezone'));
        
        //send sms immediately
        $setting  = SmsSetting::first();   

        $template = ($token->sms_status==2)?$setting->recall_sms_template:$setting->sms_template;

        $response = (new SMS_lib)
            ->provider("$setting->provider")
            ->api_key("$setting->api_key")
            ->username("$setting->username")
            ->password("$setting->password")
            ->from("$setting->from")
            ->to($token->mobile)
            ->message($template, array(
                'TOKEN'  => $token->token,
                'MOBILE' => $token->mobile,
                'DEPARTMENT'=> $token->department,
                'COUNTER'=> $token->counter,
                'OFFICER'=> $token->officer,
                'DATE'   => $token->date,
                'WAIT'   => $alert_position
            ))
            ->response();

        $api = json_decode($response, true); 
 
        //store sms information 
        $sms = new SmsHistory; 
        $sms->from        = $setting->from;
        $sms->to          = $token->mobile;
        $sms->message     = $api['message'];
        $sms->response    = $response;
        $sms->created_at  = date('Y-m-d H:i:s');
        $sms->save();

        //SMS SENT
        Token::where('id', $token->id)->update(['sms_status' => 1]);
    } 


}
