@extends('layouts.backend')
@section('title', trans('app.user_information'))

@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-8 text-left">
                <h3>{{ trans('app.user_information') }}</h3>
            </div>
            <div class="col-sm-4 text-right">
                <div class="btn-group">
                    @if ($user->user_type != 5)
                    <a href=" {{ url("admin/user/edit/$user->id") }}"  class="btn btn-sm btn-success" ><i class="fa fa-edit"></i></a> 
                    @endif
                    <button type="button" onclick="printThis('printThis')" class="btn btn-sm btn-info" ><i class="fa fa-print"></i></button> 
                </div>
            </div>
        </div>
    </div>

    <div class="panel-body" id="printThis"> 
        <div class="row"> 
            <div class="col-sm-3" align="center"> 
                <img alt="Picture" src="{{ asset((!empty($user->photo)?$user->photo:'public/assets/img/icons/no_user.jpg')) }}" class="img-thumbnail img-responsive">
                <h3>
                    {{ $user->firstname .' '. $user->lastname }}
                </h3>
                <span class="label label-info">{{ auth()->user()->roles($user->user_type) }}</span> 

            </div> 

            <div class="col-sm-9"> 
                <dl class="dl-horizontal">
                    <dt>{{ trans('app.department') }}</dt><dd>{{ ($user->department?$user->department:"N/A") }}</dd>
                    <dt>{{ trans('app.email') }}</dt><dd>{{ $user->email }}</dd>
                    <dt>{{ trans('app.mobile') }}</dt><dd>{{ $user->mobile }}</dd>
                    <dt>{{ trans('app.created_at') }}</dt><dd>{{ (!empty($user->created_at)?date('j M Y h:i a',strtotime($user->created_at)):null) }}</dd>
                    <dt>{{ trans('app.updated_at') }}</dt><dd>{{ (!empty($user->updated_at)?date('j M Y h:i a',strtotime($user->updated_at)):null) }}</dd>
                    <dt>{{ trans('app.status') }}</dt>
                    <dd>
                        @if ($user->status==1)
                        <span class="label label-success">{{ trans('app.active') }}</span>
                        @else
                        <span class="label label-danger">{{ trans('app.deactive') }}</span>
                        @endif
                    </dd>
                </dl> 
            </div>
        </div>  

        <div class="row">
            <div class="col-sm-12 panel-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="active">
                            <th>{{ trans('app.status') }}</th>
                            <td>{{ trans('app.my_token') }}</td>
                            <td>{{ trans('app.generated_by_me') }}</td>
                            <td>{{ trans('app.assigned_to_me') }}</td>
                            <td>{{ trans('app.total') }}</td>
                        </tr>
                    </thead> 
                    <tbody>
                        <tr>
                            <th scope="row" class="active">{{ trans('app.pending') }}</th>
                            <td class="info">{{ !empty($myToken['0'])?$myToken['0']:0 }}</td> 
                            <td class="info">{{ !empty($myToken['1'])?$myToken['1']:0 }}</td> 
                            <td class="info">{{ !empty($myToken['2'])?$myToken['2']:0 }}</td> 
                            <td class="active">{{ @$myToken['0']+@$myToken['1']+@$myToken['2'] }}</td> 
                        </tr> 
                        <tr>
                            <th scope="row" class="active">{{ trans('app.complete') }}</th> 
                            <td class="success">{{ !empty($generatedByMe['0'])?$generatedByMe['0']:0 }}</td> 
                            <td class="success">{{ !empty($generatedByMe['1'])?$generatedByMe['1']:0 }}</td> 
                            <td class="success">{{ !empty($generatedByMe['2'])?$generatedByMe['2']:0 }}</td> 
                            <td class="active">{{ @$generatedByMe['0']+@$generatedByMe['1']+@$generatedByMe['2'] }}</td> 
                        </tr> 
                        <tr>
                            <th scope="row" class="active">{{ trans('app.stop') }}</th> 
                            <td class="danger">{{ !empty($assignedToMe['0'])?$assignedToMe['0']:0 }}</td> 
                            <td class="danger">{{ !empty($assignedToMe['1'])?$assignedToMe['1']:0 }}</td> 
                            <td class="danger">{{ !empty($assignedToMe['2'])?$assignedToMe['2']:0 }}</td> 
                            <td class="active">{{ @$assignedToMe['0']+@$assignedToMe['1']+@$assignedToMe['2'] }}</td> 
                        </tr> 
                    </tbody>
                    <thead>
                        <tr class="active">
                            <th>{{ trans('app.total') }}</th>
                            <td>{{ @$myToken['0']+@$generatedByMe['0']+@$assignedToMe['0'] }}</td> 
                            <td>{{ @$myToken['1']+@$generatedByMe['1']+@$assignedToMe['1'] }}</td> 
                            <td>{{ @$myToken['2']+@$generatedByMe['2']+@$assignedToMe['2'] }}</td> 
                            <td>{{ @$myToken['0']+@$myToken['1']+@$myToken['2']+@$generatedByMe['0']+@$generatedByMe['1']+@$generatedByMe['2']+@$assignedToMe['0']+@$assignedToMe['1']+@$assignedToMe['2'] }}</td> 
                        </tr>
                    </thead> 
                </table>
            </div>
        </div>
    </div> 
</div>  
@endsection

 

