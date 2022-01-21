<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Session;

class LanguageController extends Controller
{   
    public function index(Request $request){
	    \Session::put('locale', $request->locale);
    }
}
