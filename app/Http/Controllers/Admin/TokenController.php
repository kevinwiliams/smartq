<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\SMS_lib;
use App\Http\Controllers\Common\Token_lib;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Department;
use App\Models\Counter;
use App\Models\Token;
use App\Models\DisplaySetting;
use App\Models\TokenSetting;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
use DB, Validator;

class TokenController extends Controller
{
 
    /*-----------------------------------
    | AUTO TOKEN SETTING
    |-----------------------------------*/

    public function tokenSettingView()
    { 
        $tokens = TokenSetting::select('token_setting.*', 'department.name as department', 'counter.name as counter', 'user.firstname', 'user.lastname')
                ->leftJoin('department', 'token_setting.department_id', '=', 'department.id')
                ->leftJoin('counter', 'token_setting.counter_id', '=', 'counter.id')
                ->leftJoin('user', 'token_setting.user_id', '=', 'user.id')
                ->get();
 
        $countertList = Counter::where('status',1)->pluck('name','id');
        $departmentList = Department::where('status',1)->pluck('name','id');
        $userList = User::select('id', DB::raw('CONCAT(firstname, " ", lastname, " <", email, ">") AS name'))->where('user_type',1)->where('status',1)->orderBy('name', 'ASC')->pluck('name', 'id');

        return view('backend.admin.token.setting', compact('tokens','countertList','departmentList','userList')); 
    }

    public function tokenSetting(Request $request)
    {   
        @date_default_timezone_set(session('app.timezone'));
        
        
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|max:11',
            'counter_id'    => 'required|unique:token_setting,counter_id|max:11',
            'user_id'       => 'required|unique:token_setting,user_id|max:11'
        ])
        ->setAttributeNames(array( 
           'department_id' => trans('app.department'),
           'counter_id' => trans('app.counter'),
           'user_id' => trans('app.officer') 
        )); 

        if ($validator->fails()) {
            return redirect('admin/token/setting')
                        ->withErrors($validator)
                        ->withInput();
        } else { 

            $check = TokenSetting::where('department_id',$request->department_id)
                    ->where('counter_id',$request->counter_id)
                    ->where('user_id',$request->user_id)
                    ->count();
            if ($check > 0) {
                return back()->with('exception', trans('app.setup_already_exists'))
                    ->withInput();
            }

            $save = TokenSetting::insert([ 
                'department_id' => $request->department_id,
                'counter_id'    => $request->counter_id, 
                'user_id'       => $request->user_id, 
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => null,
                'status'        => 1
            ]);

            if ($save) {
                return back()->withInput()->with('message',  trans('app.setup_successfully'));
            } else {
                return back()->withInput()->with('exception', trans('app.please_try_again'));
            }

        }
    }
 
    public function tokenDeleteSetting($id = null)
    {
        TokenSetting::where('id', $id)->delete();
        return back()->with('message', trans('app.delete_successfully'));
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

        return view('backend.admin.token.auto', compact('display', 'departmentList', 'keyList'));
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

        return view('backend.admin.token.manual', compact('display', 'counters', 'departments','officers' ));
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

        return view('backend.admin.token.current', compact('counters', 'departments', 'officers', 'tokens'));
    } 

    public function report(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));
        $counters = Counter::where('status',1)->pluck('name','id');
        $departments = Department::where('status',1)->pluck('name','id');
        $officers = User::select(DB::raw('CONCAT(firstname, " ", lastname) as name'), 'id')
            ->where('user_type',1)
            ->where('status',1)
            ->orderBy('firstname', 'ASC')
            ->pluck('name', 'id'); 

        return view('backend.admin.token.report', compact('counters', 'departments', 'officers'));
    }  

    public function reportData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'token_no',
            2 => 'department_id',
            3 => 'counter_id',
            4 => 'user_id',
            5 => 'client_mobile',
            6 => 'note',
            7 => 'status',
            8 => 'created_by',
            9 => 'created_at',
            10 => 'updated_at',
            11 => 'updated_at',
            12 => 'id',
        ]; 

        $totalData = Token::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $tokens = Token::offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->get();
        }
        else 
        { 
            $tokensProccess = Token::where(function($query)  use($search) {

                    if (!empty($search['status'])) {
                        $query->where('status', '=', $search['status']);
                    }
                    if (!empty($search['counter'])) {
                        $query->where('counter_id', '=', $search['counter']);
                    }
                    if (!empty($search['department'])) {
                        $query->where('department_id', '=', $search['department']);
                    }
                    if (!empty($search['officer'])) {
                        $query->where('user_id', '=', $search['officer']);
                    }

                    if (!empty($search['start_date']) && !empty($search['end_date'])) {
                        $query->whereBetween("created_at",[
                            date('Y-m-d', strtotime($search['start_date']))." 00:00:00", 
                            date('Y-m-d', strtotime($search['end_date']))." 23:59:59"
                        ]);
                    }
 
                    if (!empty($search['value'])) {

                        if ((strtolower($search['value']))=='vip') 
                        {
                            $query->where('is_vip', '1');
                        }
                        else
                        {
                            $date = date('Y-m-d', strtotime($search['value']));
                            $query->where('token_no', 'LIKE',"%{$search['value']}%")
                                ->orWhere('client_mobile', 'LIKE',"%{$search['value']}%")
                                ->orWhere('note', 'LIKE',"%{$search['value']}%")
                                ->orWhere(function($query)  use($date) {
                                    $query->whereDate('created_at', 'LIKE',"%{$date}%");
                                })
                                ->orWhere(function($query)  use($date) {
                                    $query->whereDate('updated_at', 'LIKE',"%{$date}%");
                                })
                                ->orWhereHas('generated_by', function($query) use($search) {
                                    $query->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE',"%{$search['value']}%");
                                }); 
                        }
                    }
                });

            $totalFiltered = $tokensProccess->count();
            $tokens = $tokensProccess->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 

        }

        $data = array();
        if(!empty($tokens))
        {
            $loop = 1;
            foreach ($tokens as $token)
            {  
                # complete time calculation
                $complete_time = "";
                if (!empty($token->updated_at)) {  
                    $date1 = new \DateTime($token->created_at); 
                    $date2 = new \DateTime($token->updated_at); 
                    $diff  = $date2->diff($date1); 
                    $complete_time = (($diff->d > 0) ? " $diff->d Days " : null) . "$diff->h Hours $diff->i Minutes ";
                }

                # buttons
                $options = "<div class=\"btn-group\">";
                if ($token->status == 0) {
                    $options .= "<a href=\"".url("admin/token/complete/$token->id")."\"  class=\"btn btn-success btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Complete\"><i class=\"fa fa-check\"></i></a>";
                }
                if ($token->status != 0 || !empty($token->updated_at)) {
                    $options .= "<a href=\"".url("admin/token/recall/$token->id")."\"  class=\"btn btn-info btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Re-call\"><i class=\"fa fa-phone\"></i></a>";
                }
                if ($token->status == 0) {
                    $options .= "<button type=\"button\" data-toggle=\"modal\" data-target=\".transferModal\" data-token-id='{$token->id}' class=\"btn btn-primary btn-sm\" title=\"Transfer\"><i class=\"fa fa-exchange\"></i></button> 
                        <a href=\"". url("admin/token/stoped/$token->id")."\"  class=\"btn btn-warning btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Stoped\"><i class=\"fa fa-stop\"></i></a>";
                } 

                $options .= "<button type=\"button\" href=\"".url("admin/token/print")."\" data-token-id='$token->id' class=\"tokenPrint btn btn-default btn-sm\" title=\"Print\"><i class=\"fa fa-print\"></i></button>
                    <a href=\"".url("admin/token/delete/$token->id")."\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm('Are you sure?');\" title=\"Delete\"><i class=\"fa fa-times\"></i></a>"; 
                $options .= "</div>"; 

                $data[] = [
                    'serial'     => $loop++,
                    'token_no'   => (!empty($token->is_vip)?("<span class=\"label label-danger\" title=\"VIP\">$token->token_no</span>"):$token->token_no),
                    'department' => (!empty($token->department)?$token->department->name:null),
                    'counter'    => (!empty($token->counter)?$token->counter->name:null),
                    'officer'    => (!empty($token->officer)?("<a href='".url("admin/user/view/{$token->officer->id}")."'>".$token->officer->firstname." ". $token->officer->lastname."</a>"):null),

                    'client_mobile'    => $token->client_mobile. "<br/>" .(!empty($token->client)?("(<a href='".url("admin/user/view/{$token->client->id}")."'>".$token->client->firstname." ". $token->client->lastname."</a>)"):null),

                    'note'       => $token->note,
                    'status'     => (($token->status==1)?("<span class='label label-success'>".trans('app.complete')."</span>"):(($token->status==2)?"<span class='label label-danger'>".trans('app.stop')."</span>":"<span class='label label-primary'>".trans('app.pending')."</span>")).(!empty($token->is_vip)?('<span class="label label-danger" title="VIP">VIP</span>'):''),
                    'created_by'    => (!empty($token->generated_by)?("<a href='".url("admin/user/view/{$token->generated_by->id}")."'>".$token->generated_by->firstname." ". $token->generated_by->lastname."</a>"):null),
                    'created_at' => (!empty($token->created_at)?date('j M Y h:i a',strtotime($token->created_at)):null),
                    'updated_at' => (!empty($token->updated_at)?date('j M Y h:i a',strtotime($token->updated_at)):null),
                    'complete_time' => $complete_time,
                    'options'    => $options
                ];  
            }
        }
            
        return response()->json([
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        ]);
    }

    public function performance(Request $request)
    { 
        @date_default_timezone_set(session('app.timezone'));
        
        $report = (object)array(
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date 
        );

        //REPORT DATA PROCESSING...
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date   = date('Y-m-d', strtotime($request->end_date)); 
    
        $tokens = DB::select("
         SELECT 
            realToken.user_id AS uid,
          (SELECT CONCAT_WS(' ', firstname, lastname) FROM user WHERE id= realToken.user_id) as officer,
          (
            SELECT COUNT(id) 
            FROM token 
            WHERE 
                user_id=realToken.user_id
                AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')
          ) AS total,
          
          (
            SELECT COUNT(id) 
            FROM token 
            WHERE 
                status = 2 
                AND user_id=realToken.user_id
                AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')
          ) AS stoped,
          (
            SELECT COUNT(id) 
            FROM token 
            WHERE 
                status = 1 
                AND user_id=realToken.user_id
                AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')
          ) AS success,
          (
            SELECT COUNT(id)
            FROM token 
            WHERE 
                status = 0 
                AND user_id=realToken.user_id
                AND (DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')
          ) AS pending
          FROM 
            token AS realToken
          GROUP BY user_id
        ");
        //ENDS OF REPORT DATA PROCESSING...

        return view('backend.admin.token.performance', compact(  'report','tokens'));
    }


    /*-----------------------------------
    | VIEW / RECALL / COMPLETE / STOPED / DELETE 
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

    public function recall($id = null)
    {
        @date_default_timezone_set(session('app.timezone')); 
        
        //send sms immediately
        $setting  = SmsSetting::first(); 
        $token = DB::table('token AS t')
            ->select(
                "t.token_no AS token",
                "t.client_mobile AS mobile",
                "d.name AS department",
                "c.name AS counter",
                DB::raw("CONCAT_WS(' ', u.firstname, u.lastname) AS officer"),
                "t.created_at AS date" 
            )
            ->leftJoin('department AS d', 'd.id', '=', 't.department_id')
            ->leftJoin('counter AS c', 'c.id', '=', 't.counter_id')
            ->leftJoin('user AS u', 'u.id', '=', 't.user_id')
            ->where('t.id', $id)
            ->first();

        if (!empty($token->mobile))
        {
            $response = (new SMS_lib)
                ->provider("$setting->provider")
                ->api_key("$setting->api_key")
                ->username("$setting->username")
                ->password("$setting->password")
                ->from("$setting->from")
                ->to($token->mobile)
                ->message($setting->recall_sms_template, array(
                    'TOKEN'  =>$token->token,
                    'MOBILE' =>$token->mobile,
                    'DEPARTMENT'=>$token->department,
                    'COUNTER'=>$token->counter,
                    'OFFICER'=>$token->officer,
                    'DATE'   =>$token->date
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
        }
        
        Token::where('id', $id)
            ->update([
                'updated_at' => date('Y-m-d H:i:s'), 
                'status'     => 0,
                'sms_status' => 2
            ]);

        //RECALL 
        return redirect()->back()->with('message', trans('app.recall_successfully'));
    } 

    public function complete($id = null)
    {
        @date_default_timezone_set(session('app.timezone'));
        
        Token::where('id', $id)->update(['updated_at' => date('Y-m-d H:i:s'), 'status' => 1, 'sms_status' => 1]);
        return redirect()->back()->with('message', trans('app.complete_successfully'));
    } 

    public function stoped($id = null)
    { 
        Token::where('id', $id)->update(['updated_at' => null, 'status' => 2,'sms_status' => 1]);
        return redirect()->back()->with('message', trans('app.update_successfully'));
    } 

    public function transfer(Request $request)
    {
        // transfer token
        $validator = Validator::make($request->all(), [
            'id'            => 'required|max:11',
            'department_id' => 'required|max:11',
            'counter_id'    => 'required|max:11',
            'user_id'       => 'required|max:11' 
        ])
        ->setAttributeNames(array( 
           'id'            => trans('app.token'),
           'department_id' => trans('app.department'),
           'counter_id'    => trans('app.counter'),
           'user_id'       => trans('app.officer') 
        )); 

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
            $update = Token::where('id', $request->id)
            ->update([
                'department_id' => $request->department_id,
                'counter_id'    => $request->counter_id, 
                'user_id'       => $request->user_id, 
            ]);

            if ($update) 
            {  
                $data['status'] = true;
                $data['message'] = trans('app.token_transfered_successfully');
            } else {
                $data['status'] = false;
                $data['exception'] = trans('app.please_try_again');
            }
        }
        
        return response()->json($data);
    }

    public function delete($id = null)
    { 
        Token::where('id', $id)->delete();
        return redirect()->back()->with('message', trans('app.delete_successfully'));
    }  

}

 