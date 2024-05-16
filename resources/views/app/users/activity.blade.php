@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Users')}}</h4>

            <div class="heading">
                @can('users_edit')
                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-primary btn-round"><i class="material-icons md-18">edit</i> <span class="d-md-inline d-none">{{__('Edit')}}</span></a>
                @endcan

                @can('users_ban')
                    @if(!$user->banned)
                        <a href="{{route('users.ban', $user->id)}}" class="btn btn-dark btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to ban this user?')}}"><i class="material-icons md-18">warning</i> <span class="d-md-inline d-none">{{__('Ban User')}}</span></a>
                    @endif
                @endcan

                @can('users_delete')
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger btn-round deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this user?")}}"><i class="material-icons md-18">delete</i> <span class="d-md-inline d-none">{{__('Delete')}}</span></button>
                    </form>
                @endcan

                <a href="{{route('users.index')}}" class="btn btn-secondary btn-round"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">{{__('Back To List')}}</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item">{{__('Users')}}</a>
                    <span class="breadcrumb-item active">{{__('Activity Log')}}</span>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card bg-white">
            <div class="card-body">
                @include('app.users.nav')

                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th width="1"></th>
                                <th width="1"></th>
                                <th>{{__('Date Time')}}</th>
                                <th>{{__('Event')}}</th>
                                <th>{{__('IP Address')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($audits->total() == 0)
                                <tr>
                                    <td colspan="5">{{__('No results found.')}}</td>
                                </tr>
                            @else
                                @foreach($audits as $audit)
                                    <tr>
                                        <td width="1">
                                            @can('activitylog_show')
                                                <a href="{{route('activitylog.show', $audit->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('Activity Details')}}">
                                                    <i class="material-icons md-18 text-grey">remove_red_eye</i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td width="1">
                                            @can('activitylog_delete')
                                                <form action="{{ route('activitylog.destroy', $audit->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this activity?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete Activity')}}"><i class="material-icons md-18 text-grey">delete</i></a>
                                                </form>
                                            @endcan
                                        </td>
                                        <td>{{$audit->created_at}}</td>
                                        <td>{{@$audit['event_message']}}</td>
                                        <td>{{$audit->ip_address}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="float-left">{{ $audits->links() }}</div>

                    <div class="float-right text-muted">
                        {{__('Showing')}} {{ $audits->firstItem() }} - {{ $audits->lastItem() }} / {{ $audits->total() }} ({{__('page')}} {{ $audits->currentPage() }} )
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection