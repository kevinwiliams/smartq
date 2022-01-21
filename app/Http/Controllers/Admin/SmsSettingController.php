<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\SMS_lib;
use App\Models\SmsSetting;
use App\Models\SmsHistory; 
use Validator;

class SmsSettingController extends Controller
{
    
    # Show the SMS list
    public function show()
    { 
        return view('backend.admin.sms.list');
    } 

    public function smsData(Request $request) 
    {
        $columns = [
            0 => 'id',
            1 => 'to',
            2 => 'message',
            3 => 'created_at',
            4 => 'id' 
        ];
  
        $totalData = SmsHistory::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $smses = SmsHistory::offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->get();
        }
        else 
        { 
            $smsesProccess = SmsHistory::where(function($query)  use($search) {

                if (!empty($search['start_date']) && !empty($search['end_date'])) {
                    $query->whereBetween("created_at",[
                        date('Y-m-d', strtotime($search['start_date']))." 00:00:00", 
                        date('Y-m-d', strtotime($search['end_date']))." 23:59:59"
                    ]);
                }
         
                if (!empty($search['value'])) {
                    $query->orWhere('to', 'LIKE',"%{$search['value']}%")
                        ->orWhere('message', 'LIKE',"%{$search['value']}%") 
                        ->orWhere(function($query)  use($search) {
                            $date = date('Y-m-d', strtotime($search['value']));
                            $query->whereDate('created_at', 'LIKE',"%{$date}%");
                        }); 
                }
            });
        
            $totalFiltered = $smsesProccess->count();

            $smses = $smsesProccess->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 

        }

        $data = array();
        if(!empty($smses))
        {
            $loop = 1;
            foreach ($smses as $sms)
            $data[] = [
                'serial'     => $loop++,
                'to'         => $sms->to,
                'message'    => $sms->message,
                'created_at' => (!empty($sms->created_at)?date('j M Y h:i a',strtotime($sms->created_at)):null),
                'options'    => "<div class=\"btn-group\">
                    <button type=\"button\" class=\"btn btn-sm btn-primary btn-sm\" data-mobile='{$sms->to}' data-data='{$sms->response}' data-toggle=\"modal\" data-target=\"#showApiResponse\"><i class=\"fa fa-eye\"></i></button>

                    <a href='".url("admin/sms/delete/$sms->id")."' onclick=\"return confirm('".trans('app.are_you_sure')."')\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-times\"></i></a>
                </div>" 
            ];   
        }
            
        return response()->json([
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        ]);
    }

    # SMS form
    public function form()
    {
    	$sms_setting = SmsSetting::first();
        return view('backend.admin.sms.form', compact('sms_setting'));
    }

    # Send a sms
    public function send(Request $request)
    {
        @date_default_timezone_set(session('app.timezone'));
        
        $validator = Validator::make($request->all(), [ 
            'to'        => 'required|max:100',
            'message'   => 'required|max:255',
        ])
        ->setAttributeNames(array(
           'to' => trans('app.mobile'),
           'message' => trans('app.message') 
        ));   

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput(); 
        } 
        else 
        {     
        	$setting  = SmsSetting::first();
            $response = (new SMS_lib)
	            ->provider("$setting->provider")
	            ->api_key("$setting->api_key")
	            ->username("$setting->username")
	            ->password("$setting->password")
	            ->from("$setting->from")
	            ->to("$request->to")
	            ->message("$request->message")
	            ->response();

            //store sms information 
            $sms = new SmsHistory; 
            $sms->from        = $setting->from;
            $sms->to          = $request->to;
            $sms->message     = $request->message;
            $sms->response    = $response;
            $sms->created_at  = date('Y-m-d H:i:s');

            if ($sms->save()) 
            {
                return back()
                    ->withInput()
                    ->with('message', trans('app.sms_sent'));
            } else {

                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('exception', trans('app.please_try_again')); 
            }
        } 
    }

    # Delete admin data by id_no
    public function delete(Request $request)
    {
        // request by id_no
        $sms = SmsHistory::where('id', $request->id)->delete();

        if ($sms) 
        {
            return back()
                ->withInput()
                ->with('message', trans('app.delete_successfully'));
        } else {

            return back()
                ->withInput()
                ->withErrors($validator)
                ->with('exception', trans('app.please_try_again')); 
        } 
    } 


    /*
    *---------------------------------------------------------
    * SETTING
    *--------------------------------------------------------- 
    */
    # Show the email setting form
    public function setting(Request $request)
    {
        $setting = SmsSetting::first(); 
        if (!$setting) 
        {
            $data = new SmsSetting;
            $data->provider = 'nexmo';
            $data->api_key  = '';
            $data->username = '';
            $data->password = '';
            $data->from     = 'Token - Queue Management System';
            $data->sms_template = 'Token No: [TOKEN] \r\n Department: [DEPARTMENT], Counter: [COUNTER] and Officer: [OFFICER]. \r\n Your waiting no is [WAIT]. \r\n [DATE]';
            $data->recall_sms_template = 'Please contact urgently. Token No: [TOKEN] \r\n Department: [DEPARTMENT], Counter: [COUNTER] and Officer: [OFFICER].\r\n[DATE]';
            $data->save();
        } 
        return view('backend.admin.sms.setting', compact('setting'));
    }


    # Update sms setting
    public function updateSetting(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'provider' => 'required|max:20',
            'api_key'  => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required|max:255', 
            'from'     => 'required|max:50', 
            'sms_template' => 'required|max:1020', 
            'recall_sms_template' => 'required|max:1020' 
        ])
        ->setAttributeNames(array(
           'provider' => trans('app.provider'),
           'api_key' => trans('app.api_key'),
           'username' => trans('app.username'),
           'password' => trans('app.password'),
           'from' => trans('app.from'),
           'sms_template' => trans('app.sms_template'),
           'recall_sms_template' => trans('app.recall_sms_template') 
        ));  

        if ($validator->fails()) 
        { 
            return back()
                ->withInput()
                ->withErrors($validator)
                ->with('exception', trans('app.please_try_again')); 
        } 
        else 
        { 
            $setting = SmsSetting::find($request->id);
            $setting->provider = $request->provider;
            $setting->api_key  = $request->api_key;
            $setting->username = $request->username;
            $setting->password = $request->password;
            $setting->from     = $request->from;
            $setting->sms_template = $request->sms_template;
            $setting->recall_sms_template = $request->recall_sms_template;

            if ($setting->save()) 
            {
                return back()
                    ->withInput()
                    ->with('message', trans('app.update_successfully'));
            } 
            else 
            { 
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('exception', trans('app.please_try_again')); 
            } 
        }
    } 
}
