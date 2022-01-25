@extends('layouts.backend')
@section('title', trans('app.add_department'))

@section('content')
<div class="panel panel-primary" id="printMe">
    <div class="panel-heading">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ trans('app.add_department') }}</h1>
            {{-- <button type="button" onclick="printContent('PrintMe')" class="btn btn-info" ><i class="fa fa-print"></i></button>  --}}
        </div>
    </div>

    <div class="panel-body"> 
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Enter required fields below</h6>
                    <a href="{{ url('admin/department') }}" class="btn btn-danger btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-undo"></i>
                        </span>
                        <span class="text">Cancel</span>
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    {{ Form::open(['url' => 'admin/department/create', 'class'=>'col-md-7 col-sm-8']) }}

            <div class="form-group @error('name') has-error @enderror">
                <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label> 
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ trans('app.name') }}" value="{{ old('name') }}">
                <span class="text-danger">{{ $errors->first('name') }}</span>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label for="description">{{ trans('app.description') }} </label>  
                <textarea name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}">{{ old('description') }}</textarea>
                <span class="text-danger">{{ $errors->first('description') }}</span> 
            </div>

            <div class="form-group @error('key') has-error @enderror">
                <label for="key">{{ trans('app.key_for_keyboard_mode') }} <i class="text-danger">*</i></label><br/>
                {{ Form::select('key', $keyList, null, ['placeholder' => trans('app.select_option'), 'class'=>'select2 form-control']) }}<br/>
                <span class="text-danger">{{ $errors->first('key') }}</span>
            </div>

            <div class="form-group @error('status') has-error @enderror">
                <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
                <div id="status"> 
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" {{ (old("status")==1)?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" {{ (old("status")==0)?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div>   

            <div class="form-group">
                <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="button btn btn-success" type="submit"><span>{{ trans('app.save') }}</span></button>
            </div>
        {{ Form::close() }}
                </div>
            </div>
    
        </div>

        
        
    </div>
</div> 
@endsection
