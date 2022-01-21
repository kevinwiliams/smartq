<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Image;

class SettingController extends Controller
{
    public function showForm()
    {
        $setting = Setting::first();
        if (empty($setting)) 
        {
            Setting::insert([
                'title'       => 'Demo title',
                'description' => null,
                'logo'        => null,
                'favicon'     => null,
                'email'       => null,
                'phone'       => null,
                'address'     => null,
                'copyright_text' => null,
                'direction'   => 'LTR',
                'language'    => 'en',
                'timezone'    => 'Dhaka/Asia' 
            ]);
        } 

        $timezoneList = $this->timezoneList();

    	return view('backend.admin.setting.setting', compact(
            'setting',
            'timezoneList'
        ));
    } 
 
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'id'          => 'required',
            'title'       => 'required|max:140',
            'description' => 'max:255',
            'logo'        => 'image|mimes:jpeg,png,jpg,gif|max:3072',
            'favicon'     => 'image|mimes:jpeg,png,jpg,gif|max:3072',
            'email'       => 'max:255',
            'phone'       => 'max:255',
            'address'     => 'max:255',
            'copyright_text' => 'max:255',
            'lang'        => 'max:3',
            'timezone'    => 'required|max:100' 
        ])
        ->setAttributeNames(array(
           'title' => trans('app.title'),
           'description' => trans('app.description'),
           'logo' => trans('app.logo'),
           'favicon' => trans('app.favicon'),
           'email' => trans('app.email'),
           'phone' => trans('app.mobile'),
           'address' => trans('app.address'),
           'copyright_text' => trans('app.copyright_text'),
           'lang' => trans('app.lang'),
           'timezone' => trans('app.timezone'),
        )); 


        if ($validator->fails()) {
            return redirect('admin/setting')
                        ->withErrors($validator)
                        ->withInput();
        } 
        else 
        { 
            //Favicon
            if (!empty($request->favicon)) {
                $faviconPath = 'public/assets/img/icons/favicon.jpg';
                $favicon = $request->favicon;
                Image::make($favicon)->resize(65, 50)->save($faviconPath);
            } else if (!empty($request->old_favicon)) {
                $faviconPath = $request->old_favicon;
            } 

            // Logo
            if (!empty($request->logo)) {
                $logoPath = 'public/assets/img/icons/logo.jpg';
                $logo = $request->logo;
                Image::make($logo)->resize(250, 50)->save($logoPath);
            } else if (!empty($request->old_logo)) {
                $logoPath = $request->old_logo;
            } 

            if (!empty($request->id)) {
                //update data
                $update = Setting::where('id',$request->id)
                    ->update([
                        'id'          => $request->id,
                        'title'       => $request->title,
                        'description' => $request->description,
                        'favicon'     => $faviconPath,
                        'logo'        => $logoPath,
                        'email'       => $request->email,
                        'phone'       => $request->phone,
                        'address'     => $request->address,
                        'copyright_text' => $request->copyright_text, 
                        'language'    => $request->lang, 
                        'timezone'    => $request->timezone 
                    ]);

                if ($update) 
                {
                    
                    $app = Setting::first();
                    \Session::put('locale', $request->lang);
                    \Session::put('app', array(
                        'title'   => $app->title, 
                        'favicon' => $app->favicon, 
                        'logo'    => $app->logo, 
                        'timezone' => $request->timezone,
                        'copyright_text' => $app->copyright_text, 
                    ));

                    return back()
                            ->with('message', trans('app.update_successfully'));
                } 
                else 
                {
                    return back()
                            ->with('exception', trans('app.please_try_again'));
                } 
            } 
        }
    }

    public function timezoneList()
    {
        return array(
            'Pacific/Midway'       => "(GMT-11:00) Midway Island",
            'US/Samoa'             => "(GMT-11:00) Samoa",
            'US/Hawaii'            => "(GMT-10:00) Hawaii",
            'US/Alaska'            => "(GMT-09:00) Alaska",
            'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana'      => "(GMT-08:00) Tijuana",
            'US/Arizona'           => "(GMT-07:00) Arizona",
            'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
            'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
            'America/Mexico_City'  => "(GMT-06:00) Mexico City",
            'America/Monterrey'    => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
            'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
            'America/Bogota'       => "(GMT-05:00) Bogota",
            'America/Lima'         => "(GMT-05:00) Lima",
            'America/Caracas'      => "(GMT-04:30) Caracas",
            'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz'       => "(GMT-04:00) La Paz",
            'America/Santiago'     => "(GMT-04:00) Santiago",
            'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            'Greenland'            => "(GMT-03:00) Greenland",
            'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
            'Atlantic/Azores'      => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca'    => "(GMT) Casablanca",
            'Europe/Dublin'        => "(GMT) Dublin",
            'Europe/Lisbon'        => "(GMT) Lisbon",
            'Europe/London'        => "(GMT) London",
            'Africa/Monrovia'      => "(GMT) Monrovia",
            'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
            'Europe/Berlin'        => "(GMT+01:00) Berlin",
            'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
            'Europe/Brussels'      => "(GMT+01:00) Brussels",
            'Europe/Budapest'      => "(GMT+01:00) Budapest",
            'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
            'Europe/Madrid'        => "(GMT+01:00) Madrid",
            'Europe/Paris'         => "(GMT+01:00) Paris",
            'Europe/Prague'        => "(GMT+01:00) Prague",
            'Europe/Rome'          => "(GMT+01:00) Rome",
            'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
            'Europe/Skopje'        => "(GMT+01:00) Skopje",
            'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
            'Europe/Vienna'        => "(GMT+01:00) Vienna",
            'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
            'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
            'Europe/Athens'        => "(GMT+02:00) Athens",
            'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
            'Africa/Cairo'         => "(GMT+02:00) Cairo",
            'Africa/Harare'        => "(GMT+02:00) Harare",
            'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
            'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
            'Europe/Kiev'          => "(GMT+02:00) Kyiv",
            'Europe/Minsk'         => "(GMT+02:00) Minsk",
            'Europe/Riga'          => "(GMT+02:00) Riga",
            'Europe/Sofia'         => "(GMT+02:00) Sofia",
            'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
            'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
            'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
            'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
            'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
            'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
            'Europe/Moscow'        => "(GMT+03:00) Moscow",
            'Asia/Tehran'          => "(GMT+03:30) Tehran",
            'Asia/Baku'            => "(GMT+04:00) Baku",
            'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
            'Asia/Muscat'          => "(GMT+04:00) Muscat",
            'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
            'Asia/Kabul'           => "(GMT+04:30) Kabul",
            'Asia/Karachi'         => "(GMT+05:00) Karachi",
            'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
            'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty'          => "(GMT+06:00) Almaty",
            'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
            'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth'      => "(GMT+08:00) Perth",
            'Asia/Singapore'       => "(GMT+08:00) Singapore",
            'Asia/Taipei'          => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
            'Asia/Seoul'           => "(GMT+09:00) Seoul",
            'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
            'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
            'Australia/Darwin'     => "(GMT+09:30) Darwin",
            'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
            'Australia/Canberra'   => "(GMT+10:00) Canberra",
            'Pacific/Guam'         => "(GMT+10:00) Guam",
            'Australia/Hobart'     => "(GMT+10:00) Hobart",
            'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney'     => "(GMT+10:00) Sydney",
            'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
            'Asia/Magadan'         => "(GMT+12:00) Magadan",
            'Pacific/Auckland'     => "(GMT+12:00) Auckland",
            'Pacific/Fiji'         => "(GMT+12:00) Fiji",
        );
    } 
}
