<?php
namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Department;
use Auth, DB, Validator, Hash, Image;

class HomeController extends Controller
{

    public function home()
    { 
        @date_default_timezone_set(session('app.timezone'));
        
        $month = $this->chart_month();
        $year = $this->chart_year();
        $begin = $this->chart_begin();
        $performance = $this->userPerformance();
        return view('backend.officer.home.home', compact(
            'month', 
            'year', 
            'begin',
            'performance',
        ));
    } 
    
    //chart month wise token
    public function chart_month()
    {  
        return DB::select(DB::raw("
            SELECT 
                DATE_FORMAT(created_at, '%d') AS date,
                COUNT(CASE WHEN status = 1 THEN 1 END) as success,
                COUNT(CASE WHEN status = 0 THEN 1 END) as pending,
                COUNT(t.id) AS total
            FROM 
                token AS t
            WHERE 
                DATE(created_at) >= DATE_SUB(CURDATE(),INTERVAL 1 MONTH)
                AND 
                t.user_id = '". auth()->user()->id  ."'
            GROUP BY 
                DATE(created_at)
            ORDER BY 
                t.id ASC
        "));
    }

    //chart year wise token
    public function chart_year()
    {  
        return DB::select(DB::raw("
            SELECT 
                DATE_FORMAT(created_at, '%M') AS month,
                COUNT(CASE WHEN status = 1 THEN 1 END) as success,
                COUNT(CASE WHEN status = 0 THEN 1 END) as pending,
                COUNT(t.id) AS total
            FROM 
                token AS t
            WHERE 
                DATE(created_at) >= DATE_SUB(CURDATE(),INTERVAL 1 YEAR)
                AND 
                t.user_id = '". auth()->user()->id  ."'
            GROUP BY 
                month
            ORDER BY 
                t.created_at ASC
        "));
    }

    //chart year wise token
    public function chart_begin()
    {  
        return DB::select(DB::raw("
            SELECT 
                DATE(created_at) AS date,
                COUNT(CASE WHEN status = 1 THEN 1 END) as success,
                COUNT(CASE WHEN status = 0 THEN 1 END) as pending,
                COUNT(t.id) AS total
            FROM 
                token AS t   
            WHERE 
                t.user_id = '". auth()->user()->id  ."'
        "));
    }

    // user performance
    public function userPerformance()
    {
        return DB::table("user AS u")
            ->select(DB::raw("
                u.id,
                CONCAT_WS(' ', u.firstname, u.lastname) AS username,
                COUNT(CASE WHEN t.status='0' THEN t.id END) AS pending,
                COUNT(CASE WHEN t.status='1' THEN t.id END) AS complete,
                COUNT(CASE WHEN t.status='2' THEN t.id END) AS stop,
                COUNT(t.id) AS total 
            "))
            ->leftJoin("token AS t", function($join) {
                $join->on("t.user_id", "=", "u.id");
                $join->whereDate("t.created_at", "=", date("Y-m-d"));
            })
            ->where('u.id', auth()->user()->id )
            ->groupBy("u.id")
            ->first(); 
    } 

}
