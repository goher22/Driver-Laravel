@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Permissions')}}</h4>

            <div class="heading">
                @can('permissions_edit')
                    <a href="{{route('permissions.edit', $permission->id)}}" class="btn btn-primary btn-round"><i class="material-icons md-18">edit</i> <span class="d-md-inline d-none">{{__('Edit')}}</span></a>
                @endcan

                @can('permissions_delete')
                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger btn-round deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this permission?")}}"><i class="material-icons md-18">delete</i> <span class="d-md-inline d-none">{{__('Delete')}}</span></button>
                    </form>
                @endcan

                <a href="{{route('permissions.index')}}" class="btn btn-secondary btn-round"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">{{__('Back To List')}}</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('permissions.index')}}" class="breadcrumb-item">{{__('Permissions')}}</a>
                    <span class="breadcrumb-item active">{{__('Permission Details')}}</span>
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

        <div class="card bg-white">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Name') }}</div>
                    <div class="col-md-8">
                        {{ $permission->name }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Display Name') }}</div>
                    <div class="col-md-8">
                        {{ $permission->display_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Group Name') }}</div>
                    <div class="col-md-8">
                        {{ $permission->group_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Group Slug') }}</div>
                    <div class="col-md-8">
                        {{ $permission->group_slug }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
