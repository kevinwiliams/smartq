@extends('layouts.backend')
@section('title', trans('app.dashboard'))

@section('content')
<div class="panel panel-primary">
    {{-- <div class="panel-heading"><h3 class="text-left">{{ trans('app.dashboard') }}</h3></div> --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="{{ url('admin/token/create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-ticket-alt fa-sm text-white-50"></i> {{ trans('app.manual_token') }}</a>
    </div>
    <div class="panel-body">

        <div class="row">
            <!-- Pending Tokens -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ trans('app.pending') }} {{ trans('app.token') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->token->pending)?$infobox->token->pending:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Complete Tokens -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ trans('app.complete') }} {{ trans('app.token') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->token->complete)?$infobox->token->complete:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Tokens? -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    All {{ trans('app.token') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->token->complete)?$infobox->token->total:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Active Users -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                   Active {{ trans('app.users') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->user)?$infobox->user:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Active Counters -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ trans('app.counter') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->counter)?$infobox->counter:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-desktop fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Active Departments -->
            <div class="col-xl-2 col-md-2 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ trans('app.department') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ (isset($infobox->department)?$infobox->department:0) }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cubes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row"> 
              <!-- Area Chart -->
              <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ trans('app.this_month') }}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="lineChart" style="height:150px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ trans('app.this_year') }}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="singelBarChart"></canvas>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row"> 

            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ trans('app.today_user_performance') }}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        @if (!empty($performance))   
                        @foreach($performance as $user)
                        <?php
                            $pending = number_format(((($user->pending?$user->pending:0)/($user->total?$user->total:1))*100),1);
                            $complete = number_format(((($user->complete?$user->complete:0)/($user->total?$user->total:1))*100),1);
                            $stop = number_format(((($user->stop?$user->stop:0)/($user->total?$user->total:1))*100),1);
                        ?>
                            <div class="row">
                                <label class="col-sm-3 col-xs-12">{{ $user->username }}</label>
                                <div class="col-sm-9 col-xs-12"> 
                                    <div class="progress"> 
                                    <div class="progress-bar progress-bar-danger" style="width: {{ $stop }}%">
                                        <span>{{ $stop }}% {{trans("app.stop")}} (Total {{ $user->stop }}) </span>
                                    </div>
                                    <div class="progress-bar progress-bar-success" style="width: {{ $complete }}%">
                                        <span>{{ $complete }}% {{trans("app.complete")}} (Total {{ $user->complete }}) </span>
                                    </div>
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $pending }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pending }}%">
                                        <span>{{ $pending }}% {{trans("app.pending")}} (Total {{ $user->pending }}) </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif 
                        
                    </div>
                </div>
            </div>
           <!-- Pie Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ trans('app.from_the_begining') }}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="pieChart" style="height: 200px"></canvas>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div> 
@endsection
 
@push("scripts")
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
<script type="text/javascript"> 
$(window).on('load', function(){

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';
        
    //line chart
    var ctx = document.getElementById("lineChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php 
                if (!empty($month)) {
                    for ($i=0; $i < sizeof($month) ; $i++) { 
                       echo (!empty($month[$i])?$month[$i]->date:0).", ";
                    }
                }
                ?>
            ],
            datasets: [
                {
                    label: "Total",
                    borderColor: "rgba(24, 97, 142, .9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(24, 97, 142, .09)",
                    pointHighlightStroke: "rgba(24, 97, 142, 1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->total:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Success",
                    borderColor: "rgba(225, 48, 91, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(225, 48, 91, 0.09)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->success:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Pending",
                    borderColor: "rgba(0,0,0, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(0,0,0, 0.09)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [
                        <?php 
                        if (!empty($month)) {
                            for ($i=0; $i < sizeof($month) ; $i++) { 
                               echo (!empty($month[$i])?$month[$i]->pending:0).", ";
                            }
                        }
                        ?>
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            } 
        }
    });


    // single bar chart
    var ctx = document.getElementById("singelBarChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php 
                if (!empty($year)) {
                    for ($i=0; $i < sizeof($year) ; $i++) { 
                       echo "'".(!empty($year[$i])?$year[$i]->month:0)."', ";
                    }
                }
                ?>
            ],
            datasets: [
                {
                    label: "Total",
                    borderColor: "rgba(24, 97, 142, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(24, 97, 142, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->total:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Success",
                    borderColor: "rgba(225, 48, 91, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(225, 48, 91, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->success:0).", ";
                            }
                        }
                        ?>
                    ]
                },
                {
                    label: "Pending",
                    borderColor: "rgba(0,0,0, 0.9)",
                    borderWidth: "1",
                    backgroundColor: "rgba(0,0,0, 0.5)",
                    data: [
                        <?php 
                        if (!empty($year)) {
                            for ($i=0; $i < sizeof($year) ; $i++) { 
                               echo (!empty($year[$i])?$year[$i]->pending:0).", ";
                            }
                        }
                        ?>
                    ]
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }             
        }
    });



    // pie chart
    var ctx = document.getElementById("pieChart"); 
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                    data: [
                        <?php 
                        if (!empty($begin) && is_array($begin)) { 
                               echo (!empty($begin[0])?$begin[0]->total:0).", ";
                               echo (!empty($begin[0])?$begin[0]->success:0).", ";
                               echo (!empty($begin[0])?$begin[0]->pending:0); 
                        }
                        ?>
                    ],
                    backgroundColor: [
                        "rgba(24, 97, 142,0.9)",
                        "rgba(225, 48, 91,0.7)",
                        "rgba(0,0,0,0.5)",
                        "rgba(0,0,0,0.07)"
                    ],
                    hoverBackgroundColor: [
                        "rgba(24, 97, 142,0.9)",
                        "rgba(225, 48, 91,0.7)",
                        "rgba(0,0,0,0.5)",
                        "rgba(0,0,0,0.07)"
                    ]

                }],
            labels: [
                "Total",
                "Success",
                "Pending"
            ]
        },
        options: {
            responsive: true
        }
    });
 
});
</script>
@endpush