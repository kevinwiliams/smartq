@extends('layouts.backend')
@section('title', trans('app.performance_report'))


@section('content')  
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.performance_report') }}</h3>
            </div> 
        </div>
    </div> 

    <div class="panel-body"> 
        {{ Form::open(['url' => 'admin/token/performance', 'class' => 'form-inline mb-0', 'method' => 'get']) }}
        <table class="datatable display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <td>
                        <label>{{ trans('app.start_date') }}</label><br/>
                        <input type="text" name="start_date" value="{{$report->start_date}}" class="datepicker form-control input-sm" id="start_date" placeholder="{{ trans('app.start_date') }}" autocomplete="off" style="width:100px" />
                    </td> 
                    <td>
                        <label>{{ trans('app.end_date') }}</label><br/>
                        <input type="text" name="end_date" value="{{$report->end_date}}" class="datepicker form-control input-sm" id="end_date" placeholder="{{ trans('app.end_date') }}" autocomplete="off" style="width:100px" />
                    </td> 
                    <th colspan="3">
                        <button class="button btn btn-sm btn-success" type="submit">{{ trans('app.request') }}</button>
                    </th>
                </tr> 
                <tr>
                    <th>{{ trans('app.officer') }}</th>
                    <th>Total</th>
                    <th>Stoped</th>
                    <th>Pending</th>
                    <th>Complete</th>
                </tr>  
            </thead> 
            <tbody>
                <?php 
                    $sl = 1; 
                    $grand_total   = 0;
                    $total_stoped  = 0;
                    $total_pending = 0;
                    $total_success = 0;
                ?>
                @if (!empty($tokens))
                    @foreach ($tokens as $token)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td><a href='{{url("admin/user/view/{$token->uid}")}}'>{{$token->officer}}</a></td>
                            <td>{{ $token->total }}</td> 
                            <td>{{ $token->stoped }}</td> 
                            <td>{{ $token->pending }}</td>  
                            <td>{{ $token->success }}</td>
                        </tr> 
                        <?php 
                            $grand_total   += $token->total;
                            $total_stoped  += $token->stoped;
                            $total_pending += $token->pending;
                            $total_success += $token->success;
                        ?>
                    @endforeach
                @endif
            </tbody>
            <tfoot> 
                <tr>
                    <th>#</th>
                    <th>
                        <strong>{{ trans('app.start_date') }}</strong> : {{ (!empty($report->start_date)?date('j M Y h:i a',strtotime($report->start_date)):null) }}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <br/>
                        <strong>{{ trans('app.end_date') }}</strong>&nbsp; : {{ (!empty($report->end_date)?date('j M Y h:i a',strtotime($report->end_date)):null) }}<br/>
                    </th>
                    <th>{{ $grand_total }}</th> 
                    <th>{{ $total_stoped }}</th> 
                    <th>{{ $total_pending }}</th>  
                    <th>{{ $total_success }}</th>
                </tr>  
            </tfoot>
        </table> 
        {{ Form::close() }}
    </div> 
</div>  
@endsection
 
