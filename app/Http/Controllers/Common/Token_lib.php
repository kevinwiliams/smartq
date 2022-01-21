<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use App\Models\Token;
use App\Models\Department;
use App\Models\Counter;

class Token_lib extends Controller
{ 
    /*-----------------------------------
    | TOKEN SERIAL MANAGER
    |-----------------------------------*/

    public function newToken($department_id = null, $counter_id = null, $is_vip = null)
    {  
        @date_default_timezone_set(session('app.timezone')); 

        $lastToken = Token::whereDate('created_at', date("Y-m-d"))
            ->where(function($query) use($department_id, $counter_id, $is_vip) {
                $query->where('department_id', $department_id)
                    ->where('counter_id', $counter_id);

                if (!empty($is_vip))
                {
                    $query->where('is_vip', 1);
                }
                else
                {
                    $query->where('is_vip', NULL)
                        ->orWhere('is_vip', '')
                        ->orWhere('is_vip', 0);
                }
            })
            ->orderBy('token_no','desc')
            ->value('token_no');
	 
		$prefixVIP        = (!empty($is_vip)?"V":"");
        $department       = Department::where('id', $department_id)->value('key');
        $prefixDepartment = ucfirst(mb_substr($department, 0, 1));
        $counter          = Counter::where('id', $counter_id)->value('name');
    	$prefixCounter    = mb_substr($counter, 0, 1);

        if (empty($lastToken))
        {
            $token = $prefixVIP.$prefixDepartment.$prefixCounter.'000';
        }
        else
        {
        	if (empty($is_vip))
        	{
	            $prefix = mb_substr($lastToken, 0, 1).mb_substr($lastToken, 1, 1);
	            $number = mb_substr($lastToken, 2, 1).mb_substr($lastToken, 3, 1).mb_substr($lastToken, 4, 1);
        	}
        	else
        	{
	            $prefix = mb_substr($lastToken, 0, 1).mb_substr($lastToken, 1, 1).mb_substr($lastToken, 2, 1);
	            $number = mb_substr($lastToken, 3, 1).mb_substr($lastToken, 4, 1).mb_substr($lastToken, 5, 1);
        	}

            if ($number < 999) 
            {
                $token = $prefix.sprintf("%03d", $number+1);
            } 
            else 
            {
                $token = $prefix.'000';
            }
        } 

        return $token;   
    } 

}