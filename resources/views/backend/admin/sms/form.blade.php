@extends('layouts.backend')
@section('title', trans('app.new_sms'))
 
@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="panel-heading">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ trans('app.new_sms') }}</h1>
            </div>
        </div>
    </div>
    <nav class="nav nav-borders">
        <a class="nav-link {{ (Request::is('admin/sms/list') ? 'active' : '') }} ms-0" href="{{ url('admin/sms/list') }}">{{ trans('app.sms_history') }}</a>
        <a class="nav-link {{ (Request::is('admin/sms/new') ? 'active' : '') }}" href="{{ url('admin/sms/new') }}">{{ trans('app.new_sms') }}</a>
    </nav>
    <hr >

    <div class="panel-body"> 
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Enter required fields below</h6>
                    <a href="{{ url('admin/counter') }}" class="btn btn-danger btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-undo"></i>
                        </span>
                        <span class="text">Cancel</span>
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
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

        </div>
    </div>
</div>  
@endsection