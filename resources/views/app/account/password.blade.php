@extends('app.layout')

@section('sub_content')
	
	<div class="page-header">
        <div class="page-title">
            <h4>{{__('Account Settings')}}</h4>

            <div class="heading">
	            <a href="{{route('account.edit', $user->id)}}" class="btn btn-primary btn-round float-right"><i class="material-icons md-18">edit</i> {{__('Edit Profile')}}</a>
	        </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
                    <span class="breadcrumb-item active">{{__('Password')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
    	@if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

    	<div class="card bg-white">
            <div class="card-body">
                @include('app.account.nav')

                <form method="POST" action="{{ route('account.password_update') }}">
	                @method('PATCH')
	                @csrf
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Current Password') }}</div>
	                    <div class="col-md-4">
	                        <input id="current_password" type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" required>

	                        @if ($errors->has('current_password'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('current_password') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('New Password') }}</div>
	                    <div class="col-md-4">
	                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

	                        @if ($errors->has('password'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('password') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Confirm New Password') }}</div>
	                    <div class="col-md-4">
	                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
	                    </div>
	                </div>

	                <div class="form-group row mb-0">
	                    <div class="col-md-2"></div>
	                    <div class="col-md-8">
	                        <button type="submit" class="btn btn-primary">
	                            {{ __('Change Password') }}
	                        </button>
	                    </div>
	                </div>
	            </form>
            </div>
        </div>
    </div>
@endsection
