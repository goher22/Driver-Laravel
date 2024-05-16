@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Settings')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{__('Settings')}}</span>
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
                @include('app.settings.nav')

                <form method="POST" action="{{route('settings.two_factor_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Enable/Disable 2FA')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="two_factor" @if(setting('auth.two_factor')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="two_factor-settings" @if(!setting('auth.two_factor')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Authy API Key') }}</div>
                            <div class="col-md-3">
                                <input id="authy_api_key" type="text" class="form-control{{ $errors->has('authy_api_key') ? ' is-invalid' : '' }}" name="authy_api_key" value="{{setting('auth.two_factor_api_key')}}"  autofocus>

                                @if ($errors->has('authy_api_key'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('authy_api_key') }}</strong>
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
