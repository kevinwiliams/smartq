<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\DisplaySetting; 
use App\Models\DisplayCustom; 
use App\Models\Counter; 
use Carbon\Carbon; 
use DB, Response, File, Validator;


class DisplayController extends Controller
{ 

    public function showForm()
    { 
        $setting  = DisplaySetting::first(); 
        $counters = Counter::where('status', 1)->pluck('name', 'id'); 
        $customDisplays = DisplayCustom::get();  

        if (empty($setting)) 
        {
            $insert = DisplaySetting::insert([
                'message'      => "Token - Queue Management System",
                'color'        => "#3c8dbc",
                'background_color' => "#e0f7ff",
                'border_color' => "#3c8dbc",
                'direction'    => "left",
                'time_format'  => "H:i:s",
                'date_format'  => "d M, Y",
                'display'      => '1',
                'sms_alert'    => '1',
                'show_note'    => '0',
                'keyboard_mode' => '0',
                'alert_position' => '3',
            ]);
        }
 
        return view('backend.admin.display.setting', compact(
            'setting',
            'counters',
            'customDisplays'
        ));
    } 

    public function setting(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'id'          => 'required',
            'color'       => 'max:20',
            'background_color' => 'max:20',
            'border_color' => 'max:20',
            'direction'   => 'max:10',
            'time_format' => 'max:20',
            'date_format' => 'max:20',
            'keyboard_mode' => 'max:1',
            'display'     => 'required|numeric', 
            'sms_alert'   => 'required|numeric', 
            'show_officer'    => 'required|numeric', 
            'show_department' => 'required|numeric', 
            'show_note'       => 'max:1', 
            'alert_position'  => 'required|numeric|min:1|max:99' 
        ])
        ->setAttributeNames(array(
           'color' => trans('app.color'),
           'background_color' => trans('app.background_color'),
           'border_color' => trans('app.border_color'),
           'direction' => trans('app.direction'),
           'time_format' => trans('app.time_format'),
           'date_format' => trans('app.date_format'),
           'display' => trans('app.display'),
           'keyboard_mode' => trans('app.keyboard_mode'),
           'sms_alert' => trans('app.sms_alert'),
           'show_officer' => trans('app.show_officer'),
           'show_department' => trans('app.show_department'),
           'show_note' => trans('app.show_note'),
           'alert_position' => trans('app.alert_position') 
        ));


        if ($validator->fails()) 
        {
            return redirect('admin/display/setting')
                        ->withErrors($validator)
                        ->withInput();
        } 
        else 
        { 

            if (!empty($request->id)) 
            {
                //update data
                $update = DisplaySetting::where('id',$request->id)
                    ->update([
                        'id'           => $request->id,
                        'message'      => $request->message,
                        'color'        => $request->color,
                        'background_color' => $request->background_color,
                        'border_color' => $request->border_color,
                        'direction'    => $request->direction,
                        'time_format'  => $request->time_format,
                        'date_format'  => $request->date_format,
                        'display'      => $request->display,
                        'keyboard_mode' => $request->keyboard_mode,
                        'sms_alert'    => $request->sms_alert,
                        'show_officer'  => $request->show_officer,
                        'show_department' => $request->show_department,
                        'show_note'       => $request->show_note,
                        'alert_position'  => $request->alert_position 
                    ]);

                if ($update) {
                    return back()
                            ->with('message', trans('app.update_successfully'));
                } else {
                    return back()
                            ->with('exception', trans('app.please_try_again'));
                } 
            }  
        }
    }

    public function getCustom(Request $request)
    {
        $data   = [];
        $result = DisplayCustom::find($request->id);
        if ($result)
        {
            $data = [
                'status'  => true,
                'message' => 'Data found!',
                'data'    => $result
            ];
        }
        else
        {
            $data = [
                'status'  => false,
                'message' => 'Data not found!',
                'data'    => null
            ];
        } 
        return response()->json($data);
    }

    public function custom(Request $request)
    {   
        $id = $request->id;
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|min:1|max:128|unique:display_custom,name,'.$id,    
            'description' => 'max:512',  
            'counters'    => 'required|max:64',
            'status'      => 'required|max:1' 
        ])
        ->setAttributeNames(array(
           'name'        => trans('app.name'),  
           'description' => trans('app.description'),   
           'counters'    => trans('app.counters'), 
           'status'      => trans('app.status'), 
        )); 
   
        if ($validator->fails()) 
        {
            $resError = [];
            foreach ($validator->errors()->messages() as $key => $value) 
            {
                $resError[$key] = $value[0];
            }

            return response([
                'status'  => false,
                'message' => trans('app.validation_failed'),
                'data'    => $resError
            ]);
        } 
        else 
        {    

            $postData = [
               'name'        => $request->name, 
               'description' => $request->description, 
               'counters'    => implode(',', $request->counters),  
               'status'      => $request->status, 
            ];

            if (!empty($id))
            {
                $store = DisplayCustom::where('id', $id)->update($postData);
            }
            else
            {
                $store = DisplayCustom::insert($postData);
            }

            if ($store) 
            {    

                $customDisplays = DisplayCustom::where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id');
                if (!empty($customDisplays))
                {
                    \Session::put('custom_displays', $customDisplays); 
                }

                return response([
                    'status'  => true,
                    'message' => !empty($id)?trans('app.update_successfully'):trans('app.save_successfully'),
                    'data'    => ""
                ]);  
            } 
            else 
            {
                return response([
                    'status'  => false,
                    'message' => trans('app.please_try_again'),
                    'data'    => ''
                ]); 
            } 
        }
    }

}
