<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Session::get('app.title') }} - @yield('title')</title>

       <!-- Custom fonts for this template-->
       <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel='stylesheet'>

       <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
       <!-- Custom styles for this template-->
       <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel='stylesheet'>
 
       <!-- jquery-ui -->
       <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel='stylesheet'>
       <!-- datatable -->
       {{-- <link href="{{ asset('assets/css/dataTables.min.css') }}" rel='stylesheet'> --}}
       <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel='stylesheet'>
       <!-- select2 -->
       <link href="{{ asset('assets/css/select2.min.css') }}"  rel='stylesheet'>
      
       <!-- Page styles --> 
       @stack('styles')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar toggled sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon">
                    <i class="fab fa-quora"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Smart<sup>Q</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

        <!----------------------- 
            || ADMIN MENU 
        -------------------------->
        @if(Auth::user()->hasRole('admin')) 
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ ((Request::is('admin')) ? 'active' : '') }}">
                <a class="nav-link" href="{{ url('admin') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ trans('app.dashboard') }}</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Ticketing
            </div>

            <!-- Nav Item - Token Collapse Menu -->
            <li class="nav-item {{ (Request::segment(2)=='token' ? 'active' : '') }}">
                <a class="nav-link" href="{{ url('admin/token/auto') }}">
                    <i class="fas fa-fw fa-ticket-alt rotate-15"></i>
                    <span>{{ trans('app.token') }}</span>
                </a>
                {{-- <div id="collapseToken" class="collapse {{ (Request::segment(2)=='token' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/token/auto') ? 'active' : '') }}" href="{{ url('admin/token/auto') }}">{{ trans('app.auto_token') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/token/create') ? 'active' : '') }}" href="{{ url('admin/token/create') }}">{{ trans('app.manual_token') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/token/current') ? 'active' : '') }}" href="{{ url('admin/token/current') }}">{{ trans('app.active') }} / {{ trans('app.todays_token') }}</a>
                    </div>
                </div> --}}
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Preferences  <!-- TODO: Translation -->
            </div>
            
            <!-- Nav Item - Configuration -->
            <li class="nav-item {{ (Request::segment(2)=='department' ? 'active' : '') }} {{ (Request::segment(2)=='counter' ? 'active' : '') }} {{ (Request::segment(2)=='user' ? 'active' : '') }}">
                <a class="nav-link" href="{{ url('admin/department') }}">
                    <i class="fas fa-fw fa-server"></i>
                    <span>Configuration</span></a>
            </li>
            <!-- Nav Item - Department Collapse Menu -->
            {{-- <li class="nav-item {{ (Request::segment(2)=='department' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='department' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseDept"
                    aria-expanded="true" aria-controls="collapseDept">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>{{ trans('app.department') }}</span>
                </a>
                <div id="collapseDept" class="collapse {{ (Request::segment(2)=='department' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/department/create') ? 'active' : '') }}" href="{{ url('admin/department/create') }}">{{ trans('app.add_department') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/department') ? 'active' : '') }}" href="{{ url('admin/department') }}">{{ trans('app.department_list') }}</a>
                    </div>
                </div>
            </li> --}}

            <!-- Nav Item - Counter Collapse Menu -->
            {{-- <li class="nav-item {{ (Request::segment(2)=='counter' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='counter' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseCounter"
                    aria-expanded="true" aria-controls="collapseCounter">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>{{ trans('app.counter') }}</span>
                </a>
                <div id="collapseCounter" class="collapse {{ (Request::segment(2)=='counter' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/counter/create') ? 'active' : '') }}" href="{{ url('admin/counter/create') }}">{{ trans('app.add_counter') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/counter') ? 'active' : '') }}" href="{{ url('admin/counter') }}">{{ trans('app.counter_list') }}</a>
                    </div>
                </div>
            </li> --}}
            
            <!-- Nav Item - Users Collapse Menu -->
            {{-- <li class="nav-item {{ (Request::segment(2)=='user' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='user' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseUser"
                    aria-expanded="true" aria-controls="collapseUser">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>{{ trans('app.users') }}</span>
                </a>
                <div id="collapseUser" class="collapse {{ (Request::segment(2)=='user' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/user/create') ? 'active' : '') }}" href="{{ url('admin/user/create') }}">{{ trans('app.add_user') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/user') ? 'active' : '') }}" href="{{ url('admin/user') }}">{{ trans('app.user_list') }}</a>
                    </div>
                </div>
            </li> --}}


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Messaging
            </div>

             <!-- Nav Item - SMS Collapse Menu -->
             <li class="nav-item {{ (Request::segment(2)=='sms' ? 'active' : '') }}">
                <a class="nav-link" href="{{ url('admin/sms/list') }}">
                    <i class="fas fa-fw fa-comment-alt"></i>
                    <span>{{ trans('app.sms') }}</span>
                </a>
                {{-- <div id="collapseSMS" class="collapse {{ (Request::segment(2)=='sms' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/sms/new') ? 'active' : '') }}" href="{{ url('admin/sms/new') }}">{{ trans('app.new_sms') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/sms/list') ? 'active' : '') }}" href="{{ url('admin/sms/list') }}">{{ trans('app.sms_history') }}</a>
                    </div>
                </div> --}}
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

                        
            <!-- Nav Item - Reports -->
            <li class="nav-item {{ (Request::segment(3)=='report' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(3)=='report' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseReport"
                    aria-expanded="true" aria-controls="collapseReport">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Reports</span>
                </a>
                <div id="collapseReport" class="collapse {{ (Request::segment(3)=='report' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('admin/token/report') ? 'active' : '') }}" href="{{ url('admin/token/report') }}">{{ trans('app.token_report') }}</a>
                        <a class="collapse-item {{ (Request::is('admin/token/performance') ? 'active' : '') }}" href="{{ url('admin/token/performance') }}">{{ trans('app.performance_report') }}</a>

                    </div>
                </div>
            </li>

        @endif
        
        <!----------------------- 
            || OFFICER MENU 
        -------------------------->
        @if(Auth::user()->hasRole('officer'))
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ ((Request::is('officer')) ? 'active' : '') }}">
                <a class="nav-link" href="{{ url('officer') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ trans('app.dashboard') }}</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Ticketing
            </div>

            <!-- Nav Item - Token Collapse Menu -->
            <li class="nav-item {{ (Request::segment(2)=='token' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='token' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseTokenO"
                    aria-expanded="true" aria-controls="collapseTokenO">
                    <i class="fas fa-fw fa-ticket-alt rotate-15"></i>
                    <span>{{ trans('app.token') }}</span>
                </a>
                <div id="collapseTokenO" class="collapse {{ (Request::segment(2)=='token' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        
                        <!-- <a class="collapse-item {{ (Request::is('officer/token/display') ? 'active' : '') }}" target="_blank" href="{{ url('officer/token/display') }}">{{ trans('app.display') }}</a> -->
                        <a class="collapse-item {{ (Request::is('officer/token/current') ? 'active' : '') }}" href="{{ url('officer/token/current') }}">{{ trans('app.active') }} / {{ trans('app.todays_token') }}</a>
                        <a class="collapse-item {{ (Request::is('officer/token') ? 'active' : '') }}" href="{{ url('officer/token') }}">{{ trans('app.token_list') }}</a>

                    </div>
                </div>
            </li>
        @endif

        <!----------------------- 
            || ATTENDANT MENU 
        -------------------------->
        @if(Auth::user()->hasRole('receptionist'))
           
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Ticketing
            </div>

            <!-- Nav Item - Token Collapse Menu -->
            <li class="nav-item {{ (Request::segment(2)=='token' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='token' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseTokenT"
                    aria-expanded="true" aria-controls="collapseTokenT">
                    <i class="fas fa-fw fa-ticket-alt rotate-15"></i>
                    <span>{{ trans('app.token') }}</span>
                </a>
                <div id="collapseTokenT" class="collapse {{ (Request::segment(2)=='token' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('receptionist/token/auto') ? 'active' : '') }}" href="{{ url('receptionist/token/auto') }}">{{ trans('app.auto_token') }}</a>
                        <a class="collapse-item {{ (Request::is('receptionist/token/create') ? 'active' : '') }}" href="{{ url('receptionist/token/create') }}">{{ trans('app.manual_token') }}</a>
                        <a class="collapse-item {{ (Request::is('receptionist/token/current') ? 'active' : '') }}" href="{{ url('receptionist/token/current') }}">{{ trans('app.active') }} / {{ trans('app.todays_token') }}</a>

                    </div>
                </div>
            </li>
        @endif

         <!----------------------- 
            || CLIENT MENU 
        -------------------------->
        @if(Auth::user()->hasRole('client'))
           
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Ticketing
            </div>

            <!-- Nav Item - Token Collapse Menu -->
            <li class="nav-item {{ (Request::segment(2)=='client' ? 'active' : '') }}">
                <a class="nav-link {{ (Request::segment(2)=='client' ? '' : 'collapsed') }}" href="#" data-toggle="collapse" data-target="#collapseTokenC"
                    aria-expanded="true" aria-controls="collapseTokenC">
                    <i class="fas fa-fw fa-ticket-alt rotate-15"></i>
                    <span>{{ trans('app.token') }}</span>
                </a>
                <div id="collapseTokenC" class="collapse {{ (Request::segment(2)=='client' ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Interface:</h6>
                        <a class="collapse-item {{ (Request::is('client/token/auto') ? 'active' : '') }}" href="{{ url('client/token/auto') }}">{{ trans('app.auto_token') }}</a>
                        <a class="collapse-item {{ (Request::is('client/token/create') ? 'active' : '') }}" href="{{ url('client/token/create') }}">{{ trans('app.manual_token') }}</a>
                        <a class="collapse-item {{ (Request::is('client/token/current') ? 'active' : '') }}" href="{{ url('client/token/current') }}">{{ trans('app.active') }} / {{ trans('app.todays_token') }}</a>

                    </div>
                </div>
            </li>
        @endif

        <!----------------------- 
            || COMMON MENU 
        -------------------------->
         <!-- Heading -->
        <div class="sidebar-heading">
            Settings  <!-- TODO: Translation -->
        </div>

        <!-- Nav Item - Common Collapse Menu -->
        @if (auth()->user()->hasRole('admin'))
        <li class="nav-item {{ ((Request::segment(2)=='setting' || Request::segment(3)=='setting') ? 'active' : '') }}">
            <a class="nav-link" href="{{ url('admin/setting') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>{{ trans('app.setting') }}</span>
            </a>
            <div id="collapseSettings" class="collapse {{ ((Request::segment(2)=='setting' || Request::segment(3)=='setting') ? '' : '') }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Interface:</h6>
                
                    <a class="collapse-item {{ (Request::is('admin/setting') ? 'active' : '') }}" href="{{ url('admin/setting') }}">{{ trans('app.app_setting') }}</a>
                    <a class="collapse-item {{ (Request::is('admin/setting/display') ? 'active' : '') }}" href="{{ url('admin/setting/display') }}">{{ trans('app.display_setting') }}</a>
                    <a class="collapse-item {{ (Request::is('admin/token/setting') ? 'active' : '') }}" href="{{ url('admin/token/setting') }}">{{ trans('app.auto_token_setting') }}</a>
                    <a class="collapse-item {{ (Request::is('admin/sms/setting') ? 'active' : '') }}" href="{{ url('admin/sms/setting') }}">{{ trans('app.sms_setting') }}</a>
                    {{-- <a class="collapse-item {{ (Request::is('common/setting/profile') ? 'active' : '') }}" href="{{ url('common/setting/profile') }}">{{ trans('app.profile_information') }}</a> --}}
                </div>
            </div>
        </li>
        @endif


            <!-- Nav Item - Tables -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">
                <p class="text-center mb-2"><strong>This area</strong> can be used for sign ups or ads</p>
                <a class="btn btn-success btn-sm" href="#">Sign Up</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <span class="text-xs text-gray-800">Location: <i class="fas fa-map-marker fa-fw text-danger"></i>Port Of Spain, Trinidad &amp; Tobago</span>

                    <!-- Topbar Search -->
                    <!-- form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('common/display?type=4') }}" id="displayLink" 
                               aria-expanded="false" target="_blank">
                                <i class="fas fa-desktop fa-fw"></i>
                            </a>
                            
                            
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">0</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a> -->
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter" id="message-notify">0</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>-->
                                <a class="dropdown-item text-center small text-gray-500" href="{{ url('common/message/inbox') }}">Read More Messages</a> 
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($user = Auth::user()) 
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->firstname .' '. $user->lastname }}</span>
                                <img class="img-profile rounded-circle" src="{{ !empty($user->photo)?asset($user->photo):asset('assets/img/sf/undraw_profile.svg') }}">
                            @endif
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ url('common/setting/profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ trans('app.profile_information') }}
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ trans('app.signout') }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->

                     <!-- Starts of Message -->
                    @yield('info.message')
                    <!-- Ends of Message --> 

                    <!-- Starts of Content -->
                    @yield('content')
                    <!-- Ends of Contents -->                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span class="hidden-xs">{{ \Session::get('app.copyright_text') }}</span>
                        <span>@yield('info.powered-by') @yield('info.version') Powered by Smart<sub>Q</sub></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('logout') }}">{{ trans('app.signout') }}</a>
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

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    {{-- <script src="{{ asset('assets/js/reports/chart-area-demo.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/reports/chart-pie-demo.js') }}"></script> --}}





     <!-- select2 -->
     <script src="{{ asset('assets/js/select2.min.js') }}"></script>
     <!-- juery-ui -->
     <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script> 
     <!-- jquery.mousewheel.min -->
     <script src="{{ asset('assets/js/jquery.mousewheel.min.js') }}"></script>
     <!-- jquery.cookie.min -->
     <script src="{{ asset('assets/js/jquery.cookie.min.js') }}"></script>
     <!-- fastclick -->
     <script src="{{ asset('assets/js/fastclick.min.js') }}"></script>
     <!-- template -->
     <!-- datatable -->
     <script src="{{ asset('assets/js/dataTables.min.js') }}"></script>
     {{-- <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script> --}}
   
     <!-- sweet alert -->
     <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>

     <!-- custom script -->
     <script src="{{ asset('assets/js/script.js') }}"></script>

     <!-- Page Script -->
     @stack('scripts')
        
     <script type="text/javascript">
     (function() {
       //notification
         notify();
         setInterval(function(){
             notify();
         }, 30000);

         function notify()
         {
             $.ajax({
                type:'GET',
                url:'{{ URL::to("common/message/notify") }}',
                data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                   $("#message-notify").html(data);
                }
             });
         }
      
         //language switch
         $(".select-lang").on('click', function() { 
             $.ajax({
                type:'GET',
                url: '{{ url("common/language") }}',
                data: {
                   'locale' : $(this).data("locale"), 
                   '_token' : '<?php echo csrf_token() ?>'
                },
                success:function(data){
                   history.go(0);
                }, error: function() {
                 alert('failed');
                }
             });       
         });
         
     })();
     </script>
</body>

</html>