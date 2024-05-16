@extends('layouts.app')

@section('content')
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-6">
            <h5 class="text-center mb-4">{{ __('Reset Password') }}</h5>

            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                {{ __('E-Mail Address') }}

                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-10 offset-md-1 text-center">
                                <button type="submit" class="btn btn-orange w-100">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>

                        <hr>
                        <div class="text-center">
                            <a class="" href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                            
                            @if(setting('auth.allow_registration'))
                                |
                                <a class="" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
