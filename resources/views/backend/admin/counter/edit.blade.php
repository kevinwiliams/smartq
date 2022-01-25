@extends('layouts.backend')
@section('title', trans('app.update_counter'))
 
@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ trans('app.update_counter') }}</h1>
            {{-- <button type="button" onclick="printContent('PrintMe')" class="btn btn-info" ><i class="fa fa-print"></i></button>  --}}
        </div>
    </div>

    <div class="panel-body">
        <div class="col-md-10 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Update information below</h6>
                    <a href="{{ url('admin/counter') }}" class="btn btn-danger btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-undo"></i>
                        </span>
                        <span class="text">Cancel</span>
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
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
    
        </div>
        
    </div> 
</div>  
@endsection


 

