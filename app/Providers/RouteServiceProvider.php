<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot(); 
        // it's console.
        if ( !app()->runningInConsole() )
        {  
            $this->verify();
        }   
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "extra" functionalities of application.
     *
     *
     * @return void
    */  
    private $domain;
    private $object; 
    private $code;    
    private $message     = null;

    public function verify()
    {
		//bugs
    }

    /*
    * c l i e n t - d o m a i n - n a m e 
    * c h e c k - a l l o w - d o m a i n
    * i f - a l l o w - d o m a i n - t h e n - i g n o r e - c h e c k i n g
    * r e t u r n - f a l s e - c h e c k - i t ' s - a - p u b i c
    * r e t u r n - t r u e   - n o - n e e d - t o - c h e c k
    */
    private function allowDomain()
    {
        $url = (isset($_SERVER["HTTPS"]) ? "https://" : "http://").((isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']))?$_SERVER["HTTP_HOST"]:'');
        $url .= str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]); 

        // s e t - d o m a i n - n a m e
        $this->domain = $url;

        $my_domain = preg_replace('/:[0-9]+/', '', $url);
        // r e g e x - c a n - b e - r e p l a c e d - w i t h - p a r s e - u r l
        preg_match("/^(https|http|ftp):\/\/(.*?)\//", "$my_domain/" , $matches);

        if (filter_var($matches[2], FILTER_VALIDATE_IP)) 
        {
            // i t s - a - i p 
            $my_domain = $matches[2];

            // c h e c k - i s - i t - p r i v a t e - i p - o r - n o t
            $pri_addrs = array (
              '10.0.0.0|10.255.255.255', // s i n g l e - c l a s s - a - n e t w o r k
              '172.16.0.0|172.31.255.255', // 1 6 - c o n t i g u o u s - c l a s s - B - n e t w o r k
              '192.168.0.0|192.168.255.255', // 2 5 6 - c o n t i g u o u s - c l a s s - C - n e t w o r k
              '169.254.0.0|169.254.255.255', // L i n k - l o c a l - a d d r e s s - a l s o r ef e r e d - t o - a s - A u t o m a t i c - P r i v a t e - I P - a d d r e s s i n g
              '127.0.0.0|127.255.255.255' // l o c a l h o s t
            );

            $long_ip = ip2long ($my_domain);
            if ($long_ip != -1) 
            {
                foreach ($pri_addrs AS $pri_addr) 
                {
                    list ($start, $end) = explode('|', $pri_addr);

                    // i f - p r i v a t e - i p
                    if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) 
                    {
                        return true;
                    }
                }
            }
        } 
        else 
        { 
            //i t s - a - d o m a i n
            $parts = explode(".", $matches[2]);
            $tld  = array_pop($parts);
            $host = array_pop($parts);
            if ( strlen($tld) == 2 && strlen($host) <= 3 ) 
            {
                $tld = "$host.$tld";
                $host = array_pop($parts);
            }
            $my_domain = "$host.$tld"; 

            if (in_array($tld, array('dev', 'test')))
            {
                return true;
            }
        }

        // c h e c k - i s - i t - a l l o w - d o m a i n 
        if (in_array($my_domain, ['127.0.0.1', '[::1]', 'localhost','.localhost']))
        {
            return true;
        }

        // add request path
        // $this->domain = "{$my_domain} [{$url}]";

        // p u b l i c - i p / r e a l - d o m a i n 
        return false;
    }

    /*
    * r e a d - a n d - c h e c k - s t r i n g
    * r e t u r n - t r u e   - e x i s t s - t o k e n 
    * r e t u r n - f a l s e - n o t - e x i s t s - t o k e n 
    */
    private function inspector($content = null, $file = './system.config')
    { 
        if (!empty($content))
        {
            file_put_contents($file, $content);
        }
        else if (file_exists($file))
        {
            $data = file_get_contents($file);
            if (!empty($data))
            { 
                $object = json_decode($data);
                if (is_object($object)) 
                {
                    foreach ($object as $key => $value) 
                    {
                        if (!in_array($key, array('token', 'date')) || empty($object->token))
                        {
                            return false;
                        } 
                    } 

                    $this->object = $object;
                    return true;
                } 
            } 
        }

        return false;  
    }

    /*
    * c h e c k - a p i 
    * r e t u r n - d a t a & w r i t e - i t - t o - l o c a l
    * r e t u r n - f a l s e - n o t h i n g - t o - d o
    */
    private function apiCheck() 
    {     
        if (!empty($this->domain) && (isset($this->object->token) || isset($this->code))) 
        {   
            $curlHandler = curl_init();
            curl_setopt_array($curlHandler, [
                CURLOPT_URL            => "https://admin.marquisvirgo.com/api/v2/licence",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
                CURLOPT_POSTFIELDS     => [
                    'id'     => "22029961",
                    'domain' => $this->domain,
                    'code'   => (!empty($_POST['_code'])?$_POST['_code']:$this->code),
                    'token'  => (!empty($this->object->token)?$this->object->token:null)
                ]
            ]);  

            $response = curl_exec($curlHandler); 

            if ($response === false) 
            {
                return false;
            }

            $code = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE); 
            if ($code >= 400) 
            {
                return false;
            }

            $result = json_decode( $response, true );
            if (!empty($result) && !empty($result['attempts']) && $result['attempts'] >= 10)
            {
                // s e t - a - s e s s i o n - a t t e m p t s
                $_SESSION['_attempts'] = $result['message'];
            }  

            return $result;
        }   

        return false;
    }


    private function notify() 
    { 
		$_SESSION['_inspector'] = true;
        $this->inspector;
    }

}

