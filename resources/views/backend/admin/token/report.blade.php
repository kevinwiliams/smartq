@extends('layouts.backend')
@section('title', trans('app.token_report'))

@section('content')  
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.token_report') }}</h3>
            </div> 
        </div>
    </div> 

    <div class="panel-body"> 
        <table class="dataTables-server display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="3">#</th>
                    <td>
                        <label>{{ trans('app.start_date') }}</label><br/>
                        <input type="text" class="datepicker form-control input-sm filter" id="start_date" placeholder="{{ trans('app.start_date') }}" autocomplete="off" style="width:100px" />
                    </td>
                    <td>
                        <label>{{ trans('app.end_date') }}</label><br/>
                        <input type="text" class="datepicker form-control input-sm filter" id="end_date" placeholder="{{ trans('app.end_date') }}" autocomplete="off" style="width:100px"/>
                    </td>
                    <th colspan="10">
                        
                    </th>
                </tr> 
                <tr>
                    <th></th>
                    <th> 
                        {{ Form::select('department', $departments, null, ['id'=>'department', 'class'=>'select2 filter', 'placeholder'=> trans('app.department')]) }} 
                    </th>  
                    <th> 
                        {{ Form::select('counter', $counters, null, ['id'=>'counter', 'class'=>'select2 filter', 'placeholder'=> trans('app.counter')]) }} 
                    </th>   
                    <th> 
                        {{ Form::select('officer', $officers, null, ['id'=>'officer', 'class'=>'select2 filter', 'placeholder'=> trans('app.officer')]) }} 
                    </th>  
                    <th></th>
                    <th></th>
                    <th> 
                        {{ Form::select('status', ["'0'"=>trans("app.pending"), '1'=>trans("app.complete"), '2'=>trans("app.stop")],  null,  ['placeholder' => trans("app.status"), 'id'=> 'status', 'class'=>'select2 filter']) }} 
                    </th>  
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr> 
                <tr>
                    <th>{{ trans('app.token_no') }}</th> 
                    <th>{{ trans('app.department') }}</th>
                    <th>{{ trans('app.counter') }}</th>
                    <th>{{ trans('app.officer') }}</th>
                    <th>{{ trans('app.client_mobile') }}</th>
                    <th>{{ trans('app.note') }}</th> 
                    <th>{{ trans('app.status') }}</th>
                    <th>{{ trans('app.created_by') }}</th>
                    <th>{{ trans('app.created_at') }}</th>
                    <th>{{ trans('app.updated_at') }}</th>
                    <th>{{ trans('app.complete_time') }}</th>
                    <th>{{ trans('app.action') }}</th>
                </tr> 
            </thead>  
        </table>  
    </div> 
</div>  


<!-- Transfer Modal -->
<div class="modal fade transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel">
  <div class="modal-dialog" role="document">
    {{ Form::open(['url' => 'admin/token/transfer', 'class'=>'transferFrm']) }}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="transferModalLabel">{{ trans('app.transfer_a_token_to_another_counter') }}</h4>
      </div>
      <div class="modal-body"> 
        <div class="alert hide"></div>
        <input type="hidden" name="id">
        <p>
            <label for="department_id" class="control-label">{{ trans('app.department') }} </label>
            {{ Form::select('department_id', $departments, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'department_id']) }}
        </p>

        <p>
            <label for="counter_id" class="control-label">{{ trans('app.counter') }} </label>
            {{ Form::select('counter_id', $counters, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'counter_id']) }}
        </p> 

        <p>
            <label for="user_id" class="control-label">{{ trans('app.officer') }} </label>
            {{ Form::select('user_id', $officers, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'user_id']) }}
        </p>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="button btn btn-success" type="submit"><span>{{ trans('app.transfer') }}</span></button>
      </div>
    </div>
    {{ Form::close() }}
  </div>
</div> 
@endsection

@push('scripts') 
<script> 
(function(){
    // DATATABLE
    drawDataTable();

    $("body").on("change",".filter", function(){
        drawDataTable();
    });

    function drawDataTable()
    {   
        $('.dataTables-server').DataTable().destroy();
        $('.dataTables-server').DataTable({
            responsive: true, 
            processing: true,
            serverSide: true,
            ajax: {
                url:'<?= url('admin/token/report/data'); ?>',
                dataType: 'json',
                type    : 'post',
                data    : {
                    _token : '{{ csrf_token() }}', 
                    search: {
                        status     : $('#status').val(),
                        counter    : $('#counter').val(),
                        department : $('#department').val(),
                        officer    : $('#officer').val(),
                        start_date : $('#start_date').val(),
                        end_date   : $('#end_date').val(),
                    }
                }
            },
            columns: [ 
                { data: 'serial' },
                { data: 'token_no' },
                { data: 'department' },
                { data: 'counter' },
                { data: 'officer' },
                { data: 'client_mobile' }, 
                { data: 'note' }, 
                { data: 'status' }, 
                { data: 'created_by' },
                { data: 'created_at' },
                { data: 'updated_at' }, 
                { data: 'complete_time' },
                { data: 'options' }  
            ],  
            order: [ [0, 'desc'] ], 
            select    : true,
            pagingType: "full_numbers",
            lengthMenu: [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>", 
            columnDefs: [
                { "orderable": false, "targets": [12] }
            ], 
            buttons: [
                { extend:'copy', text:'<i class="fa fa-copy"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend: 'print', text  :'<i class="fa fa-print"></i>', className:'btn-sm', exportOptions: { columns: ':visible',  modifier: { selected: null } }},  
                { extend: 'print', text:'<i class="fa fa-print"></i>  Selected', className:'btn-sm', exportOptions:{columns: ':visible'}},  
                { extend:'excel',  text:'<i class="fa fa-file-excel-o"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'pdf',  text:'<i class="fa fa-file-pdf-o"></i>',  className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'colvis', text:'<i class="fa fa-eye"></i>',className:'btn-sm'} 
            ] 
        });   
    } 


    // modal open with token id
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('input[name=id]').val(button.data('token-id'));
    }); 

    // transfer token
    $('body').on('submit', '.transferFrm', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,  
            // cache: false,  
            processData: false,
            data:  new FormData($(this)[0]),
            beforeSend: function() {
                $('.transferFrm').find('.alert')
                    .addClass('hide')
                    .html('');
            },
            success: function(data)
            {
                if (data.status)
                {  
                    $('.transferFrm').find('.alert')
                        .addClass('alert-success')
                        .removeClass('hide alert-danger')
                        .html(data.message);

                    setTimeout(() => { window.location.reload() }, 1500);
                }
                else
                {
                    $('.transferFrm').find('.alert')
                        .addClass('alert-danger')
                        .removeClass('hide alert-success')
                        .html(data.exception);
                }   
            },
            error: function(xhr)
            {
                alert('wait...');
            }
        });

    });


    // print token
    $("body").on("click", ".tokenPrint", function(e) { 
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type:'POST',
            dataType: 'json',
            data: {
                'id' : $(this).attr('data-token-id'),
                '_token':'<?php echo csrf_token() ?>'
            },
            success:function(data)
            {
                var content = "<style type=\"text/css\">@media print {"+
                       "html, body {display:block;margin:0!important; padding:0 !important;overflow:hidden;display:table;}"+
                       ".receipt-token {width:100vw;height:100vw;text-align:center}"+
                       ".receipt-token h4{margin:0;padding:0;font-size:7vw;line-height:7vw;text-align:center}"+
                       ".receipt-token h1{margin:0;padding:0;font-size:15vw;line-height:20vw;text-align:center}"+
                       ".receipt-token ul{margin:0;padding:0;font-size:7vw;line-height:8vw;text-align:center;list-style:none;}"+
                       "}</style>";
                content += "<div class=\"receipt-token\">";
                content += "<h4>{{ \Session::get('app.title') }}</h4>";
                content += "<h1>"+data.token_no+"</h1>";
                content +="<ul class=\"list-unstyled\">";
                content += "<li><strong>{{ trans('app.department') }} </strong>"+data.department+"</li>";
                content += "<li><strong>{{ trans('app.counter') }} </strong>"+data.counter+"</li>";
                content += "<li><strong>{{ trans('app.officer') }} </strong>"+data.firstname+' '+data.lastname+"</li>";
                if (data.note)
                {
                    content += "<li><strong>{{ trans('app.note') }} </strong>"+data.note+"</li>";
                }
                content += "<li><strong>{{ trans('app.date') }} </strong>"+data.created_at+"</li>";
                content += "</ul>";  
                content += "</div>";    
     
                // print 
                printThis(content);

            }, error:function(err){
                alert('failed!');
            }
        });  
    });

})(); 
</script>
@endpush
 