@extends('layouts.backend')
@section('title', trans('app.sent_message'))


@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.sent_message') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <table class="dataTables-server display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('app.photo') }}</th>
                    <th>{{ trans('app.receiver') }}</th>
                    <th>{{ trans('app.subject') }}</th>
                    <th>{{ trans('app.message') }}</th>
                    <th>{{ trans('app.attachment') }}</th>
                    <th> 
                        <label>{{ trans('app.status') }}</label><br/>
                        <select id="status" class="select2 filter">
                            <option value="">{{ trans('app.status') }}</option>
                            <option value="1">{{trans('app.seen')}}</option>
                            <option value="'0'">{{trans('app.not_seen')}}</option>
                        </select> 
                    </th> 
                    <th>{{ trans('app.date') }}</th>
                    <th width="80"><i class="fa fa-cogs"></i></th>
                </tr>
            </thead>  
        </table>
    </div> 
</div> 
@endsection

@push('scripts') 
<script> 
$(document).ready(function(){
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
                url:"{{ url('common/message/sent/data') }}",
                dataType: 'json',
                type    : 'post',
                data    : {
                    _token : '{{ csrf_token() }}', 
                    search: {
                        status     : $('#status').val(),
                    }
                }
            },
            columns: [ 
                { data: 'serial' },
                { data: 'photo' },
                { data: 'receiver' },
                { data: 'subject' },
                { data: 'message' },
                { data: 'attachment' },
                { data: 'sender_status' },
                { data: 'datetime' },
                { data: 'options' }
            ],
            order: [ [0, 'desc'] ], 
            select    : true,
            pagingType: "full_numbers",
            lengthMenu: [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>", 
            columnDefs: [
                { "orderable": false, "targets": [1,6,8] }
            ], 
            buttons: [
                { extend:'copy', footer:true, text:'<i class="fa fa-copy"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend: 'print', footer:true, text:'<i class="fa fa-print"></i>', className:'btn-sm', exportOptions: { columns: ':visible',  modifier: { selected: null } }},  
                { extend: 'print', footer:true, text:'<i class="fa fa-print"></i>  Selected', className:'btn-sm', exportOptions:{columns: ':visible'}},  
                { extend:'excel',  footer:true, text:'<i class="fa fa-file-excel-o"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'pdf',  footer:true, text:'<i class="fa fa-file-pdf-o"></i>',  className:'btn-sm',exportOptions:{columns:':visible'}},
                { extend:'colvis', footer:true, text:'<i class="fa fa-eye"></i>',className:'btn-sm'} 
            ]
        });
    }
}); 
</script>
@endpush