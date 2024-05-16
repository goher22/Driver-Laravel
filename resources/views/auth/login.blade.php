@extends('layouts.app')

@section('content')

<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-5">
            <h5 class="text-center mb-4">{{ __('Login') }}</h5>
            <div class="card">
                <div class="card-body">
                    @if (session('message'))
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="alert alert-danger">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                {{ __('E-Mail Address') }}
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                {{ __('Password') }}
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                <div class="form-check float-left">
                                    @if(setting('auth.remember_me'))
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    @endif
                                </div>

                                <div class="float-right">
                                    @if(setting('auth.forgot_password'))
                                        @if (Route::has('password.request'))
                                            <a class="" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(setting('auth.recaptcha') && setting('auth.recaptcha_key'))
                            <div class="form-group row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="g-recaptcha" data-sitekey="{{setting('auth.recaptcha_key')}}"></div>
                                    
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ __('The reCaptcha check is required') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-10 offset-md-1 text-center">
                                <button type="submit" class="btn btn-orange w-100">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr>
                    <div class="text-center">
                        @if(setting('social.facebook'))
                            <a href="{{route('social-login', 'facebook')}}">
                                <i class="fab fa-facebook-square social-icon facebook"></i>
                            </a>
                        @endif

                        @if(setting('social.google'))
                            <a href="{{route('social-login', 'google')}}">
                                <i class="fab fa-google social-icon google"></i>
                            </a>
                        @endif

                        @if(setting('social.twitter'))
                            <a href="{{route('social-login', 'twitter')}}">
                                <i class="fab fa-twitter social-icon twitter"></i>
                            </a>
                        @endif

                        @if(setting('social.linkedin'))
                            <a href="{{route('social-login', 'linkedin')}}">
                                <i class="fab fa-linkedin social-icon linkedin"></i>
                            </a>
                        @endif

                        @if(setting('social.github'))
                            <a href="{{route('social-login', 'github')}}">
                                <i class="fab fa-github social-icon github"></i>
                            </a>
                        @endif

                        @if(setting('social.bitbucket'))
                            <a href="{{route('social-login', 'bitbucket')}}">
                                <i class="fab fa-bitbucket social-icon bitbucket"></i>
                            </a>
                        @endif
                    </div>
                    
                    @if(setting('auth.allow_registration'))
                        <hr>
                        <div class="text-center">
                           {{__('Don\'t have an account?')}} 
                            <a class="" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
