@extends('layouts.backend')
@section('title', trans('app.user_list'))
 

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <ul class="row list-inline m-0">
            <li class="col-xs-10 p-0 text-left">
                <h3>{{ trans('app.user_list') }}</h3>
            </li>             
            <li class="col-xs-2 p-0 text-right">
                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#infoModal">
                  <i class="fa fa-info-circle"></i>
                </button>
            </li> 
        </ul>
    </div>

    <div class="panel-body">
        <table class="dataTables-server table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="3">#</th>
                    <td>
                        <label>{{ trans('app.start_date') }}</label>
                        <input type="text" class="datepicker form-control input-sm filter" id="start_date" placeholder="{{ trans('app.start_date') }}" autocomplete="off" style="width:100px" />
                    </td>
                    <td>
                        <label>{{ trans('app.end_date') }}</label>
                        <input type="text" class="datepicker form-control input-sm filter" id="end_date" placeholder="{{ trans('app.end_date') }}" autocomplete="off" style="width:100px"/>
                    </td>
                    <th colspan="8">
                        
                    </th>
                </tr>                        
                <tr>
                    <th></th> 
                    <th> 
                        <select id="user_type" class="select2 filter">
                            <option value="">{{ trans('app.user_type') }}</option>
                            <option value="5">{{trans('app.admin')}}</option>
                            <option value="1">{{trans('app.officer')}}</option>
                            <option value="2">{{trans('app.receptionist')}}</option>
                            <option value="3">{{trans('app.client')}}</option>
                        </select> 
                    </th> 
                    <th></th> 
                    <th></th> 
                    <th> 
                        {{ Form::select('department', $departments, null, ['id'=>'department', 'class'=>'select2 filter', 'placeholder'=> trans('app.department')]) }} 
                    </th>  
                    <th></th> 
                    <th> 
                        <select id="status" class="select2 filter">
                            <option value="">{{ trans('app.status') }}</option>
                            <option value="1">{{trans('app.active')}}</option>
                            <option value="'0'">{{trans('app.deactive')}}</option>
                        </select> 
                    </th>  
                    <th></th> 
                    <th></th> 
                    <th></th> 
                </tr>                      
                <tr>
                    <th>{{ trans('app.photo') }}</th>
                    <th>{{ trans('app.user_type') }}</th> 
                    <th>{{ trans('app.name') }}</th> 
                    <th>{{ trans('app.email') }}</th> 
                    <th>{{ trans('app.department') }}</th>  
                    <th>{{ trans('app.mobile') }}</th> 
                    <th>{{ trans('app.status') }}</th>  
                    <th>{{ trans('app.created_at') }}</th> 
                    <th>{{ trans('app.updated_at') }}</th>  
                    <th width="80"><i class="fa fa-cogs"></i></th> 
                </tr>
            </thead>  
        </table>
    </div>
</div> 

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="infoModalLabel">{{ trans('app.note') }}</h4>
      </div>
      <div class="modal-body">
            <p><strong class="label label-warning"> Note 1 </strong> &nbsp;If you delete a User then, the related tokens are not calling on the Display screen. Because the token is dependent on User ID</p>
            <p><strong class="label label-warning"> Note 2 </strong> &nbsp;If you want to change a User name you must rename the User instead of deleting it. 
            </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
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
        var oTable = $('.dataTables-server');
        oTable.DataTable().destroy();
        var oTable = $('.dataTables-server').DataTable({
            responsive: true, 
            processing: true,
            serverSide: true,
            ajax: {
                url:'<?= url('admin/user/data'); ?>',
                dataType: 'json',
                type    : 'post',
                data    : {
                    _token : '{{ csrf_token() }}', 
                    search: {
                        status     : $('#status').val(),
                        department : $('#department').val(),
                        user_type  : $('#user_type').val(),
                        start_date : $('#start_date').val(),
                        end_date   : $('#end_date').val(),
                    }
                }
            },
            columns: [ 
                { data: 'serial' },
                { data: 'photo' },
                { data: 'user_type' },
                { data: 'name' },
                { data: 'email' },
                { data: 'department' },
                { data: 'mobile' },
                { data: 'status' },
                { data: 'created_at' }, 
                { data: 'updated_at' }, 
                { data: 'options' }  
            ],
            order: [ [0, 'desc'] ], 
            select    : true,
            pagingType: "full_numbers",
            lengthMenu: [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>", 
            columnDefs: [
                { "orderable": false, "targets": [1, 2, 5, 7] }
            ], 
            buttons: [
                { extend:'copy', text:'<i class="fa fa-copy"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend: 'print', text  :'<i class="fa fa-print"></i>', className:'btn-sm', exportOptions: { columns: ':visible',  modifier: { selected: null } }},  
                { extend: 'print',className:'btn-sm', text:'<i class="fa fa-print"></i>  Selected',exportOptions:{columns: ':visible'}},  
                { extend:'excel',  text:'<i class="fa fa-file-excel-o"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'pdf',  text:'<i class="fa fa-file-pdf-o"></i>',  className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'colvis', text:'<i class="fa fa-eye"></i>',className:'btn-sm'} 
            ] 
        });  
    } 
})(); 
</script>
@endpush