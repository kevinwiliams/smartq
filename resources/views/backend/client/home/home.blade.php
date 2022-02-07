@extends('layouts.backend')
@section('title', trans('app.dashboard'))

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <h5 class="text-left">Welcome to SmartQ</h5>
    </div>
    <hr>
    <div class="panel-body">
        @php
        $user = Auth::user()
        @endphp        
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
                        @if($smsalert)
                        <div class="col-md-12 card p-3" id="phoneCard">                            
                            <span>What number should we text to alert you?</span>
                            <div class="form-group">
                              
                                <input type="phone" class="form-control form-control-user" id="phone" aria-describedby="phoneHelp" name="phone" placeholder="(555)555-1234 " value="{{ old('phone', auth()->user()->mobile) }}" autocomplete="off">

                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                            <button id="btnConfirm" class="button btn btn-primary">Next</button>
                        </div>
                        <div class="col-md-12 card p-3" id="codeCard" style="display: none;">
                            <span>Confirm the SMS code we sent below:</span>
                            <input type="text" class="form-control form-control-user" id="code" aria-describedby="codeHelp" name="code" placeholder="555555" value="{{ old('code') }}" autocomplete="off">

                            <span>It might take a few minutes, please be patient</span>
                            
                            <div class="form-group">
                                <button id="activate-step-2" class=" button btn btn-primary mr-3">Next</button>
                                <button class=" button btn btn-warning">Cancel</button>
                            </div>
                        </div>
                        @else
                        <div class="col-md-12 card p-3" id="phoneCard">                            
                            <span>We'll send a password to your email</span>
                            <div class="form-group">
                                <span>{{ $maskedemail }}</span>
                                <input type="hidden" id="phone" name="phone" value="{{ $maskedemail }}">
                            </div>
                            <button id="btnConfirm" class="button btn btn-primary">Next</button>
                        </div>
                        <div class="col-md-12 card p-3" id="codeCard" style="display: none;">
                            <span>Confirm the OTP code we sent below:</span>
                            <input type="text" class="form-control form-control-user" id="code" aria-describedby="codeHelp" name="code" placeholder="555555" value="{{ old('code') }}" autocomplete="off">

                            <span>It might take a few minutes, please be patient</span>
                            
                            <div class="form-group">
                                <button id="activate-step-2" class=" button btn btn-primary mr-3">Next</button>
                                <button class=" button btn btn-warning">Cancel</button>
                            </div>
                        </div>

                        @endif
                    </div>
                </div>   
                <div class="row setup-content text-center" id="step-2">
                    <div class="col-lg-12">
                        <div class="col-md-12 card p-3">
                            <span>Please select below what you will be querying or need our help with:</span>
                            <div class="form-group @error('department_id') has-error @enderror">
                                <label for="department_id">{{ trans('app.department') }} <i class="text-danger">*</i></label><br/>
                                {{ Form::select('department_id', $departments, null, ['placeholder' => 'Select Option', 'class'=>'select2 form-control', 'id'=> 'department_id']) }}<br/>
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div> 
                    

                            <span>Potential wait time <i class="fa fa-clock"></i>&nbsp;<span id="span_wait"></span></span>
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
                            <span id="tkn_position"></span>
                            <h1><span id="tkn_number"></span></h1>
                                <a href="{{ url('client/token/current') }}"> <button id="done" class="button btn btn-success">Finish</button></a>
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

        $('#department_id').select2();

        var navListItems = $('ul.setup-panel li a'),
            allWells = $('.setup-content');

        allWells.hide();

        navListItems.click(function(e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $link = $(this).closest('a');
            // console.log($link);


            if (!$link.hasClass('disabled')) {
                navListItems.closest('a').removeClass('active');
                $link.addClass('active');
                allWells.hide();
                $target.show();
            }
        });

        $('ul.setup-panel li a.active').trigger('click');

        $('#btnConfirm').on('click', function(e) {
            var phone = $("#phone").val();

            if (phone == "") {
                swal("Enter your contact number", {
                    title: 'Error',
                    icon: 'error'
                });
                return;
            }
            var _smsalert = '{{ $smsalert }}';
            var msg = ""
            if (parseInt(_smsalert) == 1){
                msg = "My number is: " + phone;
            }else{
                msg = "My email is: " + phone;
            }
            
            swal(msg, {
                    title: 'Are you sure?',
                    icon: 'warning',
                    buttons: {
                        cancel: "Oops!!!",
                        ok: true
                    }
                })
                .then((value) => {

                    switch (value) {
                        case "ok":
                            $.ajax({
                                type: 'post',
                                url: '{{ URL::to("client/confirmMobile") }}',
                                type:'POST',
                                dataType: 'json',
                                data: {
                                    'phone' : phone,
                                    '_token':'<?php echo csrf_token() ?>'
                                },
                                success: function(data) {
                                    $("#phoneCard").hide();
                                    $("#codeCard").show();
                                }
                            });
                            break;
                    }
                });

            // $('ul.setup-panel li a:eq(1)').removeClass('disabled');
            // $('ul.setup-panel li a[href="#step-2"]').trigger('click');
            //$(this).remove();
        });

        $('#activate-step-2').on('click', function(e) {
            var phone = $("#phone").val();
            var code = $("#code").val();

            if (phone == "") {
                swal("Enter your contact number", {
                    title: 'Error',
                    icon: 'error'
                });
                return;
            }

            if (code == "") {
                swal("Enter your OTP code", {
                    title: 'Error',
                    icon: 'error'
                });
                return;
            }
 
            $.ajax({
                type: 'post',
                url: '{{ URL::to("client/confirmOTP") }}',
                type:'POST',
                dataType: 'json',
                data: {
                    'phone' : phone,
                    'code' : code,
                    '_token':'<?php echo csrf_token() ?>'
                },
                success: function(data) {
                    if(data.status == true){
                        $('ul.setup-panel li a:eq(1)').removeClass('disabled');
                        $('ul.setup-panel li a[href="#step-2"]').trigger('click');  
                    }else{
                        swal("Invalid Code", {
                            title: 'Error',
                            icon: 'error'
                        });
                    }
                                             
                }
            });

        
            //$(this).remove();
        });


        $('#activate-step-3').on('click', function(e) {
            var dept = $("#department_id").find(':selected').val();            

            if (dept == "") {
                swal("Select a department", {
                    title: 'Error',
                    icon: 'error'
                });
                return;
            }

            $.ajax({
                type: 'post',
                url: '{{ URL::to("client/token/client") }}',
                type:'POST',
                dataType: 'json',
                data: {
                    'department_id' : dept,
                    '_token':'<?php echo csrf_token() ?>'
                },
                success: function(data) {
                    if(data.status == true){
                        var msg = "You are #" + data.position + " in the line";
                        $("#tkn_position").text(msg);
                        $("#tkn_number").text(data.token.token_no);
                        $('ul.setup-panel li a:eq(2)').removeClass('disabled');
                        $('ul.setup-panel li a[href="#step-3"]').trigger('click');  
                    }
                                             
                }
            });

        
            //$(this).remove();
        });

        $('#department_id').on('change', function(e) {
            var dept = $(this).find(":selected").val();

            if (phone == "") {
                swal("Enter your contact number", {
                    title: 'Error',
                    icon: 'error'
                });
                return;
            }
 
            $.ajax({
                type: 'post',
                url: '{{ URL::to("client/getwaittime") }}',
                type:'POST',
                dataType: 'json',
                data: {
                    'id' : dept,
                    '_token':'<?php echo csrf_token() ?>'
                },
                success: function(data) {
                    console.log(data);
                  $("#span_wait").text(data);                                             
                }
            });

        });

    });
</script>
@endpush