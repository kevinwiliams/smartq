@extends('layouts.backend')
@section('title', trans('app.dashboard'))

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading"><h5 class="text-left">Welcome to SmartQ</h5></div>
    <hr>
    <div class="panel-body"> 
        <div class="row">

            <div class="container">
                <div class="row form-group">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                            <li class="nav-item"><a class="nav-link active" href="#step-1">
                                <h4 class="list-group-item-heading">Step 1</h4>
                                <p class="list-group-item-text">How can we contact you?</p>
                            </a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="#step-2">
                                <h4 class="list-group-item-heading">Step 2</h4>
                                <p class="list-group-item-text">What service are you seeking?</p>
                            </a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="#step-3">
                                <h4 class="list-group-item-heading">Step 3</h4>
                                <p class="list-group-item-text">Joined the queue</p>
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="row setup-content text-center" id="step-1">
                    <div class="col-lg-12">
                        <div class="col-md-12 card p-3">
                            <span>What number should we text to alert you?</span>
                            <div class="form-group">
                                <input type="phone" class="form-control form-control-user"
                                    id="email" aria-describedby="emailHelp" name="email"
                                    placeholder="(555)555-1234 " value="{{ old('email') }}" autocomplete="off">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                            <button id="activate-step-2" class="button btn btn-primary">Next</button>
                        </div>
                    </div>
                </div>
                <div class="row setup-content text-center" id="step-2">
                    <div class="col-lg-12">
                        <div class="col-md-12 card p-3">
                            <span>Please select below what you will be querying or need our help with:</span>

                            <select class="js-example-basic-single" name="state">
                                <option value="AL">Alabama</option>
                                <option value="WY">Wyoming</option>
                              </select>

                            <span>Potential wait time <i class="fa fa-clock"></i> 30 mins</span>
                            <br>
                            <span>Are you still insterested?</span>
                            <div class="form-group">
                                <button id="activate-step-3" class=" button btn btn-primary mr-3">Next</button>
                                <button class=" button btn btn-warning">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row setup-content text-center" id="step-3">
                    <div class="col-lg-12">
                        <div class="col-md-12 card p-3">
                            <span>You are #3 in the line</span>
                            <h1>A10001</h2>
                            <button id="done" class="button btn btn-success">Finish</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div> 
@endsection
@push('scripts')
<script>
    $(document).ready(function() {

        $('.js-example-basic-single').select2();
    
    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $link = $(this).closest('a');
            // console.log($link);
        

       if(!$link.hasClass('disabled')){
            navListItems.closest('a').removeClass('active');
            $link.addClass('active');
            allWells.hide();
            $target.show();
        }
    });
    
    $('ul.setup-panel li a.active').trigger('click');
    
    $('#activate-step-2').on('click', function(e) {
        $('ul.setup-panel li a:eq(1)').removeClass('disabled');
        $('ul.setup-panel li a[href="#step-2"]').trigger('click');
        //$(this).remove();
    })    
    
    $('#activate-step-3').on('click', function(e) {
        $('ul.setup-panel li a:eq(2)').removeClass('disabled');
        $('ul.setup-panel li a[href="#step-3"]').trigger('click');
        //$(this).remove();
    }) 
    
    
});


    </script>
@endpush


