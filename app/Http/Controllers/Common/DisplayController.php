<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use App\Models\DisplaySetting; 
use App\Models\DisplayCustom; 
use App\Models\Setting;  
use DB, Response, Validator;


class DisplayController extends Controller
{ 
    public function display()
    {  
        $appSetting = Setting::first();  
        $setting    = DisplaySetting::first();  
        $setting->languages = ['ar', 'bn', 'en', 'vi'];
        $setting->title     = $appSetting->title;
        $setting->timezone  = $appSetting->timezone;
        date_default_timezone_set(session('app.timezone')?session('app.timezone'):$setting->timezone);

        $setting->display = request()->get('type')?request()->get('type'):$setting->display;

        if ($setting->display==6)
        {
            $display = DisplayCustom::where('status', 1)
                ->where('id', request()->get('custom'))
                ->first();
            if (!$display) return trans('app.you_are_not_authorized');
            //department wise display
            return view('backend.common.display.display6', compact('setting', 'display'));
        }
        elseif ($setting->display==5)
        {
            //department wise display
            return view('backend.common.display.display5', compact('setting'));
        }
        elseif ($setting->display==4)
        {
            //department wise display
            return view('backend.common.display.display4', compact('setting'));
        }
        elseif ($setting->display==3)
        {
            //counter wise display 2
            return view('backend.common.display.display3', compact('setting'));
        }
        elseif ($setting->display==2)
        {
            //counter wise display 1
            return view('backend.common.display.display2', compact('setting'));
        }
        else
        {
            //general display - sequential 
            return view('backend.common.display.display', compact('setting'));
        }
    }

    public function display1(Request $request)
    {  
        $nowServing = [];
        $newToken   = [];
        $setting  = DisplaySetting::first(); 
        $appSetting = Setting::first();   
        date_default_timezone_set(session('app.timezone')?session('app.timezone'):$appSetting->timezone);

        $displays = DB::select("
            SELECT 
                token.token_no,
                token.updated_at,
                department.name AS department,
                counter.name AS counter,
                CONCAT_WS(' ', user.firstname, user.lastname) as officer
            FROM (
                    SELECT t.* 
                    FROM token t 
                    WHERE t.status = 0
                    ORDER BY t.is_vip DESC, t.id ASC 
                    LIMIT 8
                ) AS token
            LEFT JOIN
                department ON department.id = token.department_id
            LEFT JOIN 
                counter ON counter.id = token.counter_id
            LEFT JOIN 
                user ON user.id = token.user_id
            ORDER BY token.is_vip DESC, token.id ASC
        ");

        $loop = 1;
        $main = null;
 
        $size  = sizeof($displays)>0?sizeof($displays):1;
        $width = (($request->width-150-($size*13.5))/$size);
        $height = round(($request->height-160)/8);

        $result = "<div class=\"col-sm-4 row\">";
        foreach ($displays as $display):  
            if ($loop==1) 
            {
                $main = "<div class=\"col-sm-8\">
                    <div id=\"clock\" class=\"well text-center\" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";padding:25px 0;font-size:28px;margin-bottom:0\">".date("$setting->date_format $setting->time_format")."</div>
                    <div class=\"text-center \" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";font-size:32px;padding:5px;height:50px\">".trans('app.now_serving')."</div>
                    <div class=\"queue well text-center ".(($loop==1)?'active':null)." \" style=\"height:auto;padding:160px 0;margin:0;font-size:36px\"> 
                        <h1 class=\"token\" style=\"font-size:90px\">$display->token_no</h1>
                    </div>
                </div>";

                $nowServing = array(
                    'counter' => $display->counter,
                    'token'   => $display->token_no
                ); 

                $recall = (!empty($display->updated_at) && ((strtotime(date("Y-m-d H:i:s"))-strtotime($display->updated_at)) <= 15)); 
                if ($recall) 
                {
                    $data['status'] = true;
                    $newToken = $nowServing;
                } 
            } 
            else 
            {

                if ($loop==2)
                {
                    $result .= "<div class=\"col-sm-12\"> 
                        <div class=\"queue well text-center \" style=\"height:60px;padding:0;text-align:center;font-size:25px;line-height:60px;margin-bottom:2px;background:#222;color:#fff\">
                            <strong style=\" padding-left:15px; height:58px;float:left;\">".explode(' ', trans('app.waiting_1'))[0]."</strong>
                            <strong style=\"display:inline-block;\">".trans('app.token')."
                            </strong>
                        </div>
                    </div>";
                }

                $result .= "<div class=\"col-sm-12\"> 
                    <div class=\"queue well text-center \" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";min-height:75px;height:{$height}px;padding:0;margin:0 0 5px 0\">
                        <div style=\"width:80px;min-height:72px;height:100%;float:left;background:#222;color:#fff\"><h1>".($loop-1)."</h1></div>
                        <div class=\"text-center\" style=\"display:inline-block;padding:10px 0\">
                            <h1 class=\"token\"><b>$display->token_no</b></h1>
                        </div>
                    </div>
                </div>";
            }
        $loop++;
        endforeach;
        $result .= "</div>";
        $result .= $main;


        /*NOTIFICATION*/
        $viewToken = $request->get('view_token');
        // compare between $nowServing and $viewToken
        if (is_array($viewToken) && sizeof($viewToken)>0)
        {
            // get new_token
            if ($nowServing['token'] != $viewToken['token'])
            {
                $data['status'] = true;
                $newToken = $nowServing;
            }   
        } 

        $data['result']     = $result;
        $data['view_token'] = $nowServing;
        $data['new_token']  = $newToken;
        $data['interval']   = 10000;

        return Response::json($data);
    }

    public function display3(Request $request)
    { 
        $allTokens  = []; //all token
        $viewTokens = []; //all token form view
        $newTokens  = []; //new token
        $vTokens    = [];
        $cTokens    = [];

        $setting    = DisplaySetting::first(); 
        $appSetting = Setting::first();   
        date_default_timezone_set(session('app.timezone')?session('app.timezone'):$appSetting->timezone);
        $counters = DB::table('counter')
            ->where('status', 1)
            ->where(function($q) use($request) {
                if (!empty($request->counters)) {
                    $q->whereIn('id', explode(',', $request->counters));
                }
            })
            ->orderBy(DB::raw('LENGTH(name)'), 'ASC')
            ->orderBy('name', 'ASC')
            ->get();


        $token_list = array();
        foreach ($counters as $counter) 
        {
            $tokens = DB::select("
                SELECT 
                    token.token_no AS token,
                    department.name AS department,
                    counter.name AS counter,
                    token.note AS note,
                    token.client_mobile AS mobile,
                    token.updated_at,
                    CONCAT_WS(' ', user.firstname, user.lastname) as officer
                FROM (
                        SELECT t.* 
                        FROM token t 
                        WHERE 
                            t.status = 0 
                            AND t.counter_id = $counter->id
                        ORDER BY t.id ASC 
                        LIMIT 5
                    ) AS token
                LEFT JOIN
                    department ON department.id = token.department_id
                LEFT JOIN 
                    counter ON counter.id = token.counter_id
                LEFT JOIN 
                    user ON user.id = token.user_id
                ORDER BY token.is_vip ASC, token.id DESC
                LIMIT 5
            ");

            foreach ($tokens as $token) 
            { 
                $token_list[$token->counter][] = array(
                    'counter'    => $token->counter,
                    'token'      => $token->token,
                    'mobile'     => $token->mobile,
                    'department' => $token->department,
                    'officer'    => $token->officer,
                    'note'       => $token->note,
                    'updated_at' => $token->updated_at,
                ); 
            }
        }

        $size  = sizeof($token_list)>0?sizeof($token_list):1;    
        $width = (($request->width-150-($size*13.5))/$size);
        $height = (($request->height-200)/5);

        $html = "<div id=\"clock\" class=\"well text-center\" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#fff') .";padding:5px 0;margin:-20px 0 0 0;font-size:24px;\">".date("$setting->date_format $setting->time_format")."</div>
            <div class=\"queue-box queue-box-status\">
                <h4 class='deprt'>".trans('app.q_c')."</h4> 
                <div class=\"item text-center\">
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_4')." </div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_3')." </div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_2')."</div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_1')."</div>
                    <div class='queue2 active' style='height:{$height}px;'>".trans('app.now_serving')."</div>
                </div>
            </div>";


        foreach ($token_list as $key => $value):
            $html .= "<div class=\"queue-box queue-box-element\" style=\"width:{$width}px\">
                <h4 class='deprt'>".trans('app.counter')." $key</h4> 
                <div class=\"item text-center\">";

                $sl = 5;
                $x  = 1; 
                label:
                foreach ($value as $html2): 
                    if (sizeof($value) < $sl):
                        $html .=  "<div class='queue2 ' style='height:{$height}px;background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#cdcdcd') .";'>-----</div>";
                        $sl--;
                        goto label;
                    endif;
     
                    if ($x == $sl)
                    {
                        $allTokens[] = $html2;   
                    }


                    $html .=  "<div class=\"queue2 ".(($x==$sl)?'active':null)." \" style='height:{$height}px;background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#cdcdcd') .";'>";
                        foreach ($html2 as $key => $item):
                            if ($key=='token')
                            {
                                $html .=  "<h1 class=\"title\">$item</h1>";
                            }
                            else
                            {
                                if ($setting->show_note == "1" && $key=='note')
                                {
                                    $html .=  "<strong>".trans("app.note")."</strong>: <span>$item</span><br>";
                                }
                                if ($setting->sms_alert == "1" && $key=='mobile')
                                {
                                    $html .=  "<strong>".trans("app.mobile")."</strong>: <span>$item</span><br>";
                                }
                                if ($setting->show_department == "1" && $key=='department')
                                {
                                    $html .=  "<strong>".trans("app.department")."</strong>: <span>$item</span><br>";
                                }
                                if ($setting->show_officer == "1" && $key=='officer')
                                {
                                    $html .=  "<strong>".trans("app.officer")."</strong>: <span>$item</span><br>";
                                }
                            }
                        endforeach;
                        $html .=  "</div>";
                    $x++;
                endforeach;

                $html .=  "</div>";
            $html .=  "</div>";
        endforeach;


        /*NOTIFICATION*/
        $viewTokens = $request->get('view_token'); 
        // compare between view_token & all_token
        if (is_array($viewTokens) && sizeof($viewTokens)>0)
        { 
            // extract view token
            foreach($viewTokens as $t)
            {
                $vTokens[$t['counter']] = $t['token'];
            }  

            // extract controller/all token
            foreach ($allTokens as $t) 
            {  
                $recall = (!empty($t['updated_at']) && ((strtotime(date("Y-m-d H:i:s"))-strtotime($t['updated_at'])) <= 15));  

                if ($recall) 
                {
                    $data['status'] = true;
                    $newTokens[] = array(
                        'counter' => $t['counter'],
                        'token'   => $t['token']
                    ); 
                }

                $cTokens[$t['counter']] = $t['token'];
            }  

            //get new token
            $nts = array_diff($cTokens,$vTokens);
            if (sizeof($nts)>0)
            {
                foreach ($nts as $key => $value) 
                {
                    $newTokens[] = array(
                        'counter' => $key,
                        'token'   => $value
                    );
                }
                $data['status'] = true;
            }
        }
  
        $data['result'] = $html;
        $data['new_token'] = $newTokens;
        $data['all_token'] = $allTokens;
        $data['interval']   = 10000*(count($newTokens)?count($newTokens):1);

        return Response::json($data);
    } 

    public function display4(Request $request)
    { 
        $allTokens = []; //all token
        $viewTokens = []; //all token form view
        $newTokens = []; //new token
        $vTokens = [];
        $cTokens = [];

        $setting = DisplaySetting::first(); 
        $appSetting = Setting::first();   
        date_default_timezone_set(session('app.timezone')?session('app.timezone'):$appSetting->timezone);
        $departments = DB::table('department')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();


        $token_list = array(); 
        foreach ($departments as $department) 
        {
            $tokens = DB::select("
                SELECT 
                    token.token_no AS token,
                    token.client_mobile AS mobile,
                    token.note AS note,
                    token.updated_at,
                    department.name AS department,
                    counter.name AS counter,
                    CONCAT_WS(' ', user.firstname, user.lastname) as officer
                FROM (
                        SELECT t.* 
                        FROM token t 
                        WHERE 
                            t.status = 0 
                            AND t.department_id = $department->id
                        ORDER BY t.id ASC 
                        LIMIT 5
                    ) AS token
                LEFT JOIN
                    department ON department.id = token.department_id
                LEFT JOIN 
                    counter ON counter.id = token.counter_id
                LEFT JOIN 
                    user ON user.id = token.user_id
                ORDER BY token.is_vip ASC, token.id DESC
                LIMIT 5
            ");

            foreach ($tokens as $token) 
            {
                $token_list[$token->department][] = array(
                    'counter'    => $token->counter,
                    'token'      => $token->token,
                    'mobile'     => $token->mobile,
                    'department' => $token->department,
                    'officer'    => $token->officer,
                    'note'       => $token->note,
                    'updated_at' => $token->updated_at
                );  
            }    
        }  


        $size  = sizeof($token_list)>0?sizeof($token_list):1;
        $width = (($request->width-150-($size*13.5))/$size);
        $height = (($request->height-200)/5);
            
        $html = "<div id=\"clock\" class=\"well text-center\" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#fff') .";padding:5px 0;margin:-20px 0 0 0;font-size:24px;\">".date("$setting->date_format $setting->time_format")."</div>
            <div class=\"queue-box queue-box-status\">
                <h4 class='deprt'>".trans('app.q_c')."</h4> 
                <div class=\"item text-center\">
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_4')." </div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_3')." </div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_2')."</div>
                    <div class='queue2' style='height:{$height}px;'>".trans('app.waiting_1')."</div>
                    <div class='queue2 active' style='height:{$height}px;'>".trans('app.now_serving')."</div>
                </div>
            </div>";

        foreach ($token_list as $key => $value):
            $html .= "<div class=\"queue-box queue-box-element\" style=\"width:{$width}px\">
                <h4 class='deprt'>$key</h4> 
                <div class=\"item text-center\">";

                $sl = 5;
                $x  = 1; 
                label:
                foreach ($value as $html2):

                if (sizeof($value) < $sl):
                    $html .=  "<div class='queue2 ' style='height:{$height}px;background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#cdcdcd') .";'>-----</div>";
                    $sl--;
                    goto label;
                endif;
 
                if ($x == $sl)
                {
                    $allTokens[] = $html2;  
                }


                $html .=  "<div class=\"queue2 ".(($x==$sl)?'active':null)." \" style='height:{$height}px;background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";color:".(!empty($setting->color)?$setting->color:'#cdcdcd') .";'>";
                    foreach ($html2 as $key => $item):
                        if ($key=='token')
                        {
                            $html .=  "<h1 class=\"title\">$item</h1>";
                        }
                        else
                        {
                            if ($setting->show_note == "1" && $key=='note')
                            {
                                $html .=  "<strong>".trans("app.note")."</strong>: <span>$item</span><br>";
                            }
                            if ($setting->sms_alert == "1" && $key=='mobile')
                            {
                                $html .=  "<strong>".trans("app.mobile")."</strong>: <span>$item</span><br>";
                            }
                            if ($setting->show_department == "1" && $key=='department')
                            {
                                $html .=  "<strong>".trans("app.department")."</strong>: <span>$item</span><br>";
                            }
                            if ($setting->show_officer == "1" && $key=='officer')
                            {
                                $html .=  "<strong>".trans("app.officer")."</strong>: <span>$item</span><br>";
                            }
                        }
                    endforeach;
                    $html .=  "</div>";
                $x++;
                endforeach;

                $html .=  "</div>";
            $html .=  "</div>";
        endforeach;


        /*NOTIFICATION*/
        $viewTokens = $request->get('view_token'); 
        // compare between view_token & all_token
        if (is_array($viewTokens) && sizeof($viewTokens)>0)
        { 
            // extract view token
            foreach($viewTokens as $t)
            {
                $vTokens[$t['counter']] = $t['token'];
            }  

            // extract controller/all token
            foreach ($allTokens as $t) 
            {
                $recall = (!empty($t['updated_at']) && ((strtotime(date("Y-m-d H:i:s"))-strtotime($t['updated_at'])) <= 15));  

                if ($recall) 
                {
                    $data['status'] = true;
                    $newTokens[] = array(
                        'counter' => $t['counter'],
                        'token'   => $t['token']
                    ); 
                }
                $cTokens[$t['counter']] = $t['token'];
            }  

            //get new token
            $nts = array_diff($cTokens,$vTokens);
            if (sizeof($nts)>0)
            {
                foreach ($nts as $key => $value) 
                {
                    $newTokens[] = array(
                        'counter' => $key,
                        'token'   => $value
                    );
                }
                $data['status'] = true;
            }
        }
 
        $data['result']    = $html;
        $data['new_token'] = $newTokens;
        $data['all_token'] = $allTokens;
        $data['interval']  = 10000*(count($newTokens)?count($newTokens):1);

        return Response::json($data);
    } 
    
    // counter wise queue
    public function display5(Request $request)
    {  
        $allTokens = []; //all token
        $viewTokens = []; //all token form view
        $vTokens = [];
        $cTokens = [];
        $newTokens = []; //new token

        $setting = DisplaySetting::first();
        $appSetting = Setting::first();   
        date_default_timezone_set(session('app.timezone')?session('app.timezone'):$appSetting->timezone);
        $counters = DB::table('counter')
            ->where('status', 1)
            ->orderBy(DB::raw('LENGTH(name)'), 'ASC')
            ->orderBy('name', 'ASC')
            ->get();

        $html = "<div class=\"col-sm-12\">
            <div id=\"clock\" class=\"well text-center\" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";padding:10px 0;font-size:28px;margin-bottom: 10px;\">".date("$setting->date_format $setting->time_format")."</div> 
            </div>
            <div class=\"row\">";

        $count = 1;
        $size = sizeof($counters);
        foreach ($counters as $counter) 
        { 
            $token = DB::table("token AS t")
                ->select(
                    "t.token_no AS token",
                    "d.name AS department",
                    "c.name AS counter",
                    DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                    "t.updated_at",
                    "t.status",
                    "t.sms_status" 
                )
                ->leftJoin("department AS d", "d.id", "=", "t.department_id")
                ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
                ->leftJoin("user AS o", "o.id", "=", "t.user_id")
                ->where("t.counter_id", $counter->id)
                ->where("t.status", "0")
                ->orderBy('t.is_vip', 'DESC')
                ->orderBy('t.id', 'ASC')
                ->first();

            // Add Header
            if ($count==1)
            {
               $html .= "<div class=\"col-sm-6\">
                <div class=\"col-sm-12\"> 
                    <div class=\"queue well text-center \" style=\"height:60px;padding:0;text-align:center;font-size:25px;line-height:60px;margin-bottom:2px;background:#222;color:#fff\">
                        <strong style=\"width:50%;height:58px;float:left;\">".trans('app.counter')."</strong>
                        <strong style=\"display:inline-block;\">".trans('app.token')."
                        </strong>
                    </div>
                </div>";
            } 
            else if ($count == 9)
            {
               $html .= "</div>
                <div class=\"col-sm-6\">
                <div class=\"col-sm-12\"> 
                    <div class=\"queue well text-center \" style=\"height:60px;padding:0;text-align:center;font-size:25px;line-height:60px;margin-bottom:2px;background:#222;color:#fff\">
                        <strong style=\"width:50%;height:58px;float:left;\">".trans('app.counter')."</strong>
                        <strong style=\"display:inline-block;\">".trans('app.token')."
                        </strong>
                    </div>
                </div>";
            }
            else if($count == $size+1)
            { 
               $html .= "</div>";
            }

            // Show Token 
            $html .= "<div class=\"col-sm-12\"> 
                <div class=\"queue well text-center \" style=\"background-color:".(!empty($setting->background_color)?$setting->background_color:'#cdcdcd') .";border-color:".(!empty($setting->border_color)?$setting->border_color:'#fff') .";height:60px;padding:0;text-align:center;font-size:25px;line-height:60px;margin-bottom:2px\">
                    <div style=\"width:50%;height:58px;float:left;background:#222;color:#fff\"><span>$counter->name</span></div>
                    <strong style=\"display:inline-block;\">".(!empty($token->token)?$token->token:'-----')."
                    </strong>
                </div>
            </div>"; 


            /*
            * ---------------------------------------
            */
            if (!empty($token->token))
            { 
                $allTokens[] = array(
                    'counter'    => $counter->name,
                    'token'      => $token->token,
                    'updated_at' => $token->updated_at
                );  
            } 

            /*
            * ---------------------------------------
            */

            $count++;
        }    
        $html .= "</div>";


        /*NOTIFICATION*/
        $viewTokens = $request->get('view_token'); 
        // compare between view_token & all_token
        if (is_array($viewTokens) && sizeof($viewTokens)>0)
        { 
            // extract view token
            foreach($viewTokens as $t)
            {
                $vTokens[$t['counter']] = $t['token'];
            }  

            // extract controller/all token
            foreach ($allTokens as $t) 
            {
                $recall = (!empty($t['updated_at']) && ((strtotime(date("Y-m-d H:i:s"))-strtotime($t['updated_at'])) <= 15));  

                if ($recall) 
                {
                    $data['status'] = true;
                    $newTokens[] = array(
                        'counter' => $t['counter'],
                        'token'   => $t['token']
                    ); 
                }
                $cTokens[$t['counter']] = $t['token'];
            }  

            //get new token
            $nts = array_diff($cTokens,$vTokens);
            if (sizeof($nts)>0)
            {
                foreach ($nts as $key => $value) 
                {
                    $newTokens[] = array(
                        'counter' => $key,
                        'token'   => $value
                    );
                }
                $data['status'] = true;
            }
        }
 
        $data['result']    = $html;
        $data['new_token'] = $newTokens;
        $data['all_token'] = $allTokens;
        $data['interval']  = 10000*(count($newTokens)?count($newTokens):1);

        return Response::json($data);
    }

}
