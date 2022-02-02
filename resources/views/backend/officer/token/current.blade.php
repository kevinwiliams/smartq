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
    <div class="row">
        <div class="col-md-4 col-lg-4" style=" max-height: 500px; overflow: scroll">

            @if (!empty($tokens))
                <?php $sl = 1 ?>
                @foreach ($tokens as $token)
            {{-- Waiting list cards --}}
                    <div class="col-xl-12 col-md-12 mb-4">
                        
                        <div class="card {!! (!empty($token->is_vip)? "border-left-danger" :"border-left-primary") !!}  shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        #{{ $sl++ }} <div class="text-xs font-weight-bold {!! (!empty($token->is_vip)? "text-danger" :"text-primary") !!}  text-uppercase mb-1">
                                            {{ !empty($token->department)?$token->department->name:null }}</div>
                                        <div class="mb-0 font-weight-bold text-gray-800"><i class="fas fa-user text-gray-500"></i> Client L.</div>
                                        <div class="mb-0 text-gray-800"><i class="fas fa-ticket-alt rotate-15 text-gray-500"></i> {!! (!empty($token->is_vip)?("<span class=\"badge bg-danger text-white\" title=\"VIP\">$token->token_no</span>"):$token->token_no) !!}</div>
                                        <div class="mb-0 text-gray-800"><i class="fas fa-tv text-gray-500"></i> {{ !empty($token->counter)?$token->counter->name:null }}</div>
                                        <div class="mb-0 text-gray-800"><i class="fas fa-phone text-gray-500"></i> {{ $token->client_mobile }}<br/>
                                            {!! (!empty($token->client)?("(<a href='".url("officer/user/view/{$token->client->id}")."'>".$token->client->firstname." ". $token->client->lastname."</a>)"):null) !!}
                                        </div>
                                        <div class="mb-0 text-gray-800"><i class="fas fa-calendar text-gray-500"></i> {{ (!empty($token->created_at)?date('j M Y h:i a',strtotime($token->created_at)):null) }}</div>

                                        
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                        <br><br>
                                        <a href="{{ url("officer/token/complete/$token->id") }}"  class="btn btn-success btn-sm" onclick="return confirm('Are you sure?')" title="Complete"><i class="fa fa-check"></i></a> 
                                        <br><br>
                                        <a href="{{ url("officer/token/stoped/$token->id") }}"  class="btn btn-warning btn-sm" onclick="return confirm('Are you sure?')" title="Stoped"><i class="fa fa-stop"></i></a>
    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-md-8 col-lg-8">
            <div class="col-xl-12 col-md-12 mb-4" >
                <div class="card {!! (!empty($tokens[0]->is_vip)? "border-left-danger" :"border-left-primary") !!}  bg-gradient-light shadow h-100 py-2" style="min-height: 500px;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                
                                <div class="text-xs font-weight-bold {!! (!empty($tokens[0]->is_vip)? "text-danger" :"text-primary") !!}  text-uppercase mb-1">
                                    {{ !empty($tokens[0]->department)?$tokens[0]->department->name:null }}</div>
                                <div class="h1 mb-0 font-weight-bold text-gray-800"><i class="fas fa-user text-gray-500"></i> Client L.</div>
                                <div class="h3 mb-0 text-gray-800">{!! (!empty($tokens[0]->is_vip)?("<span class=\"badge bg-danger text-white\" title=\"VIP\">".$tokens[0]->token_no."</span>"):$tokens[0]->token_no) !!}</div>
                                {{-- <div class="h5 b-0 text-gray-800">{{ !empty($tokens[0]->counter)?$tokens[0]->counter->name:null }}</div> --}}
                                <div class="h5 mb-0 text-gray-800">{{ $tokens[0]->client_mobile }}<br/>
                                    {!! (!empty($tokens[0]->client)?("(<a href='".url("officer/user/view/{$tokens[0]->client->id}")."'>".$tokens[0]->client->firstname." ". $tokens[0]->client->lastname."</a>)"):null) !!}
                                </div>
                                <div class="h5 mb-0 text-gray-800"><i class="fas fa-calendar text-gray-500"></i> {{ (!empty($tokens[0]->created_at)?date('j M Y h:i a',strtotime($tokens[0]->created_at)):null) }}</div>
                                <br><br>
                                
                                
                                <a href="{{ url("officer/token/stoped/".$tokens[0]->id."") }}"  class="btn btn-warning btn-sm mx-2" onclick="return confirm('Are you sure?')" title="Stoped"><i class="fa fa-stop"></i></a>
                                <a href="{{ url("officer/token/complete/".$tokens[0]->id."") }}"  class="btn btn-success btn-sm" onclick="return confirm('Are you sure?')" title="Complete"><i class="fa fa-check"></i> Next</a> 
                                
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-8x text-gray-300"></i>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <hr>
    {{-- <div class="panel-body">
        <table class="datatable display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('app.token_no') }}</th>
                    <th>{{ trans('app.department') }}</th>
                    <th>{{ trans('app.counter') }}</th>
                    <th>{{ trans('app.client_mobile') }}</th>
                    <th>{{ trans('app.note') }}</th>
                    <th>{{ trans('app.status') }}</th>
                    <th>{{ trans('app.created_by') }}</th>
                    <th>{{ trans('app.created_at') }}</th>
                    <th width="120">{{ trans('app.action') }}</th>
                </tr>
            </thead> 
            <tbody>
                @if (!empty($tokens))
                    <?php $sl = 1 ?>
                    @foreach ($tokens as $token)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>
                                {!! (!empty($token->is_vip)?("<span class=\"badge bg-danger text-white\" title=\"VIP\">$token->token_no</span>"):$token->token_no) !!} 
                            </td>
                            <td>{{ !empty($token->department)?$token->department->name:null }}</td>
                            <td>{{ !empty($token->counter)?$token->counter->name:null }}</td> 
                            <td>
                                {{ $token->client_mobile }}<br/>
                                {!! (!empty($token->client)?("(<a href='".url("officer/user/view/{$token->client->id}")."'>".$token->client->firstname." ". $token->client->lastname."</a>)"):null) !!}
                            </td>
                            <td>{{ $token->note }}</td>
                            <td> 
                                @if($token->status==0) 
                                <span class="badge bg-primary text-white">{{ trans('app.pending') }}</span> 
                                @elseif($token->status==1)   
                                <span class="badge bg-success text-white">{{ trans('app.complete') }}</span>
                                @elseif($token->status==2) 
                                <span class="badge bg-danger text-white">{{ trans('app.stop') }}</span>
                                @endif
                                {!! (!empty($token->is_vip)?('<span class="badge bg-danger text-white" title="VIP">VIP</span>'):'') !!}
                            </td>
                            <td>{!! (!empty($token->generated_by)?("<a href='".url("officer/user/view/{$token->generated_by->id}")."'>".$token->generated_by->firstname." ". $token->generated_by->lastname."</a>"):null) !!}</td> 
                            <td>{{ (!empty($token->created_at)?date('j M Y h:i a',strtotime($token->created_at)):null) }}</td>
                            <td>
                                <div class="btn-group"> 
                                    <a href="{{ url("officer/token/complete/$token->id") }}"  class="btn btn-success btn-sm" onclick="return confirm('Are you sure?')" title="Complete"><i class="fa fa-check"></i></a> 

                                    <a href="{{ url("officer/token/stoped/$token->id") }}"  class="btn btn-warning btn-sm" onclick="return confirm('Are you sure?')" title="Stoped"><i class="fa fa-stop"></i></a>

                                    <button type="button" href='{{ url("officer/token/print") }}' data-token-id='{{ $token->id }}' class="tokenPrint btn btn-default btn-sm" title="Print" ><i class="fa fa-print"></i></button>
                                </div>
                            </td>
                        </tr> 
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>  --}}
</div>   
@endsection

@push("scripts")
<script type="text/javascript">
(function() {
    if (window.addEventListener) {
        window.addEventListener("load", loadHandler, false);
    }
    else if (window.attachEvent) {
        window.attachEvent("onload", loadHandler);
    }
    else {
        window.onload = loadHandler;
    }

    function loadHandler() {
        setTimeout(doMyStuff, 60000);
    }

    function doMyStuff() { 
        window.location.reload();
    }
  
    // modal open with token id
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('input[name=id]').val(button.data('token-id'));
    }); 

    // print token
    $("body").on("click", ".tokenPrint", function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type:'POST',
            dataType: 'json',
            data: {
                'id' : $(this).attr('data-token-id'),
                '_token':'<?php echo csrf_token() ?>'
            },
            success:function(data)
            {  
                var content = "<style type=\"text/css\">@media print {"+
                       "html, body {display:block;margin:0!important; padding:0 !important;overflow:hidden;display:table;}"+
                       ".receipt-token {width:100vw;height:100vw;text-align:center}"+
                       ".receipt-token h4{margin:0;padding:0;font-size:7vw;line-height:7vw;text-align:center}"+
                       ".receipt-token h1{margin:0;padding:0;font-size:15vw;line-height:20vw;text-align:center}"+
                       ".receipt-token ul{margin:0;padding:0;font-size:7vw;line-height:8vw;text-align:center;list-style:none;}"+
                       "}</style>";
                       
                content += "<div class=\"receipt-token\">";
                content += "<h4>{{ \Session::get('app.title') }}</h4>";
                content += "<h1>"+data.token_no+"</h1>";
                content +="<ul class=\"list-unstyled\">";
                content += "<li><strong>{{ trans('app.department') }} </strong>"+data.department+"</li>";
                content += "<li><strong>{{ trans('app.counter') }} </strong>"+data.counter+"</li>";
                content += "<li><strong>{{ trans('app.officer') }} </strong>"+data.firstname+' '+data.lastname+"</li>";
                if (data.note)
                {
                    content += "<li><strong>{{ trans('app.note') }} </strong>"+data.note+"</li>";
                }
                content += "<li><strong>{{ trans('app.date') }} </strong>"+data.created_at+"</li>";
                content += "</ul>";  
                content += "</div>";   
                
                // print 
                printThis(content);


            }, error:function(err){
                alert('failed!');
            }
        });  
    });
    
})();
</script>
@endpush
 