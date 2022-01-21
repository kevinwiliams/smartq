@extends('layouts.backend')
@section('title', trans('app.app_setting'))

@section('content')
<div class="panel panel-primary" id="printMe">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.app_setting') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 

        {{ Form::open(['url' => 'admin/setting', 'files' => true, 'class'=>'col-md-7 col-sm-8']) }}

            <input type="hidden" name="id" value="{{ $setting->id }}">
     
            <div class="form-group @error('title') has-error @enderror">
                <label for="title">{{ trans('app.title') }} <i class="text-danger">*</i></label> 
                <input type="text" name="title" id="title" class="form-control" placeholder="{{ trans('app.title') }}" value="{{ old('title')?old(
                'title'):$setting->title }}">
                <span class="text-danger">{{ $errors->first('title') }}</span>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label for="description">{{ trans('app.description') }} </label>
                <textarea name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}">{{ old('description')?old(
                'description'):$setting->description }}</textarea>
                <span class="text-danger">{{ $errors->first('description') }}</span>
            </div>

            <div class="form-group @error('email') has-error @enderror">
                <label for="email">{{ trans('app.email') }}</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="{{ trans('app.email') }}" value="{{ old('email')?old(
                'email'):$setting->email }}">
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>

            <div class="form-group @error('phone') has-error @enderror">
                <label for="phone">{{ trans('app.mobile') }}</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="{{ trans('app.mobile') }}"  value="{{ old('phone')?old(
                'phone'):$setting->phone }}">
                <span class="text-danger">{{ $errors->first('phone') }}</span>
            </div>

            <div class="form-group @error('address') has-error @enderror">
                <label for="address">{{ trans('app.address') }} </label>
                <textarea name="address" id="address" class="form-control" placeholder="{{ trans('app.address') }}">{{ old('address')?old(
                'address'):$setting->address }}</textarea>
                <span class="text-danger">{{ $errors->first('address') }}</span>
            </div>

            <div class="form-group @error('copyright_text') has-error @enderror">
                <label for="copyright_text">{{ trans('app.copyright') }} </label>
                <textarea name="copyright_text" id="copyright_text" class="form-control" placeholder="{{ trans('app.copyright') }}">{{ old('copyright_text')?old(
                'copyright_text'):$setting->copyright_text }}</textarea>
                <span class="text-danger">{{ $errors->first('copyright_text') }}</span>
            </div>

            <div class="form-group @error('language') has-error @enderror">
                @include('backend.common.info')
                <label for="lang-select">{{ trans('app.language') }} </label>
                @yield('language')
                <span class="text-danger">{{ $errors->first('language') }}</span>
            </div> 

            <div class="form-group @error('timezone') has-error @enderror">
                <label for="timezone">{{ trans('app.timezone') }} <i class="text-danger">*</i></label><br/>
                {{ Form::select('timezone', $timezoneList, (old('timezone')?old(
                                'timezone'):$setting->timezone) , [ 'class'=>'select2 form-control', "id"=>'timezone']) }}<br/>
                <span class="text-danger">{{ $errors->first('timezone') }}</span>
            </div> 

            <div class="form-group @error('favicon') has-error @enderror">
                <label for="favicon">{{ trans('app.favicon') }} </label>
                <img src="{{ asset((session('favicon')?session('favicon'):$setting->favicon)) }}" alt="favicon" class="img-thubnail thumbnail" width="50" height="50"> 
                <input type="hidden" name="old_favicon" value="{{ ((session('favicon') != null) ? session('favicon') : $setting->favicon) }}">  
                <input type="file" name="favicon" id="favicon" class="form-control">
                <span class="text-danger">{{ $errors->first('favicon') }}</span>
                <span class="help-block">Diamension: (32x32)px</span>
            </div>

            <div class="form-group @error('logo') has-error @enderror">
                <label for="wlogo">{{ trans('app.logo') }}</label>
                <img src="{{ asset($setting->logo) }}" alt="" class="img-thubnail thumbnail" width="200"> 
                <input type="hidden" name="old_logo" value="{{ $setting->logo }}"> 
                <input type="file" name="logo" id="wlogo">
                <span class="text-danger">{{ $errors->first('logo') }}</span>
                <span class="help-block">Diamension: (250x50)px</span>
            </div>
     
            
            <div class="form-group">
                <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="button btn btn-success" type="submit"><span>{{ trans('app.update') }}</span></button> 
            </div>
        
        {{ Form::close() }}

    </div>
</div> 
@endsection

 