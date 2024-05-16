@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Activity Log')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{__('Activity Log')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="card bg-white mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th width="1"></th>
                                <th width="1"></th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Date Time')}}</th>
                                <th>{{__('Event')}}</th>
                                <th>{{__('IP Address')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($audits->total() == 0)
                                <tr>
                                    <td colspan="6">{{__('No results found.')}}</td>
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
                                        <td>
                                            @if(isset($audit->user))
                                                @if(auth()->user()->can('users_show'))
                                                    <a href="{{route('users.activity', $audit->user->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('Go to user\'s activity log')}}">{{$audit->user->name}}</a>
                                                @else
                                                    {{$audit->user->name}}
                                                @endif
                                            @endif
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
