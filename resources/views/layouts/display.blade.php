<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ !empty($setting->title)?$setting->title:null }} :: @yield('title')</title>
        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset(Session::get('app.favicon')) }}" type="image/x-icon" />
        <!-- template bootstrap -->
        <link href="{{ asset('assets/css/template.min.css') }}" rel='stylesheet prefetch'>
        <!-- Custom fonts for this template-->
       <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel='stylesheet'>

       <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
       <!-- Custom styles for this template-->
       <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel='stylesheet'>
        <!-- custom style -->
        <link href="{{ asset('assets/css/style.css') }}" rel='stylesheet'>
         
        <!-- Jquery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    </head>
    <body class="cm-no-transition cm-1-navbar loader-process">
        <div class="loader">
           
        </div>
        <!-- Starts of Content -->
        @yield('content')
        <!-- Ends of Contents -->
        
        <!-- Page Script -->
        @stack('scripts')
        <script type="text/javascript">

        //preloader
        $(window).load(function() {
            //$(".loader").fadeOut("slow");;
            // disable preloader
        });

        // dispaly clock
        $(document).ready(function()
        { 
          var time_format = '{{ (!empty($setting->time_format)?$setting->time_format:"H:i:s") }}';
          var date_format = '{{ (!empty($setting->date_format)?$setting->date_format:"d M, Y") }}';

          setInterval(function()
          { 
            var d = new Date();

            //-----------CLOCK-----------------
            //12 hour time
            if (time_format == 'h:i:s A')
            { 
              var time = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12: true, timeZone:"{{ $setting->timezone }}"}); 
            }
            else
            { 
              var time = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12: false, timeZone:"{{ $setting->timezone }}"}); 
            }

            // ---------DATE--------------
            const monthFullNames = ["January", "February", "March", "April", "May", "June",
              "July", "August", "September", "October", "November", "December"
            ];  
            const monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
              "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];  
         
            if (date_format == 'd M, Y')
            {   
              var date = d.getDate()+ " " +(monthShortNames[d.getMonth()])+ ", "+d.getFullYear();
            } 
            else if (date_format == 'F j, Y')
            {
              var date = (monthFullNames[d.getMonth()])+" "+d.getDate()+ ", "+d.getFullYear();
            } 
            else if(date_format == 'm.d.y')
            {
              var date = ((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1))+ "."+(d.getDate()<10?"0"+d.getDate():d.getDate())+"."+(d.getFullYear().toString().substr(-2));
            } 
            else if(date_format == 'd/m/Y')
            {
              var date = (d.getDate()<10?"0"+d.getDate():d.getDate())+"/"+((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1))+ "/"+(d.getFullYear().toString());
            } 

            $("body #clock").html(date+" "+time);
          },1000);
        });

        // full screen
        // -----------------------------------------------------
        function goFullscreen(id) 
        {
          var element = document.getElementById(id);
          if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
          } else if (element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
          }  
        } 
        
        /*
        *-----------------------------------------------------
        * NOTIFICATION SOUNDS
        *-----------------------------------------------------
        */ 
        class Notification 
        {
          url      = ``;
          lang     = `en`;
          start    = ``;
          counter  = ``;
          token    = ``;
          sounds   = [];
          callCounter   = true;
          callToken     = true;
          static status = true;

          // call with multiple object
          call(objects = [], lang = null, url = null, callCounter=true, callToken=true) {
          
            if (Notification.status === true) {
              // reset url 
              if (url != null || url != '') {
                this.url = url;
              } 
              if (lang != null || lang != '') {
                this.lang = lang;
              }
              if (callCounter != null || callCounter != '') {
                this.callCounter = callCounter;
              }
              this.url       = this.url+`/assets/sounds/`;
              this.start     = this.url+`/start.mp3`;
              this.counter   = this.url+`/`+this.lang+`/counter.mp3`;
              this.token     = this.url+`/`+this.lang+`/token.mp3`; 
         
              // make audio and play
              if ((objects.length > 0)) {
                objects.map(function(item) {
                  this.processAudio(item);
                }, this);

                this.player(); 
              } 

            }
          }

          processAudio(tokenObject) {
            this.sounds.push(this.makeAudio(this.start));

            if (this.callCounter) {
              this.sounds.push(this.makeAudio(this.counter));
              var i = 0;
              const counter = tokenObject.counter.toString();
              while (i < counter.length) 
              {
                var char = counter.charAt(i).toLowerCase(); 
                this.sounds.push(this.makeAudio(this.url+`/`+this.lang+`/char/`+char+`.mp3`)); 
                i++;
              }
            }

            if (this.callCounter) {
              this.sounds.push(this.makeAudio(this.token));
            }

            const token = tokenObject.token.toString();
            var i = 0;
            while (i < token.length) 
            {
              var char = token.charAt(i).toLowerCase(); 
              this.sounds.push(this.makeAudio(this.url+`/`+this.lang+`/char/`+char+`.mp3`)); 
              i++;
            }
          } 

          makeAudio(filePath) {
            var audio = new Audio(filePath.replace(/([^:]\/)\/+/g, "$1"));
            audio.crossOrigin = "anonymous";
            return audio;
          }

          // take a object and convert to audio 
          async player() {
            Notification.status = false; 
            for (var i = 0; i < (this.sounds).length; i++) {
              const item = this.sounds[i];
              await new Promise((resolve) => {
                item.onended = resolve;
                item.play();
              });
            }
            Notification.status = true;
          }

        } 
        </script>
  </body>
</html>