@extends('layouts.backend')
@section('title', trans('app.department_list'))


@section('content')
<div class="panel panel-primary">
 
    <div class="panel-heading">
        <ul class="row list-inline m-0">
            <li class="col-xs-10 p-0 text-left">
                <h3>{{ trans('app.department_list') }}</h3>
            </li>             
            <li class="col-xs-2 p-0 text-right">
                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#infoModal">
                  <i class="fa fa-info-circle"></i>
                </button>
            </li> 
        </ul>
    </div>

    <div class="panel-body">
        <div class="col-sm-12">
            <table class="datatable table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('app.name') }}</th>
                        <th>{{ trans('app.description') }}</th>
                        <th>{{ trans('app.key_for_keyboard_mode') }}</th>
                        <th>{{ trans('app.created_at') }}</th>
                        <th>{{ trans('app.updated_at') }}</th>
                        <th>{{ trans('app.status') }}</th>
                        <th width="80"><i class="fa fa-cogs"></i></th>
                    </tr>
                </thead> 
                <tbody>

                    @if (!empty($departments))
                        <?php $sl = 1 ?>
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->description }}</td>
                                <td>{{ $department->key }}</td>
                                <td>{{ (!empty($department->created_at)?date('j M Y h:i a',strtotime($department->created_at)):null) }}</td>
                                <td>{{ (!empty($department->updated_at)?date('j M Y h:i a',strtotime($department->updated_at)):null) }}</td>
                                <td>{!! (($department->status==1)?"<span class='label label-success'>". trans('app.active') ."</span>":"<span class='label label-dander'>". trans('app.deactive') ."</span>") !!}</td>
                                <td>
                                    <div class="btn-group"> 
                                        <a href="{{ url("admin/department/edit/$department->id") }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url("admin/department/delete/$department->id") }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ trans("app.are_you_sure") }}')"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr> 
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> 
    </div> 
</div>  


<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="infoModalLabel"><?= trans('app.note') ?></h4>
      </div>
      <div class="modal-body">
        <p><strong class="label label-warning"> Note 1 </strong> &nbsp;If you delete a Department then, the related tokens are not calling on the Display screen. Because the token is dependent on Department ID</p>
        <p><strong class="label label-warning"> Note 2 </strong> &nbsp;If you want to change a Department name you must rename the Department instead of deleting it. 
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 
@endsection

