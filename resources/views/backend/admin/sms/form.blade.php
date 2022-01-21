@extends('layouts.backend')
@section('title', trans('app.new_sms'))
 
@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.new_sms') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 
        {{ Form::open(['url' => 'admin/sms/new', 'files' => true, 'class'=>'col-md-7 col-sm-8']) }}

            <div class="form-group @error('to') has-error @enderror">
                <label for="to">{{ trans('app.mobile') }} <i class="text-danger">*</i> </label>
                <input type="text" name="to" id="to" class="form-control" placeholder="{{ trans('app.mobile') }}" value="{{ old('to') }}">
                <span class="text-danger">{{ $errors->first('to') }}</span>
            </div>

            <div class="form-group @error('message') has-error @enderror">
                <label for="message">{{ trans('app.message') }}  <i class="text-danger">*</i> </label>
                <textarea name="message" id="message" class="form-control" placeholder="{{ trans('app.message') }}">{{ old('message') }}</textarea>
                <span class="text-danger">{{ $errors->first('message') }}</span>
            </div>
 
            <div class="form-group">
                <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="button btn btn-success" type="submit"><span>{{ trans('app.send') }}</span></button>
            </div> 

        {{ Form::close() }}
    </div>
</div>  
@endsection