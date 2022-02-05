@extends('layouts.backend')
@section('title', trans('app.todays_token'))

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.active') }} / {{ trans('app.todays_token') }}</h3>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <input type="hidden" name="department_id" id="department_id" value="{{ !empty($token->department)?$token->department->id:null }}">
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card {!! (!empty($token->is_vip)? " border-left-danger" :"border-left-primary") !!} bg-gradient-light shadow h-100 py-2" style="min-height: 300px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col text-center">

                            <div class="text-xs font-weight-bold {!! (!empty($token->is_vip)? " text-danger" :"text-primary") !!} text-uppercase mb-1">
                                {{ !empty($token->department)?$token->department->name:null }}
                            </div>
                            <div class="h1 mb-3 font-weight-bold text-gray-800"><i class="fa fa-ticket-alt rotate-15"></i> {!! (!empty($token->is_vip)?("<span class=\"badge bg-danger text-white\" title=\"VIP\">".$token->token_no."</span>"):$token->token_no) !!}</div>
                            {{-- <div class="h5 b-0 text-gray-800">{{ !empty($token->counter)?$token->counter->name:null }}
                        </div> --}}
                        <div class="h5 mb-3 text-gray-800">{{ $token->client_mobile }}<br />
                            {!! (!empty($token->client)?("(<a href='".url("common/setting/profile")."'>".$token->client->firstname." ". $token->client->lastname."</a>)"):null) !!}
                        </div>
                        <br>
                        <span class="h6 mx-3" id="tkn_position">You are #{{ $position }} in the queue</span>
                        <br>
                        <span class="h6 mb-3">Potential wait time
                            <i class="fa fa-clock"></i>&nbsp;
                            <span id="span_wait">{{ $wait }}</span>
                        </span>
                        <br><br>
                        <div class="btn-group">
                            <a href="#" class="btn btn-danger btn-sm" onclick="javascript:getOutOfLine('{{ $token->id }}')" title="Stoped"><i class="fa fa-stop"></i> Get out of line</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@push("scripts")
<script type="text/javascript">
    (function() {
        if (window.addEventListener) {
            window.addEventListener("load", loadHandler, false);
        } else if (window.attachEvent) {
            window.attachEvent("onload", loadHandler);
        } else {
            window.onload = loadHandler;
        }

        function loadHandler() {
            setTimeout(doMyStuff, 60000);
        }

        function doMyStuff() {
            $.ajax({
                type: 'get',
                url: '{{ URL::to("client/token/currentposition") }}',            
                success: function(data) {
                    console.log(data);
                    $("#tkn_position").text("You are #" + data.position + " in the queue.");
                    $("#span_wait").text(data.wait);
                }
            });
        }



        $(document).ready(function() {
            doMyStuff(); 
        });

        // print token
        $("body").on("click", ".tokenPrint", function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': $(this).attr('data-token-id'),
                    '_token': '<?php echo csrf_token() ?>'
                },
                success: function(data) {
                    var content = "<style type=\"text/css\">@media print {" +
                        "html, body {display:block;margin:0!important; padding:0 !important;overflow:hidden;display:table;}" +
                        ".receipt-token {width:100vw;height:100vw;text-align:center}" +
                        ".receipt-token h4{margin:0;padding:0;font-size:7vw;line-height:7vw;text-align:center}" +
                        ".receipt-token h1{margin:0;padding:0;font-size:15vw;line-height:20vw;text-align:center}" +
                        ".receipt-token ul{margin:0;padding:0;font-size:7vw;line-height:8vw;text-align:center;list-style:none;}" +
                        "}</style>";

                    content += "<div class=\"receipt-token\">";
                    content += "<h4>{{ \Session::get('app.title') }}</h4>";
                    content += "<h1>" + data.token_no + "</h1>";
                    content += "<ul class=\"list-unstyled\">";
                    content += "<li><strong>{{ trans('app.department') }} </strong>" + data.department + "</li>";
                    content += "<li><strong>{{ trans('app.counter') }} </strong>" + data.counter + "</li>";
                    content += "<li><strong>{{ trans('app.officer') }} </strong>" + data.firstname + ' ' + data.lastname + "</li>";
                    if (data.note) {
                        content += "<li><strong>{{ trans('app.note') }} </strong>" + data.note + "</li>";
                    }
                    content += "<li><strong>{{ trans('app.date') }} </strong>" + data.created_at + "</li>";
                    content += "</ul>";
                    content += "</div>";

                    // print 
                    printThis(content);


                },
                error: function(err) {
                    alert('failed!');
                }
            });
        });

    })();

    function getOutOfLine(id) {
        // alert(id);
        // var _url = '{{ URL::to("client/token/stoped") }}/' + id;
        // alert(_url);
        // return;
        swal('Are you sure?', {
                title: 'Are you sure?',
                icon: 'warning',
                buttons: {
                    cancel: "Oops!!!",
                    ok: true
                }
            })
            .then((value) => {
                switch (value) {
                    case "ok":
                        $.ajax({
                            url: '{{ URL::to("client/token/stoped") }}/' + id,
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                document.location.href = '/client';
                            }
                        });
                        break;
                }
            });
    }
</script>
@endpush