@extends('layouts.backend')
@section('title', trans('app.dashboard'))

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading"><h3 class="text-left">{{ trans('app.dashboard') }}</h3></div>
    <div class="panel-body"> 
        <div class="row">

            

        </div> 
    </div>
</div> 
@endsection

@push('scripts')
<script src="{{ asset('public/assets/js/Chart.min.js') }}"></script>
<script type="text/javascript"> 
$(window).on('load', function(){

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
