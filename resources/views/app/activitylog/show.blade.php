@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Activity Log')}}</h4>

            <div class="heading">
                @can('activitylog_delete')
                    <form action="{{ route('activitylog.destroy', $audit->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this activity?")}}"><i class="material-icons md-18">delete</i> <span class="d-md-inline d-none">Delete</span></button>
                    </form>
                @endcan

                <a href="{{route('activitylog.index')}}" class="btn btn-secondary"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">Back To List</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('activitylog.index')}}" class="breadcrumb-item">{{__('Activity Log')}}</a>
                    <span class="breadcrumb-item active">{{__('Activity Details')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card bg-white">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-2">{{ __('User') }}</div>
                    <div class="col-md-8">
                        @if(isset($audit->user))
                            @if(auth()->user()->can('users_show'))
                                <a href="{{route('users.activity', $audit->user->id)}}">{{$audit->user->name}}</a>
                            @else
                                {{$audit->user->name}}
                            @endif
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Date Time') }}</div>
                    <div class="col-md-8">
                        {{$audit->created_at}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Event') }}</div>
                    <div class="col-md-8">
                        {{@$audit['event_message']}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('IP Address') }}</div>
                    <div class="col-md-8">
                        {{$audit->ip_address}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('User Agent') }}</div>
                    <div class="col-md-8">
                        {{$audit->user_agent}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
