<!DOCTYPE html>
<html lang="fr">
<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Session::get('app.title') }} :: {{ trans('app.signin') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ \Session::get('app.favicon') }}" type="image/x-icon" />
    <!-- font-awesome -->
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel='stylesheet'>
    <!-- template bootstrap -->
    <link href="{{ asset('assets/css/template.min.css') }}" rel='stylesheet prefetch'> 
    <!-- select2 -->
    <link href="{{ asset('assets/css/select2.min.css') }}" rel='stylesheet'>
    <!-- Jquery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
</head>
<body class="cm-login">
    <div class="loader">
       <!-- insert loader animation -->
    </div>

    <div class="text-center" style="padding:35px 0 30px 0;background:#fff;border-bottom:1px solid #ddd;">
        <h2 class="text-primary text-center text-uppercase">{{ \Session::get('app.title') }}</h2>
        <img src="{{ asset('assets/img/icons/logo.jpg') }}" width="300" >
    </div>
    
    <div class="col-sm-6 col-md-4 col-lg-3" style="margin:30px auto; float:none;">
        @include('backend.common.info')
        <!-- Starts of Message -->
        <div class="col-xs-12">
            @yield('info.message')
        </div>

        {{ Form::open(['url' => 'login', 'class'=>'']) }}
        <div class="col-xs-12">
            <div class="form-group">
                <label for="email" class="control-label sr-only">{{ trans('app.email') }}</label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></div>
                    <input type="text" name="email" class="form-control" id="email" placeholder="{{ trans('app.email') }}"  value="{{ old('email') }}" autocomplete="off">
                </div>
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
            <div class="form-group">
                <label for="password" class="control-label sr-only">{{ trans('app.password') }}</label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ trans('app.password') }}" value="{{ old('password') }}" autocomplete="off">
                </div>
                <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>
        </div>
        <div class="col-xs-6">
            @yield('info.language')
        </div>
        <div class="col-xs-6">
          <button type="submit" class="btn btn-block btn-primary">{{ trans('app.signin')}}</button>
        </div> 
        {{ Form::close() }}   
        
        @yield('info.login-credentials')
    </div>  

    <footer class="cm-footer">
        <span class="col-sm-8 col-xs-12 text-left">@yield('info.powered-by') @yield('info.version')</span>
        <span class="col-sm-4 col-xs-12 text-right hidden-xs"> {{ \Session::get('app.copyright_text') }}</span>
    </footer> 

    <!-- Jquery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- bootstrp -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script type="text/javascript">

    $(function() { 
        $('table tbody tr').on('click', function() {
            $("input[name=email]").val($(this).children().first().text());
            $("input[name=password]").val($(this).children().first().next().text());
        }); 

        // select2
        $("select").select2();

        //language switch
        $("#lang-select").on('change', function() {
            var x = $(this).val();
            $.ajax({
               type:'GET',
               url:'{{ URL::to("common/language/") }}',
               data: {
                  'locale' : x, 
                  '_token' : '<?php echo csrf_token() ?>'
               },
               success:function(data){
                  history.go(0);
               }, error: function() {
                alert('failed');
               }
            });       
        }); 
    }(jQuery));

    //preloader
    $(window).load(function() {
        $(".loader").fadeOut("slow");;
    });
    </script> 
</body>
</html>
