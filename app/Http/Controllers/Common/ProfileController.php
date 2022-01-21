<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Department;
use Validator, Hash, Image;

class ProfileController extends Controller
{
 
    public function profile()
    { 
        $user = User::select(
                'user.*', 
                'department.name as department'
            )->leftJoin('department', 'user.department_id', '=', 'department.id')
            ->where('user.id', auth()->user()->id)
            ->first(); 

        // assigned to me {as a officer}
        $assignedToMe = Token::where('user_id', auth()->user()->id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // created by me {as a admin/client/reciptionist}
        $generatedByMe = Token::where('created_by', auth()->user()->id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // my token { mobile number as client}
        $myToken = Token::where('client_mobile', $user->mobile)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status"); 

        return view('backend.common.setting.profile', compact(            
            'user',
            'assignedToMe',
            'generatedByMe',
            'myToken'
        ));
    }
 
    public function profileEditShowForm()
    { 
        $user = User::where('id', auth()->user()->id )->first(); 
        $departmentList = Department::where('status', 1)->pluck('name','id'); 

        return view('backend.common.setting.profile_edit',
            compact('departmentList','user'));
    }

    public function updateProfile(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));

        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|max:50|unique:user,email,' . auth()->user()->id ,
            'firstname'   => 'required|max:25',
            'lastname'    => 'required|max:25',
            'password'    => 'required|max:50',
            'conf_password' => 'required|max:50|same:password',
            'department_id' => 'max:11',
            'mobile'      => 'required|max:20',
            'photo'       => 'image|mimes:jpeg,png,jpg,gif|max:3072',
        ])
        ->setAttributeNames(array(
           'email' => trans('app.email'),
           'firstname' => trans('app.firstname'),
           'lastname' => trans('app.lastname'),
           'password' => trans('app.password'),
           'conf_password' => trans('app.conf_password'),
           'department_id' => trans('app.department_id'),
           'mobile' => trans('app.mobile'),
           'photo' => trans('app.photo') 
        )); 

        $filePath = null;
        if (!empty($request->photo)) {
            $filePath = 'public/assets/img/users/'. date('ymdhis') .'.jpg';
            $photo = $request->photo;
            Image::make($photo)->resize(300, 200)->save($filePath);
        } else if (!empty($request->old_photo)) {
            $filePath = $request->old_photo;
        }  
 
        if ($validator->fails()) { 
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('photo', $filePath);
        } else {  

            $update = User::where('id', auth()->user()->id )
                ->update([  
                    'firstname'   => $request->firstname,
                    'lastname'    => $request->lastname,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->conf_password),
                    'mobile'      => $request->mobile,
                    'photo'       => $filePath,
                    'updated_at'  => date('Y-m-d'),
            ]);

            if ($update) { 
                return redirect()
                    ->back()
                    ->withInput()  
                    ->with('photo', $filePath)
                    ->with('message', trans('app.update_successfully'));
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath)
                    ->with('exception', trans('app.please_try_again'));
            }

        }
    }
}
