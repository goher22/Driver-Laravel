@extends('app.layout')

@section('sub_content')

	<div class="page-header">
        <div class="page-title">
            <h4>{{__('Account Settings')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
                    <span class="breadcrumb-item active">{{__('Edit Profile')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
    	<div class="card bg-white">
            <div class="card-body">
                @include('app.account.nav')

                <form method="POST" action="{{ route('account.update') }}">
	                @method('PATCH')
	                @csrf
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Name') }}</div>
	                    <div class="col-md-8">
	                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}"  autofocus>

	                        @if ($errors->has('name'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('name') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Phone') }}</div>
	                    <div class="col-md-3">
	                        <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $user->phone }}">

	                        @if ($errors->has('phone'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('phone') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Address') }}</div>
	                    <div class="col-md-8">
	                        <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $user->address }}">

	                        @if ($errors->has('address'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('address') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('City') }}</div>
	                    <div class="col-md-3">
	                        <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $user->city }}">

	                        @if ($errors->has('city'))
	                            <span class="invalid-feedback" role="alert">
	                                <strong>{{ $errors->first('city') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <div class="col-md-2">{{ __('Country') }}</div>
	                    <div class="col-md-3">
	                        <select class="form-control" name="country_id">
	                            <option value="0">{{__('Select a country')}}</option>
	                            @foreach($countries as $country)
	                                <option value="{{$country->id}}" @if($user->country_id == $country->id) selected @endif>{{$country->name}}</option>
	                            @endforeach
	                        </select>
	                    </div>
	                </div>

	                <div class="form-group row mb-0">
	                    <div class="col-md-2"></div>
	                    <div class="col-md-8">
	                        <button type="submit" class="btn btn-primary">
	                            {{ __('Save') }}
	                        </button>
	                        <a href="{{route('account.index')}}" class="btn btn-danger">
	                            {{ __('Cancel') }}
	                        </a>
	                    </div>
	                </div>
	            </form>
            </div>
        </div>
    </div>
@endsection
