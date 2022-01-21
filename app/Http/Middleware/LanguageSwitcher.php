<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;
use DB;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next)
    {
        $locale1 = Session::get('locale');
        $locale2 = DB::table('setting')->first()->language;
        $locale3 = Config::get('app.locale');

        if (!empty($locale1)) {
            $locale = $locale1;
        } else if (!empty($locale2)) {
            $locale = $locale2;
        } else {
            $locale = $locale3;
        }

        App::setLocale($locale);
        return $next($request);
    }
}

 