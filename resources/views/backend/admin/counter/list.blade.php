@extends('layouts.backend')
@section('title', trans('app.counter_list'))

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ trans('app.counter_list') }}</h1>
            <button type="button" class="btn btn-warning btn-sm btn-circle" data-toggle="modal" data-target="#infoModal">
                <i class="fa fa-info-circle"></i>
              </button> 
        </div>
    </div>
    <nav class="nav nav-borders">
        <a class="nav-link {{ (Request::is('admin/department') ? 'active' : '') }} ms-0" href="{{ url('admin/department') }}">{{ trans('app.department') }}</a>
        <a class="nav-link {{ (Request::is('admin/counter') ? 'active' : '') }}" href="{{ url('admin/counter') }}">{{ trans('app.counter') }}</a>
        <a class="nav-link {{ (Request::is('admin/user') ? 'active' : '') }}" href="{{ url('admin/user') }}">{{ trans('app.users') }}</a>
    </nav>
    <hr >

    <div class="panel-body">
        <div class="col-sm-10">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ trans('app.counter_list') }}</h6>
                    <a href="{{ url('admin/counter/create') }}" class="btn btn-success btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Create New</span>
                    </a>
                </div>
                <div class="card-body">
                    <table class="datatable table table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('app.counter') }}</th>
                                <th>{{ trans('app.description') }}</th>
                                <th>{{ trans('app.created_at') }}</th>
                                <th>{{ trans('app.updated_at') }}</th>
                                <th>{{ trans('app.status') }}</th>
                                <th width="80"><i class="fa fa-cogs"></i></th>
                            </tr>
                        </thead> 
                        <tbody>
        
                            @if (!empty($counters))
                                <?php $sl = 1 ?>
                                @foreach ($counters as $counter)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $counter->name }}</td>
                                        <td>{{ $counter->description }}</td>
                                        <td>{{ (!empty($counter->created_at)?date('j M Y h:i a',strtotime($counter->created_at)):null) }}</td>
                                        <td>{{ (!empty($counter->updated_at)?date('j M Y h:i a',strtotime($counter->updated_at)):null) }}</td>
                                        <td>{!! (($counter->status==1)?"<span class='badge bg-success text-white'>". trans('app.active') ."</span>":"<span class='badge bg-danger text-white'>". trans('app.deactive') ."</span>") !!}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url("admin/counter/edit/$counter->id") }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url("admin/counter/delete/$counter->id") }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ trans("app.are_you_sure") }}')"><i class="fa fa-times"></i></a>
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
        <p><strong class="label label-warning"> Note 1 </strong> &nbsp;If you delete a Counter then, the related tokens are not calling on the Display screen. Because the token is dependent on Counter ID</p>
        <p><strong class="label label-warning"> Note 2 </strong> &nbsp;If you want to change a Counter name you must rename the Counter instead of deleting it. 
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 
@endsection

 
