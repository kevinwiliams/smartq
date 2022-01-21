@extends('layouts.backend')
@section('title', trans('app.update_user'))

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.update_user') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 
        {{ Form::open(['url' => 'common/setting/profile/edit', 'files' => true, 'class'=>'col-md-7 col-sm-8']) }}

             <div class="form-group @error('firstname') has-error @enderror">
                <label for="firstname">{{ trans('app.firstname') }} <i class="text-danger">*</i></label> 
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="{{ trans('app.firstname') }}" value="{{ old('firstname')?old('firstname'):$user->firstname }}">
                <span class="text-danger">{{ $errors->first('firstname') }}</span>
            </div>

            <div class="form-group @error('lastname') has-error @enderror">
                <label for="lastname">{{ trans('app.lastname') }} <i class="text-danger">*</i></label> 
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="{{ trans('app.lastname') }}" value="{{ old('lastname')?old('lastname'):$user->lastname }}">
                <span class="text-danger">{{ $errors->first('lastname') }}</span>
            </div>

            <div class="form-group @error('email') has-error @enderror">
                <label for="email">{{ trans('app.email') }} <i class="text-danger">*</i></label> 
                <input type="text" name="email" id="email" class="form-control" placeholder="{{ trans('app.email') }}" value="{{ old('email')?old('email'):$user->email }}">
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>

            <div class="form-group @error('password') has-error @enderror">
                <label for="password">{{ trans('app.password') }} <i class="text-danger">*</i></label> 
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ trans('app.password') }}" value="{{ old('password') }}">
                <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>

            <div class="form-group @error('conf_password') has-error @enderror">
                <label for="conf_password">{{ trans('app.conf_password') }} <i class="text-danger">*</i></label> 
                <input type="password" name="conf_password" id="conf_password" class="form-control" placeholder="{{ trans('app.conf_password') }}" value="{{ old('conf_password') }}">
                <span class="text-danger">{{ $errors->first('conf_password') }}</span>
            </div>

            <div class="form-group @error('mobile') has-error @enderror">
                <label for="mobile">{{ trans('app.mobile') }} <i class="text-danger">*</i></label> 
                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="{{ trans('app.mobile') }}" value="{{ old('mobile')?old('mobile'):$user->mobile }}">
                <span class="text-danger">{{ $errors->first('mobile') }}</span>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <img src="{{ asset((session('photo')?session('photo'):$user->photo)) }}" alt="Photo" class="img-thubnail thumbnail" width="140" height="100">
                    <input type="file" name="photo" id="photo">  
                    <input type="hidden" name="old_photo" value="{{ ((session('photo') != null) ? session('photo') : $user->photo) }}"> 
                </div> 

                <div class="col-sm-4"></div> 

                <div class="col-sm-4">
                    <div class="form-group">
                        <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                        <button class="button btn btn-success" type="submit"><span>{{ trans('app.update') }}</span></button> 
                    </div>
                </div>
            </div>

        {{ Form::close() }}
    </div>
</div> 
@endsection


 
