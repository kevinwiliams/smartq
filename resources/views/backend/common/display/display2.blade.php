@extends('layouts.display')
@section('title', trans('app.display_2'))


@section('content') 
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h3>{{ trans('app.display_2') }} <button class="pull-right btn btn-sm btn-primary" onclick="goFullscreen('fullscreen'); return false"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button></h3> 
                <span class="text-danger">(enable full-screen mode and wait 10 seconds to adjust the screen)</span>
            </div> 
        </div>
    </div>

    <div class="panel-body" id="fullscreen" style="color:{{ (!empty($setting->color)?$setting->color:'#ffffff') }}">
 
      <div class="media" style="height:60px;background:#3498db;margin-top:-20px;margin-bottom:10px">
        <div class="media-left hidden-xs">
          <img class="media-object" style="height:59px;" src="{{ asset('public/assets/img/icons/logo.jpg') }}" alt="Logo">
        </div>
        <div class="media-body" style="color:#ffffff">
          <h4 class="media-heading" style="font-size:50px;line-height:60px"><marquee direction="{{ (!empty($setting->direction)?$setting->direction:null) }}">{{ (!empty($setting->message)?$setting->message:null) }}</marquee></h4> 
        </div>
      </div>
      
      <div class="row">  
         <div id="display5"></div>
      </div>

      <div class="panel-footer col-xs-12"> 
        @include('backend.common.info')
        <span class="col-xs-10 text-left">@yield('info.powered-by')</span>
        <span class="col-xs-2 text-right">@yield('info.version')</span>
      </div>
    </div> 
</div>   
@endsection

@push('scripts')
<script type="text/javascript"> 
$(document).ready(function(){
  //get previous token
  var view_token = [];
  var interval = 1000; 

  var display = function()
  {
    var width  = $(window).width();
    var height = $(window).height();
    var isFullScreen = document.fullScreen ||
    document.mozFullScreen ||
    document.webkitIsFullScreen || (document.msFullscreenElement != null);
    if (isFullScreen)
    {
      var width  = $(window).width();
      var height = $(window).height();
    } 
    
    $.ajax({
        type:'post',
        dataType:'json',
        url:'{{ URL::to("common/display5") }}',
        data:
        {
            _token: '<?php echo csrf_token() ?>',
            view_token: view_token,
            width: width,
            height: height
        },
        success:function(data)
        {
            $("#display5").html(data.result); 

            view_token = (data.all_token).map(function(item){
                return {counter: item.counter, token  : item.token} 
            }); 

            //notification sound
            if (data.status)
            {  
                var url  = "{{ URL::to('') }}"; 
                var lang = "{{ in_array(session()->get('locale'), $setting->languages)?session()->get('locale'):'en' }}";
                var player = new Notification;
                player.call(data.new_token, lang, url);
            } 

            setTimeout(display, data.interval);
       }
    });
  }; 

  setTimeout(display, interval);
})
</script>
@endpush

