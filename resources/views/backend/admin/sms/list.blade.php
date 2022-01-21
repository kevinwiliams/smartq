@extends('layouts.backend')
@section('title', trans('app.sms_history'))

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.sms_history') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <table class="dataTables-server display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <td>
                        <label>{{ trans('app.start_date') }}</label><br/>
                        <input type="text" class="datepicker form-control input-sm filter" id="start_date" placeholder="{{ trans('app.start_date') }}" autocomplete="off" style="width:100px" />
                    </td>
                    <td>
                        <label>{{ trans('app.end_date') }}</label><br/>
                        <input type="text" class="datepicker form-control input-sm filter" id="end_date" placeholder="{{ trans('app.end_date') }}" autocomplete="off" style="width:100px"/>
                    </td>
                    <th colspan="2">
                        
                    </th>
                </tr> 
                <tr>
                    <th>{{ trans('app.send_to') }}</th>
                    <th>{{ trans('app.message') }}</th>
                    <th>{{ trans('app.date') }}</th>
                    <th width="80"><i class="fa fa-cogs"></i></th>
                </tr>
            </thead>   
        </table>
    </div> 
</div>  


<!-- Modal -->
<div class="modal fade" id="showApiResponse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
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
                url:"{{ url('admin/sms/data') }}",
                dataType: 'json',
                type    : 'post',
                data    : {
                    _token : '{{ csrf_token() }}', 
                    search: {
                        start_date : $('#start_date').val(),
                        end_date   : $('#end_date').val(),
                    }
                }
            },
            columns: [ 
                { data: 'serial' },
                { data: 'to' },
                { data: 'message' },
                { data: 'created_at' },
                { data: 'options' }
            ],  
            order: [ [0, 'desc'] ], 
            select    : true,
            pagingType: "full_numbers",
            lengthMenu: [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>", 
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

 
    // api response/preview
    $('#showApiResponse').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var mobile = button.data('mobile');
        var data   = button.data('data');

        var modal = $(this)
        modal.find('.modal-title').text('New message to ' + mobile);

        result = data;
        // result.success = JSON.parse((data.success),null,2);
        modal.find('.modal-body').html("<pre>"+JSON.stringify((result),null,'\t')+"</pre>"); 
    })

}); 
</script>
@endpush