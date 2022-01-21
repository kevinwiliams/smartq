@extends('layouts.backend')
@section('title', trans('app.update_counter'))
 
@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-8 text-left">
                <h3>{{ trans('app.update_counter') }}</h3>
            </div>
            <div class="col-sm-4 text-right">
                <button type="button" onclick="printContent('PrintMe')" class="btn btn-info" ><i class="fa fa-print"></i></button> 
            </div>
        </div>
    </div>

    <div class="panel-body">
        {{ Form::open(['url' => 'admin/counter/edit', 'class'=>'col-md-7 col-sm-8']) }}

            <input type="hidden" name="id" value="{{ $counter->id }}">
     
            <div class="form-group @error('name') has-error @enderror">
                <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ trans('app.name') }}" value="{{ old('name')?old('name'):$counter->name }}">
                <span class="help-block text-danger">{{ $errors->first('name') }}</span>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label for="description">{{ trans('app.description') }} </label> 
                <textarea name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}">{{ old('description')?old('description'):$counter->description }}</textarea>
                <span class="help-block text-danger">{{ $errors->first('description') }}</span>
            </div>

            <div class="form-group @error('status') has-error @enderror">
                <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
                <div id="status"> 
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" {{ ((old('status') || $counter->status)==1)?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" {{ ((old('status') || $counter->status)==0)?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div>  

            <div class="form-group">
                <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="button btn btn-success" type="submit"><span>{{ trans('app.update') }}</span></button> 
            </div>
        
        {{ Form::close() }} 
    </div> 
</div>  
@endsection


 

