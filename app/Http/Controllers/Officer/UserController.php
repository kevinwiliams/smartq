<?php
namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Models\User;
use App\Models\Token;

class UserController extends Controller
{  
    public function view(Request $request)
    {
        $user = User::select(
                'user.*', 
                'department.name as department'
            )->leftJoin('department', 'user.department_id', '=', 'department.id')
            ->leftJoin('token', 'token.created_by', '=', 'user.id')
            ->where('token.created_by', $request->id)
            ->first(); 

        // assigned to me {as a officer}
        $assignedToMe = Token::where('user_id', $request->id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // created by me {as a admin/client/reciptionist}
        $generatedByMe = Token::where('created_by', $request->id)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status");

        // my token { mobile number as client}
        $myToken = Token::where('client_mobile', $user->mobile)
            ->selectRaw("COUNT(id) as total, status")
            ->groupBy('status')
            ->pluck("total", "status"); 

        return view('backend.officer.user.view', compact(
            'user',
            'assignedToMe',
            'generatedByMe',
            'myToken'
        ));
    }
}
