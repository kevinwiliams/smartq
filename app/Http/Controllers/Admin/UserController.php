<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Http\Requests; 
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Token;
use App\Models\Department; 
use DB, Hash, Image, Validator;

class UserController extends Controller
{ 
	public function index()
	{   
        $departments = Department::where('status', 1)
            ->pluck('name', 'id');

    	return view('backend.admin.user.list', compact('departments'));
	}


    public function userData(Request $request) 
    {
        $columns = [
            0 => 'id',
            1 => 'photo',
            2 => 'user_type',
            3 => 'firstname',
            4 => 'email',
            5 => 'department_id',
            6 => 'mobile',
            7 => 'created_at',
            8 => 'updated_at',
            9 => 'status',
            10 => 'id',
        ];
  
        $totalData = User::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length'); 
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $users = User::offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->get();
        }
        else 
        { 
            $usersProccess = User::where(function($query)  use($search) {
                if (!empty($search['status'])) {
                    $query->where('status', '=', $search['status']);
                }

                if (!empty($search['user_type'])) {
                    $query->where('user_type', '=', $search['user_type']);
                }

                if (!empty($search['department'])) {
                    $query->where('department_id', '=', $search['department']);
                }

                if (!empty($search['start_date']) && !empty($search['end_date'])) {
                    $query->whereBetween("created_at",[
                        date('Y-m-d', strtotime($search['start_date']))." 00:00:00", 
                        date('Y-m-d', strtotime($search['end_date']))." 23:59:59"
                    ]);
                }

                if (!empty($search['value'])) {
                    $query->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE',"%{$search['value']}%")
                        ->orWhere('email', 'LIKE',"%{$search['value']}%")
                        ->orWhere('mobile', 'LIKE',"%{$search['value']}%")
                        ->orWhere(function($query)  use($search) {
                            $date = date('Y-m-d', strtotime($search['value']));
                            $query->whereDate('created_at', 'LIKE',"%{$date}%");
                        }); 
                }
            });
            
            $totalFiltered = $usersProccess->count();
            $users = $usersProccess->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 

        }

        $data = array();
        if(!empty($users))
        {
            $loop = 1;
            foreach ($users as $user)
            {  
                $data[] = [
                    'serial'     => $loop++,
                    'photo'      => '<img src="'.asset((!empty($user->photo)?$user->photo:'public/assets/img/icons/no_user.jpg')).'" alt="" width="64">',
                    'user_type'  => auth()->user()->roles($user->user_type),
                    'name'       => $user->firstname. ' ' . $user->lastname,
                    'email'      => $user->email,
                    'department' => (!empty($user->department)?$user->department->name:null),
                    'mobile'     => $user->mobile,
                    'created_at' => (!empty($user->created_at)?date('j M Y h:i a',strtotime($user->created_at)):null),
                    'updated_at' => (!empty($user->updated_at)?date('j M Y h:i a',strtotime($user->updated_at)):null),

                    'status'     => (($user->status==1)?"<span class='label label-success'>".trans('app.active')."</span>":"<span class='label label-danger'>".trans('app.deactive')."</span>"),

                    'options'    => "<div class=\"btn-group\">
                        <a href='".url("admin/user/view/$user->id")."' class=\"btn btn-sm btn-info\"><i class=\"fa fa-eye\"></i></a>". 
                        (
                            ($user->user_type != 5)?
                            "<a href='".url("admin/user/edit/$user->id")."' class=\"btn btn-sm btn-success\"><i class=\"fa fa-edit\"></i></a>
                            <a href='".url("admin/user/delete/$user->id")."' onclick=\"return confirm('".trans('app.are_you_sure')."')\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-times\"></i></a>":""
                        ).
                        "</div>" 
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


    public function showForm()
    {
        $departmentList = Department::where('status', 1)
            ->pluck('name','id'); 
    	return view('backend.admin.user.form', compact('departmentList'));
    }
    
 
    public function create(Request $request)
    { 
        @date_default_timezone_set(session('app.timezone'));
        
        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|unique:user,email|max:50',
            'firstname'   => 'required|max:25',
            'lastname'    => 'required|max:25',
            'password'    => 'required|max:50',
            'conf_password' => 'required|max:50|same:password',
            'department_id' => 'max:11',
            'mobile'      => 'required|max:20',
            'user_type'   => 'required|digits:1|max:1',
            'photo'       => 'image|mimes:jpeg,png,jpg,gif|max:3072',
            'status'      => 'required|digits:1|max:1' 
        ])
        ->setAttributeNames(array(
           'email' => trans('app.email'),
           'firstname' => trans('app.firstname'),
           'lastname' => trans('app.lastname'),
           'password' => trans('app.password'),
           'conf_password' => trans('app.conf_password'),
           'department_id' => trans('app.department_id'),
           'mobile' => trans('app.mobile'),
           'user_type' => trans('app.user_type'),
           'photo' => trans('app.photo'),
           'status' => trans('app.status') 
        ));   

        $filePath = null;
        if (!empty($request->photo)) {
            $filePath = 'public/assets/img/users/'. date('ymdhis') .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } else {
            $filePath = $request->old_photo;
        } 

        if ($validator->fails()) {
            return redirect('admin/user/create')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('photo', $filePath);
        } else { 
 
        	$save = User::insert([ 
                'firstname'   => $request->firstname,
                'lastname'    => $request->lastname,
                'email'       => $request->email,
                'password'    => Hash::make($request->conf_password), 
                'department_id' => (!empty($request->department_id)?$request->department_id:null),
                'mobile'      => $request->mobile,
                'user_type'   => $request->user_type,
                'photo'       => $filePath,
                'created_at'  => date('Y-m-d'),
                'status'      => $request->status,
        	]);

        	if ($save) {
	            return back()
                    ->withInput()  
                    ->with('photo', $filePath)
                    ->with('message',trans('app.save_successfully'));
        	} else {
	            return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath)
                    ->with('exception', trans('app.please_try_again'));
        	}

        }
    }

    public function view($id = null)
    {
        $user = User::select(
                'user.*', 
                'department.name as department'
            )->leftJoin('department', 'user.department_id', '=', 'department.id')
            ->where('user.id', $id)
            ->first(); 

        // assigned to me {as a officer}
        $assignedToMe = Token::where('user_id', $id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // created by me {as a admin/client/reciptionist}
        $generatedByMe = Token::where('created_by', $id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // my token { mobile number as client}
        $myToken = Token::where('client_mobile', $user->mobile)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status"); 

        return view('backend.admin.user.view', compact(
            'user',
            'assignedToMe',
            'generatedByMe',
            'myToken'
        ));
    }

 
    public function showEditForm($id = null)
    {
        $user = User::where('id', $id)
            ->whereNotIn('user_type', [5])
            ->first(); 

        $departmentList = Department::where('status', 1)->pluck('name','id'); 

        return view('backend.admin.user.edit',
            compact('departmentList','user'));
    }


    public function update(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));
        
        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|max:50|unique:user,email,'.$request->id,
            'firstname'   => 'required|max:25',
            'lastname'    => 'required|max:25',
            'password'    => 'required|max:50',
            'conf_password' => 'required|max:50|same:password',
            'department_id' => 'max:11',
            'mobile'      => 'required|max:20',
            'user_type'   => 'required|digits:1|max:1',
            'photo'       => 'image|mimes:jpeg,png,jpg,gif|max:3072',
            'status'      => 'required|digits:1|max:1'
        ])
        ->setAttributeNames(array(
           'email' => trans('app.email'),
           'firstname' => trans('app.firstname'),
           'lastname' => trans('app.lastname'),
           'password' => trans('app.password'),
           'conf_password' => trans('app.conf_password'),
           'department_id' => trans('app.department_id'),
           'mobile' => trans('app.mobile'),
           'user_type' => trans('app.user_type'),
           'photo' => trans('app.photo'),
           'status' => trans('app.status') 
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
            return redirect('admin/user/edit/'.$request->id)
                ->withErrors($validator)
                ->withInput()
                ->with('photo', $filePath);
        } else {  

            $update = User::where('id',$request->id)
                ->update([  
                    'firstname'   => $request->firstname,
                    'lastname'    => $request->lastname,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->conf_password),
                    'department_id' => (!empty($request->department_id)?$request->department_id:null),
                    'mobile'      => $request->mobile,
                    'user_type'   => $request->user_type,
                    'photo'       => $filePath,
                    'updated_at'  => date('Y-m-d'),
                    'status'      => $request->status,
                ]);


            if ($update) {
                return back()
                    ->withInput()  
                    ->with('photo', $filePath)
                    ->with('message', trans('app.update_successfully'));
            } else {
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath)
                    ->with('exception', trans('app.please_try_again'));
            }

        }
    }
 
    public function delete($id = null)
    {
        $delete = User::where('id', $id)
            ->whereNotIn('user_type', [5])
            ->delete();

        if ($delete) {
            return back()
                    ->with('message', trans('app.delete_successfully'));
        } else {
            return back()
                    ->with('exception', trans('app.please_try_again'));
        }
    }
 

}
