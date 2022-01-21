<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Message; 
use Validator, DB, Response, Str;

class MessageController extends Controller
{ 
    public function show()
    {  
        $userList = User::select(DB::raw('CONCAT_WS(" ", firstname, lastname) AS name'),'id')
        	->whereNotIn('id', [auth()->user()->id ])
        	->where('status', 1)
        	->orderBy('name', 'asc')
            ->pluck('name','id');
 
    	return view('backend.common.message.new', compact('userList'));
    }

    public function send(Request $request)
    {
        @date_default_timezone_set(session('app.timezone'));
 
        $validator = Validator::make($request->all(), [
            'receiver' => 'required|max:11',
            'subject' => 'required|max:255',
            'message' => 'required|max:1000',
            'attachment' => 'max:64',
        ])
        ->setAttributeNames(array(
           'receiver' => trans('app.receiver'),
           'subject' => trans('app.subject'),
           'message' => trans('app.message'),
           'attachment' => trans('app.attachment') 
        )); 

        if ($validator->fails()) {
            return redirect()
    			->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $save = Message::insert([
                'sender_id'      => auth()->user()->id ,
                'receiver_id'    => $request->receiver,
                'subject'        => $request->subject,
                'message'        => $request->message,
                'attachment'    => $request->attachment,
                'datetime'  	 => date('Y-m-d H:i:s'),
                'sender_status'  => 0,
                'receiver_status' => 0,
            ]);

        	if ($save) {
	            return back()->withInput()
                        ->with('message', trans('app.message_sent'));
        	} else {
	            return back()->withInput()
                        ->with('exception',trans('app.please_try_again'));
        	}

        }
    }

	public function inbox()
    {  
    	return view('backend.common.message.inbox');
    }

    public function inboxData(Request $request) 
    {
        $columns = [
            0 => 'id',
            1 => 'photo',
            2 => 'sender_id',
            3 => 'subject',
            4 => 'message',
            5 => 'attachment',
            6 => 'receiver_status',
            7 => 'datetime',
            8 => 'id' 
        ];
  
        $totalData = Message::where('receiver_id', auth()->user()->id )
              ->whereNotIn('receiver_status', [2])
              ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $messages = Message::where('receiver_id', auth()->user()->id )
                 ->whereNotIn('receiver_status', [2])
                 ->offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->get();
        }
        else 
        { 
            $messageAfterFilter = Message::where('receiver_id', auth()->user()->id )
                ->whereNotIn('receiver_status', [2])
                ->where(function($query) use($search) {  

                    if (!empty($search['status'])) {
                        $query->where('receiver_status', '=', $search['status']);
                    }

                    if (!empty($search['value'])) {
                        $query->where('subject', 'LIKE', "%{$search['value']}%")
                              ->orWhere('message', 'LIKE', "%{$search['value']}%")
                              ->orWhereHas('sender', function($query) use($search) {
                                   $query->where('firstname', 'LIKE', "%{$search['value']}%");
                                   $query->orWhere('lastname', 'LIKE', "%{$search['value']}%");
                              }) 
                              ->orWhere(function($query)  use($search) {
                                   $date = date('Y-m-d', strtotime($search['value']));
                                   $query->whereDate('datetime', 'LIKE',"%{$date}%");
                              });
                    } 
                });

            $totalFiltered = $messageAfterFilter->count();

            $messages = $messageAfterFilter->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 
        }

        $data = array();
        if(!empty($messages))
        {
            $loop = 1;
            foreach ($messages as $message)
            $data[] = [
                'serial'     => $loop++, 
                'photo'      => '<img src="'.asset((!empty($message->sender->photo)?$message->sender->photo:'public/assets/img/icons/no_user.jpg')).'" alt="" width="64">',
                'sender'     => !empty($message->sender)?($message->sender->firstname. ' '.$message->sender->lastname . '<br/><i class="label label-success">'. auth()->user()->roles($message->sender->user_type).'</i>'):null,
                'subject'    => $message->subject,
                'message'    => Str::limit($message->message, 500, '...'),
                'datetime'   => (!empty($message->datetime)?date('j M Y h:i a',strtotime($message->datetime)):null),
                'attachment' => (!empty($message->attachment)?'<i class="text-success fa fa-2x fa-check"></i>':'<i class="text-danger fa fa-2x fa-times"></i>'),

                'receiver_status' => (($message->receiver_status==0)?'<i class="label label-warning">'.trans('app.not_seen').'</i>':'<i class="label label-success">'.trans('app.seen').'</i>'),

                'options'    => "<div class=\"btn-group\"> 
                    <a href='".url("common/message/details/$message->id/inbox")."'  class=\"btn btn-sm btn-success\"><i class=\"fa fa-eye\"></i></a>

                    <a href='".url("common/message/delete/$message->id/inbox")."' onclick=\"return confirm('".trans('app.are_you_sure')."')\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-times\"></i></a>
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
 
	public function sent()
    {   
        $messages = Message::select('message.*', DB::raw('CONCAT_WS(" ", user.firstname, user.lastname) AS sendTo'))
        	->where('sender_id', auth()->user()->id )
        	->whereNotIn('sender_status', [2])
        	->leftJoin('user', 'user.id', '=', 'message.receiver_id')
            ->orderBy('id', 'DESC')
            ->paginate(25);

    	return view('backend.common.message.sent', compact('messages'));
    }

    public function sentData(Request $request) 
    { 
        $columns = [
            0 => 'id',
            1 => 'photo',
            2 => 'receiver_id',
            3 => 'subject',
            4 => 'message',
            5 => 'attachment',
            6 => 'sender_status',
            7 => 'datetime',
            8 => 'id' 
        ];
  
        $totalData = Message::where('sender_id', auth()->user()->id )
              ->whereNotIn('sender_status', [2])
              ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $messages = Message::where('sender_id', auth()->user()->id )
                 ->whereNotIn('sender_status', [2])
                 ->offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->get();
        }
        else 
        { 
            $messageAfterFilter = Message::where('sender_id', auth()->user()->id )
                ->whereNotIn('sender_status', [2])
                ->where(function($query) use($search) {  

                    if (!empty($search['status'])) {
                        $query->where('sender_status', '=', $search['status']);
                    }

                    if (!empty($search['value'])) {
                        $query->where('subject', 'LIKE', "%{$search['value']}%")
                              ->orWhere('message', 'LIKE', "%{$search['value']}%")
                              ->orWhereHas('receiver', function($query) use($search) {
                                   $query->where('firstname', 'LIKE', "%{$search['value']}%");
                                   $query->orWhere('lastname', 'LIKE', "%{$search['value']}%");
                              }) 
                              ->orWhere(function($query)  use($search) {
                                   $date = date('Y-m-d', strtotime($search['value']));
                                   $query->whereDate('datetime', 'LIKE',"%{$date}%");
                              });
                    } 
                });

            $totalFiltered = $messageAfterFilter->count();

            $messages = $messageAfterFilter->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 
        }

        $data = array();
        if(!empty($messages))
        {
            $loop = 1;
            foreach ($messages as $message)
            $data[] = [
                'serial'     => $loop++, 
                'photo'      => '<img src="'.asset((!empty($message->receiver->photo)?$message->receiver->photo:'public/assets/img/icons/no_user.jpg')).'" alt="" width="64">',
                'receiver'     => !empty($message->receiver)?($message->receiver->firstname. ' '.$message->receiver->lastname . '<br/><i class="label label-success">'. auth()->user()->roles($message->receiver->user_type).'</i>'):null,
                'subject'    => $message->subject,
                'message'    => Str::limit($message->message, 500, '...'),
                'datetime'   => (!empty($message->datetime)?date('j M Y h:i a',strtotime($message->datetime)):null),
                'attachment' => (!empty($message->attachment)?'<i class="text-success fa fa-2x fa-check"></i>':'<i class="text-danger fa fa-2x fa-times"></i>'),

                'sender_status' => (($message->sender_status==0)?'<i class="label label-warning">'.trans('app.not_seen').'</i>':'<i class="label label-success">'.trans('app.seen').'</i>'),

                'options'    => "<div class=\"btn-group\"> 
                    <a href='".url("common/message/details/$message->id/inbox")."'  class=\"btn btn-sm btn-success\"><i class=\"fa fa-eye\"></i></a>

                    <a href='".url("common/message/delete/$message->id/inbox")."' onclick=\"return confirm('".trans('app.are_you_sure')."')\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-times\"></i></a>
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

    public function details($id = null, $type = null)
    {  
        if ($type == "inbox") 
        {
            DB::table('message')
                ->where('id', $id)
                ->update(['receiver_status' => 1]);
        } 
        #-------------------------------# 
        $message = collect(\DB::select("
            SELECT 
                message.*,
                CONCAT_WS(' ', u1.firstname, u1.lastname) AS sender,
                CONCAT_WS(' ', u2.firstname, u2.lastname) AS receiver
            FROM message
            LEFT JOIN 
                user u1 ON message.sender_id = u1.id
            LEFT JOIN 
                user u2 ON message.receiver_id = u2.id
            WHERE message.id = $id
        "))->first();

        return view('backend.common.message.details', compact('message'));
    }


    public function delete($id = null, $type = null)
    {
        if ($type == 'inbox') 
        {
            DB::table('message')
                ->where('id', $id)
                ->update(['receiver_status' => 2]);    
                return redirect()->back()->with('message', trans('app.delete_successfully'));
        } 
        else if ($type == "sent") 
        {
            DB::table('message')
                ->where('id', $id)
                ->update(['sender_status' => 2]);    
                return redirect()->back()->with('message', trans('app.delete_successfully'));
        } 
        else 
        {
            return redirect()->back()->with('exception', trans('app.please_try_again'));
        } 
    }
 
   
    public function UploadFiles(Request $request)
    {
        $input = $request->all();
 
        $validation = Validator::make($input, array(
            'file' => 'mimes:docx,doc,pdf,jpg,png,jpeg|max:2048',
        ));
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 
        $destinationPath = 'public/assets/attachments/'; // upload path
        $extension = $request->file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
        $upload_success = $request->file('file')->move($destinationPath, $fileName); // uploading file to given path
        //file path
        $filePath = $destinationPath.$fileName;
 
        if ($upload_success) {
            return Response::json(['status' => 200, 'path'=>$filePath]);
        } else {
            return Response::json(['status' => 400]);
        }
    }

}
