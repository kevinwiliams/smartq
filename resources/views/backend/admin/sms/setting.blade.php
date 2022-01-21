@extends('layouts.backend')
@section('title', trans('app.sms_setting'))
 
@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.sms_setting') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 
        {!! Form::open(['url' => 'admin/sms/setting', 'class' => '']) !!}
        <div class="row">
            <div class="col-sm-6">
                {!! Form::hidden('id', $setting->id) !!}

                <div class="form-group @error('provider') has-error @enderror">
                    <label for="provider">{{ trans('app.provider') }}<i class="text-danger">*</i></label><br/>
                    {{ Form::select('provider', ["nexmo"=>"Nexmo", "clickatell"=>"Click A Tell", "robi"=>"Robi","budgetsms"=>"Budget SMS", "campaigntag"=>"Campaign Tag"], (old('provider')?old('provider'):$setting->provider), ["id"=>"provider", "class"=>"select2 form-control"]) }}<br/>
                    <span class="text-danger">{{ $errors->first('provider') }}</span>
                </div>

                <div class="form-group @error('api_key') has-error @enderror">
                    <label for="api_key">{{ trans('app.api_key') }}<i class="text-danger">*</i></label>
                    <input type="text" name="api_key" id="api_key" class="form-control" placeholder="{{ trans('app.api_key') }}" value="{{ old('api_key')?old('api_key'):$setting->api_key }}">
                    <span class="text-danger">{{ $errors->first('api_key') }}</span>
                </div>
                
                <div class="form-group @error('username') has-error @enderror">
                    <label for="username">{{ trans('app.username') }}<i class="text-danger">*</i></label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="{{ trans('app.username') }}" value="{{ old('username')?old('username'):$setting->username }}">
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                </div>

                <div class="form-group @error('password') has-error @enderror">
                    <label for="password">{{ trans('app.password') }}<i class="text-danger">*</i></label>
                    <input type="text" name="password" id="password" class="form-control" placeholder="{{ trans('app.password') }}" value="{{ old('password')?old('password'):$setting->password }}">
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
                
                <div class="form-group @error('from') has-error @enderror">
                    <label for="from">{{ trans('app.from') }}<i class="text-danger">*</i></label>
                    <input type="text" name="from" id="from" class="form-control" placeholder="{{ trans('app.from') }}" value="{{ old('form')?old('form'):$setting->from }}">
                    <span class="text-danger">{{ $errors->first('from') }}</span>
                    <span class="text-info">(number for click-a-tell and any string for others)</span>
                </div>

                <div class="form-group">
                    <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                    <button class="button btn btn-success" type="submit"><span>{{ trans('app.update') }}</span></button> 
                </div>
            </div>

            <div class="col-sm-6">
                <div class="well pb-0">
                    <strong>Available variable for SMS</strong>
                    <dl class="dl-horizontal">
                        <dt>[TOKEN]</dt><dd> - token no</dd>
                        <dt>[MOBILE]</dt><dd> - client mobile</dd>
                        <dt>[DEPARTMENT]</dt><dd> - department name</dd>
                        <dt>[COUNTER]</dt><dd> - counter name</dd>
                        <dt>[OFFICER]</dt><dd> - officer name</dd>
                        <dt>[WAIT]</dt><dd> - officer name</dd>
                        <dt>[DATE]</dt><dd> - date time</dd>
                    </dl>
                </div>

                <div class="form-group @error('sms_template') has-error @enderror">
                    <label for="sms_template">{{ trans('app.sms_template') }} <i class="text-danger">*</i></label>
                    <textarea name="sms_template" id="sms_template" class="form-control" placeholder="{{ trans('app.sms_template') }}" rows="3">{{ old('sms_template')?old('sms_template'):$setting->sms_template }}</textarea>
                    <span class="text-danger">{{ $errors->first('sms_template') }}</span>
                </div>

                <div class="form-group @error('recall_sms_template') has-error @enderror">
                    <label for="recall_sms_template">{{ trans('app.recall_sms_template') }} <i class="text-danger">*</i></label>
                    <textarea name="recall_sms_template" id="recall_sms_template" class="form-control" placeholder="{{ trans('app.recall_sms_template') }}" rows="3">{{ old('recall_sms_template')?old('recall_sms_template'):$setting->recall_sms_template }}</textarea>
                    <span class="text-danger">{{ $errors->first('recall_sms_template') }}</span>
                </div>    
            </div> 
        </div>
        {{ Form::close() }}
    </div>
</div>  
@endsection
