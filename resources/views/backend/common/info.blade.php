@section('info.message')
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible animated--fade-in alert-dismissible mt-1 mb-3">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p class="mb-0"><i class="fa fa-check"></i> {{ session('message') }}</p>
    </div>
    @endif 
    @if(session()->has('exception'))
    <div class="alert alert-success alert-dismissible animated--fade-in alert-dismissible mt-1 mb-3">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p class="mb-0"><i class="fa fa-times"></i> {{ session('exception') }}</p>
    </div>
    @endif 
@stop

@section('info.language')
    {{ Form::select('lang', [ 'en' => 'English', 'ar' => 'العَرَبِيَّة', 'tr' => 'Türkçe', 'bn' => 'বাংলা', 'es' => 'Español', 'fr'=>'Français', 'pt'=>'Português', 'te'=>'తెలుగు', 'th' => 'ภาษาไทย', 'vi'=> 'Tiếng Việt' ],  \Session::get('locale') , ['id' => 'lang-select', 'class'=>'select2 form-control']) }}
@stop

@section('info.powered-by')
    Powered by <a href="https://marquisvirgo.com" target="_blank">MVL</a> All rights reserved.
@stop

@section('info.version')
    <span class="label label-primary hidden-xs">v4.1.0</span>
@stop



