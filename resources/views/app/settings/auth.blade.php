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

                <form method="POST" action="{{route('settings.auth_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Allow Registration')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="allow_registration" @if(setting('auth.allow_registration')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('E-mail Verification')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="email_verification" @if(setting('auth.email_verification')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Show Remember Me')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="remember_me" @if(setting('auth.remember_me')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Show Forgot Password')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="forgot_password" @if(setting('auth.forgot_password')) checked @endif>
                                <span class="slider round"></span>
                            </label>
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
