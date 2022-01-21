@extends('layouts.backend')
@section('title', trans('app.details'))


@section('content')
<div class="panel panel-primary"> 
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-8 text-left">
                <h3>{{ trans('app.details') }}</h3>
            </div>
            <div class="col-sm-4 text-right">
                <button type="button" onclick="printThis('printThis')" class="btn btn-info btn-sm" ><i class="fa fa-print"></i></button> 
            </div>
        </div>
    </div>

    <div class="panel-body" id="printThis">
        <dl class="dl-horizontal">
            <dt>{{ trans('app.sender') }}</dt><dd>{{ $message->sender }}</dd>
            <dt>{{ trans('app.receiver') }}</dt><dd>{{ $message->receiver }}</dd>
            <dt>{{ trans('app.date') }}</dt><dd>{{ (!empty($message->datetime)?date('j M Y h:i a',strtotime($message->datetime)):null) }}</dd>
            <dt>{{ trans('app.subject') }}</dt><dd>{{ $message->subject }}</dd>
            <dt>{{ trans('app.message') }}</dt><dd><p>{{ $message->message }}</p></dd>
            <dt>{{ trans('app.attachment') }}</dt>
            <dd>
                @if(!empty($message->attachment))
                    <a href="{{ url($message->attachment) }}" class="btn btn-xs btn-success" target="_blank" download title="Download"><i class="fa fa-download"></i> {{ trans('app.download') }}</a>
                @else
                    <strong class="text-danger"> {{ trans('app.no_file_found') }}</strong>
                @endif
            </dd>
        </dl>
    </div> 
</div>  
@endsection

