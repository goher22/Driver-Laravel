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

                <form method="POST" action="{{route('settings.app_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('App Name') }}</div>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{setting('app.name')}}"  autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('Default Language') }}</div>
                        <div class="col-md-2">
                            <select name="locale" class="form-control">
                                <option value="en" @if(setting('app.locale') == "en") selected @endif>English</option>
                                <option value="tr" @if(setting('app.locale') == "tr") selected @endif>Türkçe</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Register Default Role')}}</div>
                        <div class="col-md-2">
                            <select name="default_role" class="form-control">
                                <option value="0" @if(setting('app.default_role') == 0) selected @endif>None</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" @if(setting('app.default_role') == $role->id) selected @endif>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Enable API')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="enable_api" @if(setting('app.enable_api')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Notify admin when new user registered')}}</div>
                        <div class="col-md-8">
                            <label class="switch ">
                                <input type="checkbox" class="success" name="notify_admin_for_new_users" @if(setting('app.notify_admin_for_new_users')) checked @endif>
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