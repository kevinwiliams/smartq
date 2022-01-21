<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Models\Counter;
use Validator, App;

class CounterController extends Controller
{
	public function index()
	{   
        $counters = Counter::get();
    	return view('backend.admin.counter.list', ['counters' => $counters]);
	}

    public function showForm()
    {
    	return view('backend.admin.counter.form');
    }
    
    public function create(Request $request)
    {     
        @date_default_timezone_set(session('app.timezone'));
        
        $validator = Validator::make($request->all(), [ 
            'description' => 'max:255',
            'status'      => 'required',
            'name'        => 'required|unique:counter,name|max:50',
        ])
        ->setAttributeNames(array(
           'name' => trans('app.name'),
           'description' => trans('app.description'),
           'status' => trans('app.status')
        ));

        if ($validator->fails()) {
            return redirect('admin/counter/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
 
            $save = Counter::insert([
                'name'        => $request->name,
                'description' => $request->description,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => null,
                'status'      => $request->status
            ]);

        	if ($save) {
	            return back()->withInput()
                        ->with('message', trans('app.save_successfully'));
        	} else {
	            return back()->withInput()
                        ->with('exception', trans('app.please_try_again'));
        	}

        }
    }
 
    public function showEditForm($id = null)
    {
        $counter = Counter::where('id', $id)->first();
        return view('backend.admin.counter.edit', compact('counter'));
    }
  
    public function update(Request $request)
    { 
        @date_default_timezone_set(session('app.timezone')); 

        $validator = Validator::make($request->all(), [ 
            'description' => 'max:255',
            'status'      => 'required',
            'name'        => 'required|max:50|unique:counter,name,'.$request->id,
        ])
        ->setAttributeNames(array(
           'name' => trans('app.name'),
           'description' => trans('app.description'),
           'status' => trans('app.status')
        ));

        if ($validator->fails()) {
            return redirect('admin/counter/edit/'.$request->id)
                        ->withErrors($validator)
                        ->withInput();
        } else {

            $update = Counter::where('id',$request->id)->update([
                    'name'        => $request->name,
                    'description' => $request->description,
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'status'      => $request->status
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
 
    public function delete($id = null)
    {
        $delete = Counter::where('id', $id)
            ->delete();
        return redirect('admin/counter')->with('message', trans('app.delete_successfully'));
    }  
}
