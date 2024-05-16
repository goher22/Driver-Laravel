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
                    <span class="breadcrumb-item active">{{__('Two Factor Authentication')}}</span>
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

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('authy_errors'))
            <div class="alert alert-danger" role="alert">
                @foreach(session('authy_errors') as $field => $message)
		            Authy Error: {{$field}}: {{$message}} <br>
		        @endforeach
            </div>
        @endif

    	<div class="card bg-white">
            <div class="card-body">
                @include('app.account.nav')

                <form method="POST" action="{{ route('account.two_factor_update') }}">
	                @method('PATCH')
	                @csrf
	                <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Enable/Disable 2FA')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="authy_enabled" @if($user->isTwoFactorEnabled() || ($errors->any() && old('authy_enabled') == "on")) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="authy_enabled-settings" @if(!$user->isTwoFactorEnabled() && !$errors->any()) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('E-mail') }}</div>
                            <div class="col-md-3">
                                <input id="authy_email" type="text" class="form-control{{ $errors->has('authy_email') ? ' is-invalid' : '' }}" name="authy_email" value="{{$user->authy_email !== null ? $user->authy_email : $user->email}}"  autofocus>

                                @if ($errors->has('authy_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('authy_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Country') }}</div>
                            <div class="col-md-3">
                                <select id="authy-countries" name="country_code" data-value="{{$user->country_code}}"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Phone') }}</div>
                            <div class="col-md-3">
                                <input id="authy-cellphone" name="authy_phone" class="form-control{{ $errors->has('authy_phone') ? ' is-invalid' : '' }}" type="text" value="{{$user->authy_phone}}" />

                                @if ($errors->has('authy_phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('authy_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

	                <div class="form-group row mb-0">
	                    <div class="col-md-2"></div>
	                    <div class="col-md-8">
	                        <button type="submit" class="btn btn-primary">
	                            {{ __('Update Settings') }}
	                        </button>
	                    </div>
	                </div>
	            </form>
            </div>
        </div>
    </div>
@endsection
