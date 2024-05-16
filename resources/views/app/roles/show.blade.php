@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Roles')}}</h4>

            <div class="heading">
                @can('roles_edit')
                    <a href="{{route('roles.edit', $role->id)}}" class="btn btn-primary btn-round"><i class="material-icons md-18">edit</i> <span class="d-md-inline d-none">{{__('Edit')}}</span></a>
                @endcan

                @if($role->id !== 1)
                    @can('roles_delete')
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger btn-round deleteBtn" data-confirm-message="{{__('Are you sure you want to delete this role?')}}"><i class="material-icons md-18">delete</i> <span class="d-md-inline d-none">{{__('Delete')}}</span></button>
                        </form>
                    @endcan
                @endif

                <a href="{{route('roles.index')}}" class="btn btn-secondary btn-round"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">{{__('Back To List')}}</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('roles.index')}}" class="breadcrumb-item">{{__('Roles')}}</a>
                    <span class="breadcrumb-item active">{{__('Role Details')}}</span>
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
                        {{ $role->name }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">{{ __('Permissions') }}</div>
                    <div class="col-md-8">
                        <table class="table table-striped table-bordered permissions_table">
                            @foreach($groups as $group)
                                <tr>
                                    <td>
                                        <h6 class="mb-2 font-weight-bold">{{$group['name']}}</h6>
                                        <div>
                                            @foreach($group['permissions'] as $perm)
                                                <label class="mr-4">
                                                    @if($role->hasPermissionTo($perm['id'])) 
                                                        <i class="material-icons md-18 text-success">check_circle</i>
                                                    @else
                                                        <i class="material-icons md-18 text-danger">cancel</i>
                                                    @endif
                                                    {{$perm['display_name'] !== null ? $perm['display_name'] : $perm['name']}}
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
