@extends('app.layout')

@section('sub_content')
	
    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Permissions')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('permissions.index')}}" class="breadcrumb-item">{{__('Permissions')}}</a>
                    <span class="breadcrumb-item active">{{__('Edit Permission')}}</span>
                    <span class="breadcrumb-item active">{{$permission->name}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card bg-white">
            <div class="card-body">
                <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Name') }}</div>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $permission->name }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Display Name') }}</div>
                        <div class="col-md-8">
                            <input id="display_name" type="text" class="form-control{{ $errors->has('display_name') ? ' is-invalid' : '' }}" name="display_name" value="{{ $permission->display_name }}">

                            @if ($errors->has('display_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('display_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Group Name') }}</div>
                        <div class="col-md-8">
                            <input id="group_name" type="text" class="form-control{{ $errors->has('group_name') ? ' is-invalid' : '' }}" name="group_name" value="{{ $permission->group_name }}">

                            @if ($errors->has('group_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('group_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                            <a href="{{route('permissions.show', $permission->id)}}" class="btn btn-danger">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
