<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\Token_lib;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Department;
use App\Models\Counter;
use App\Models\Token;
use App\Models\TokenSetting;
use App\Models\DisplaySetting;
use Validator, DB;

class TokenController extends Controller
{
    /*-----------------------------------
    | TOKEN CURRENT / REPORT / PERFORMANCE
    |-----------------------------------*/

    public function current()
    {  
        @date_default_timezone_set(session('app.timezone'));
        $tokens = Token::where('status', '0')
        ->orderBy('is_vip', 'DESC')
        ->orderBy('id', 'ASC')
        ->get(); 

        $counters = Counter::where('status',1)->pluck('name','id');
        $departments = Department::where('status',1)->pluck('name','id');
        $officers = User::select(DB::raw('CONCAT(firstname, " ", lastname) as name'), 'id')
            ->where('user_type',1)
            ->where('status',1)
            ->orderBy('firstname', 'ASC')
            ->pluck('name', 'id'); 

        return view('backend.receptionist.token.current', compact('counters', 'departments', 'officers', 'tokens'));
    } 


    /*-----------------------------------
    | VIEW 
    |-----------------------------------*/

    public function viewSingleToken(Request $request)
    {
        return Token::select('token.*', 'department.name as department', 'counter.name as counter', 'user.firstname', 'user.lastname')
            ->leftJoin('department', 'token.department_id', '=', 'department.id')
            ->leftJoin('counter', 'token.counter_id', '=', 'counter.id')
            ->leftJoin('user', 'token.user_id', '=', 'user.id')
            ->where('token.id', $request->id)
            ->first(); 
    }

 
    /*-----------------------------------
    | AUTO TOKEN 
    |-----------------------------------*/

    public function tokenAutoView()
    {
        $display = DisplaySetting::first();
        $keyList = DB::table('token_setting AS s')
            ->select('d.key', 's.department_id', 's.counter_id', 's.user_id')
            ->leftJoin('department AS d', 'd.id', '=', 's.department_id')
            ->where('s.status', 1)
            ->get();
        $keyList = json_encode($keyList);

        if ($display->display == 5)
        {
            $departmentList = TokenSetting::select( 
                    'department.name',
                    'token_setting.department_id',
                    'token_setting.counter_id',
                    'token_setting.user_id',
                    DB::raw('CONCAT(user.firstname ," " ,user.lastname) AS officer')
                )
                ->join('department', 'department.id', '=', 'token_setting.department_id')
                ->join('counter', 'counter.id', '=', 'token_setting.counter_id')
                ->join('user', 'user.id', '=', 'token_setting.user_id')
                ->where('token_setting.status',1)
                ->groupBy('token_setting.user_id')
                ->orderBy('token_setting.department_id', 'ASC')
                ->get();
        }
        else
        {
            $departmentList = TokenSetting::select( 
                    'department.name',
                    'token_setting.department_id',
                    'token_setting.counter_id',
                    'token_setting.user_id'
                    )
                ->join('department', 'department.id', '=', 'token_setting.department_id')
                ->join('counter', 'counter.id', '=', 'token_setting.counter_id')
                ->join('user', 'user.id', '=', 'token_setting.user_id')
                ->where('token_setting.status', 1)
                ->groupBy('token_setting.department_id')
                ->get(); 
        }

        return view('backend.receptionist.token.auto', compact('display', 'departmentList', 'keyList'));
    }    

    public function tokenAuto(Request $request)
    {   
        @date_default_timezone_set(session('app.timezone'));
        
        $display = DisplaySetting::first();

        if ($display->sms_alert)
        {
            $validator = Validator::make($request->all(), [
                'client_mobile' => 'required',
                'department_id' => 'required|max:11',
                'counter_id'    => 'required|max:11',
                'user_id'       => 'required|max:11',
                'note'          => 'max:512' 
            ])
            ->setAttributeNames(array( 
               'client_mobile' => trans('app.client_mobile'),
               'department_id' => trans('app.department'),
               'counter_id' => trans('app.counter'),
               'user_id' => trans('app.officer'), 
               'note' => trans('app.note') 
            )); 
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'department_id' => 'required|max:11',
                'counter_id'    => 'required|max:11',
                'user_id'       => 'required|max:11',
                'note'          => 'max:512'
            ])
            ->setAttributeNames(array( 
               'department_id' => trans('app.department'),
               'counter_id' => trans('app.counter'),
               'user_id' => trans('app.officer'), 
               'note' => trans('app.note')
            )); 
        }

        //generate a token
        try {
            DB::beginTransaction(); 

            if ($validator->fails()) {
                $data['status'] = false;
                $data['exception'] = "<ul class='list-unstyled'>"; 
                $messages = $validator->messages();
                foreach ($messages->all('<li>:message</li>') as $message)
                {
                    $data['exception'] .= $message; 
                }
                $data['exception'] .= "</ul>"; 
            } else {  

                //find auto-setting
                $settings = TokenSetting::select('counter_id','department_id','user_id','created_at')
                        ->where('department_id', $request->department_id)
                        ->groupBy('user_id')
                        ->get();

                //if auto-setting are available
                if (!empty($settings)) { 

                    foreach ($settings as $setting) {
                        //compare each user in today
                        $tokenData = Token::select('department_id','counter_id','user_id',DB::raw('COUNT(user_id) AS total_tokens'))
                                ->where('department_id',$setting->department_id)
                                ->where('counter_id',$setting->counter_id)
                                ->where('user_id',$setting->user_id)
                                ->where('status', 0)
                                ->whereRaw('DATE(created_at) = CURDATE()')
                                ->orderBy('total_tokens', 'asc')
                                ->groupBy('user_id')
                                ->first(); 

                        //create user counter list
                        $tokenAssignTo[] = [
                            'total_tokens'  => (!empty($tokenData->total_tokens)?$tokenData->total_tokens:0),
                            'department_id' => $setting->department_id,
                            'counter_id'    => $setting->counter_id,
                            'user_id'       => $setting->user_id
                        ]; 
                    }

                    //findout min counter set to 
                    $min = min($tokenAssignTo);
                    $saveToken = [
                        'token_no'      => (new Token_lib)->newToken($min['department_id'], $min['counter_id']),
                        'client_mobile' => $request->client_mobile,
                        'department_id' => $min['department_id'],
                        'counter_id'    => $min['counter_id'],
                        'user_id'       => $min['user_id'],
                        'note'          => $request->note, 
                        'created_by'    => auth()->user()->id,
                        'created_at'    => date('Y-m-d H:i:s'), 
                        'updated_at'    => null,
                        'status'        => 0 
                    ]; 

                } else {
                    $saveToken = [
                        'token_no'      => (new Token_lib)->newToken($request->department_id, $request->counter_id),
                        'client_mobile' => $request->client_mobile,
                        'department_id' => $request->department_id,
                        'counter_id'    => $request->counter_id, 
                        'user_id'       => $request->user_id, 
                        'note'          => $request->note, 
                        'created_at'    => date('Y-m-d H:i:s'),
                        'created_by'    => auth()->user()->id,
                        'updated_at'    => null,
                        'status'        => 0
                    ];               
                }  

                //store in database  
                //set message and redirect
                if ($insert_id = Token::insertGetId($saveToken)) { 

                    $token = null;
                    //retrive token info
                    $token = Token::select(
                            'token.*', 
                            'department.name as department', 
                            'counter.name as counter', 
                            'user.firstname', 
                            'user.lastname'
                        )
                        ->leftJoin('department', 'token.department_id', '=', 'department.id')
                        ->leftJoin('counter', 'token.counter_id', '=', 'counter.id')
                        ->leftJoin('user', 'token.user_id', '=', 'user.id') 
                        ->where('token.id', $insert_id)
                        ->first(); 

                    DB::commit();
                    $data['status'] = true;
                    $data['message'] = trans('app.token_generate_successfully');
                    $data['token']  = $token;
                    
                } else {
                    $data['status'] = false;
                    $data['exception'] = trans('app.please_try_again');
                }
            }
            
            return response()->json($data);
            
        } catch(\Exception $err) {
            DB::rollBack(); 
        }
    } 

 
    /*-----------------------------------
    | FORCE/MANUAL/VIP TOKEN 
    |-----------------------------------*/

    public function showForm()
    {  
        $display = DisplaySetting::first();
        $counters = Counter::where('status',1)->pluck('name','id');
        $departments = Department::where('status',1)->pluck('name','id');
        $officers = User::select(DB::raw('CONCAT(firstname, " ", lastname) as name'), 'id')
            ->where('user_type',1)
            ->where('status',1)
            ->orderBy('firstname', 'ASC')
            ->pluck('name', 'id'); 

        return view('backend.receptionist.token.manual', compact('display', 'counters', 'departments','officers' ));
    }  

    public function create(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));
        
        $display = DisplaySetting::first();

        if ($display->sms_alert)
        {
            $validator = Validator::make($request->all(), [
                'client_mobile' => 'required',
                'department_id' => 'required|max:11',
                'counter_id'    => 'required|max:11',
                'user_id'       => 'required|max:11',
                'note'          => 'max:512',
                'is_vip'        => 'max:1'
            ])
            ->setAttributeNames(array(
               'client_mobile' => trans('app.client_mobile'),
               'department_id' => trans('app.department'),
               'counter_id'    => trans('app.counter'),
               'user_id'       => trans('app.officer'), 
               'note'          => trans('app.note'),
               'is_vip'        => trans('app.is_vip'), 
            ));  
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'department_id' => 'required|max:11',
                'counter_id'    => 'required|max:11',
                'user_id'       => 'required|max:11',
                'note'          => 'max:512',
                'is_vip'        => 'max:1'
            ])
            ->setAttributeNames(array( 
               'department_id' => trans('app.department'),
               'counter_id'    => trans('app.counter'),
               'user_id'       => trans('app.officer'),
               'note'          => trans('app.note'),
               'is_vip'        => trans('app.is_vip'), 
            )); 
        }

        if ($validator->fails()) 
        {
            $data['status'] = false;
            $data['exception'] = "<ul class='list-unstyled'>"; 
            $messages = $validator->messages();
            foreach ($messages->all('<li>:message</li>') as $message)
            {
                $data['exception'] .= $message; 
            }
            $data['exception'] .= "</ul>"; 
        } 
        else 
        { 
            $newTokenNo = (new Token_lib)->newToken($request->department_id, $request->counter_id, $request->is_vip);

            $save = Token::insert([
                'token_no'      => $newTokenNo,
                'client_mobile' => $request->client_mobile,
                'department_id' => $request->department_id,
                'counter_id'    => $request->counter_id, 
                'user_id'       => $request->user_id, 
                'note'          => $request->note, 
                'created_by'    => auth()->user()->id,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => null,
                'is_vip'        => $request->is_vip, 
                'status'        => 0 
            ]);

            if ($save) { 
                $token = Token::select(
                    'token.*', 
                    'department.name as department', 
                    'counter.name as counter', 
                    'user.firstname', 
                    'user.lastname'
                )
                ->leftJoin('department', 'token.department_id', '=', 'department.id')
                ->leftJoin('counter', 'token.counter_id', '=', 'counter.id')
                ->leftJoin('user', 'token.user_id', '=', 'user.id')
                ->whereDate('token.created_at', date("Y-m-d"))
                ->where('token.token_no', $newTokenNo)
                ->first(); 

                $data['status'] = true;
                $data['message'] = trans('app.token_generate_successfully');
                $data['token']  = $token;
            } else {
                $data['status'] = false;
                $data['exception'] = trans('app.please_try_again');
            }
        }
        return response()->json($data);
    } 


}

 