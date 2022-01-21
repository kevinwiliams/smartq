@extends('layouts.backend')
@section('title', trans('app.new_message'))

@section('content') 
<div class="panel panel-primary" id="printMe">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.new_message') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 

        {{ Form::open(['url' => 'common/message', 'files'=>true, 'class'=>'col-md-7 col-sm-8']) }}

            <div class="form-group @error('receiver') has-error @enderror">
                <label for="user">{{ trans('app.receiver') }} </label>
                {{ Form::select('receiver', $userList, old('receiver') , ['placeholder' => trans('app.select_option'), 'class'=>'select2 form-control']) }}
                <span class="text-danger">{{ $errors->first('receiver') }}</span>
            </div> 

            <div class="form-group @error('subject') has-error @enderror">
                <label for="subject">{{ trans('app.subject') }}</label>
                <input type="text" name="subject" id="subject" class="form-control" placeholder="{{ trans('app.subject') }}" value="{{ old('subject') }}">
                <span class="text-danger">{{ $errors->first('subject') }}</span>
            </div> 

            <div class="form-group @error('message') has-error @enderror">
                <label for="message">{{ trans('app.message') }} </label> 
                <textarea name="message" id="message" class="form-control" placeholder="{{ trans('app.message') }}">{{ old('message') }}</textarea>
                <span class="text-danger">{{ $errors->first('message') }}</span> 
            </div>
            
            <div class="form-group @error('attachment') has-error @enderror">
                <label for="file">{{ trans('app.attachment') }}</label>
                <input type="hidden" name="attachment">
                <input type="file" name="file" id="file" class="form-control" placeholder="{{ trans('app.file') }}" value="{{ old('file') }}">
                <div class="progress mt-1">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="10" aria-valuemax="100" style="min-width:2em;">0% 
                  </div>
                 </div>
                <span class="text-danger">{{ $errors->first('attachment') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="btn btn-success" type="submit"><span>{{ trans('app.send') }}</span></button>
            </div>
        {{ Form::close() }}
        
    </div>
</div> 
@endsection

@push('scripts') 
<script type="text/javascript">
$( document ).ready( function ()
{
    $( '#file' ).on('change', function ()
    {
        var file      = $('#file').get( 0 ).files[0];
        var extension = $('#file').val().split('.').pop().toLowerCase();
        var formData  = new FormData();
        formData.append( 'file', file );
        formData.append( '_token', '{{ csrf_token() }}' ); 

        $.ajax( {
            url        : '{{ url("common/message/attachment") }}',
            type       : 'POST',
            contentType: false,
            cache      : false,
            processData: false,
            data       : formData,
            xhr        : function ()
            {
                var jqXHR = null;
                if ( window.ActiveXObject )
                {
                    jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                }
                else
                {
                    jqXHR = new window.XMLHttpRequest();
                }
                //Upload progress
                jqXHR.upload.addEventListener( "progress", function ( evt )
                {
                    if ( evt.lengthComputable )
                    {
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        //Do something with upload progress
                        $(".progress").children().attr( 'style', 'width:'+percentComplete+'%');
                        $(".progress").children().html(percentComplete+ '% Complete');
                    }
                }, false );
                //Download progress
                jqXHR.addEventListener( "progress", function ( evt )
                {
                    if ( evt.lengthComputable )
                    {
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        //Do something with download progress
                        $(".progress").children().attr( 'style',  percentComplete+ '%');
                        $(".progress").children().html(percentComplete+ '% Downloaded');
                    }
                }, false );
                return jqXHR;
            },
            beforeSend : function()
            {        
                if ((file.size/1024) >= 2048) 
                {
                    $("input[name=attachment]").val("");
                    $(".progress").next().html("Max file size 2MB");
                    return false;
                }

                if($.inArray(extension, ['gif','png','jpg','jpeg','bmp','pdf','docx','doc']) == -1) 
                {
                    $("input[name=attachment]").val("");
                    $(".progress").next().html("Sorry, invalid extension.");
                    return false;
                }

            },
            success : function ( data )
            {   
                if (data.status == 200)
                {
                    $("input[name=attachment]").val(data.path);
                    $(".progress").children().attr('style', 'width:100%');
                    $(".progress").children().html('100% Completed');
                    $(".progress").next().html("");
                }
                else
                {
                    $("input[name=attachment]").val("");
                    $(".progress").html( '500! Internal server error.');
                }
            }
        } );
    } );
});
</script>
@endpush
