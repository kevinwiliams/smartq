<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function home()
    { 
        @date_default_timezone_set(session('app.timezone'));
        
        $infobox = $this->infobox();
        $performance = $this->userPerformance();
        $month = $this->chart_month();
        $year = $this->chart_year();
        $begin = $this->chart_begin();
        return view('backend.admin.home.home', compact(
            'infobox', 
            'performance', 
            'month', 
            'year', 
            'begin'
        ));
    }

    public function infobox()
    {
        $infobox = (object)array();
        $infobox->department = DB::table("department")->count();
        $infobox->counter = DB::table("counter")->count();
        $infobox->user  = DB::table("user")->count();
        $infobox->token = DB::table("token")
            ->select(DB::raw("
                COUNT(CASE WHEN status = '0' THEN id END) AS pending,
                COUNT(CASE WHEN status = '1' THEN id END) AS complete,
                COUNT(CASE WHEN status = '2' THEN id END) AS stop,
                COUNT(id) AS total
            "))
            ->first();

        return $infobox;
    }

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
            ->whereIn('u.user_type', [1])
            ->groupBy("u.id")
            ->get(); 
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
                MONTH(created_at) >= MONTH(CURRENT_DATE()) 
            GROUP BY 
                DATE(t.created_at)
            ORDER BY 
                t.created_at ASC
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
                YEAR(created_at) >= YEAR(CURRENT_DATE()) 
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
        "));
    }

}
