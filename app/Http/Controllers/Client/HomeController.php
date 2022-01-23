<?php
namespace App\Http\Controllers\Client;

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
    
        return view('backend.client.home.home');
    } 
}
