<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use App\Models\Message; 
use Session;

class NotificationController extends Controller
{  
    public function message()
    {
        return Message::where('receiver_id', auth()->user()->id)
            ->where('receiver_status', 0)
            ->count('id'); 
    } 
}
