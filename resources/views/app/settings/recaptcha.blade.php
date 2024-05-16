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

                <form method="POST" action="{{route('settings.recaptcha_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Enable/Disable reCAPTCHA')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="recaptcha" @if(setting('auth.recaptcha')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="recaptcha-settings" @if(!setting('auth.recaptcha')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Google reCAPTCHA Key') }}</div>
                            <div class="col-md-3">
                                <input id="recaptcha_key" type="text" class="form-control{{ $errors->has('recaptcha_key') ? ' is-invalid' : '' }}" name="recaptcha_key" value="{{setting('auth.recaptcha_key')}}"  autofocus>

                                @if ($errors->has('recaptcha_key'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('recaptcha_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Google reCAPTCHA Secret') }}</div>
                            <div class="col-md-3">
                                <input id="recaptcha_secret" type="text" class="form-control{{ $errors->has('recaptcha_secret') ? ' is-invalid' : '' }}" name="recaptcha_secret" value="{{setting('auth.recaptcha_secret')}}"  autofocus>

                                @if ($errors->has('recaptcha_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('recaptcha_secret') }}</strong>
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
