<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Session::get('app.title') }} &middot; {{ trans('app.signin') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ \Session::get('app.favicon') }}" type="image/x-icon" />
    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel='stylesheet'>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- select2 -->
    <link href="{{ asset('assets/css/select2.min.css') }}" rel='stylesheet'>
    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel='stylesheet'>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">{{ \Session::get('app.title') }}</h1>
                                    </div>
                                    {{-- include backend --}}
                                    @include('backend.common.info')
                                        <!-- Starts of Message -->
                                        <div class="col-xs-12">
                                            @yield('info.message')
                                        </div>
                                    <!-- <form class="user"> -->
                                    {{ Form::open(['url' => 'login', 'class'=>'user']) }}
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp" name="email"
                                                placeholder="{{ trans('app.email') }}" value="{{ old('email') }}" autocomplete="off">
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password"
                                                id="password" placeholder="{{ trans('app.password') }}" value="{{ old('password') }}" autocomplete="off">
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                            </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            @yield('info.language')
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            {{ trans('app.signin')}}
                                        </button>
                                        <hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                        {{ Form::close() }}   
        
                                    <!-- </form> -->
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="#">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="#">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>



    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>


    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- select2 -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script type="text/javascript">

        $(function() { 
            $('table body tr').on('click', function() {
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
    
       
        </script> 
</body>

</html>