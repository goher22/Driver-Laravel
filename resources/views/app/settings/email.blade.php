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
                <form method="POST" action="{{route('settings.email_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('Protocol') }}</div>
                        <div class="col-md-3">
                            <select name="MAIL_DRIVER" class="form-control">
                                <option value="sendmail" @if(setting('email.MAIL_DRIVER') == 'sendmail') selected @endif>PHP Mail</option>
                                <option value="smtp" @if(setting('email.MAIL_DRIVER') == 'smtp') selected @endif>SMTP</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('From Name') }}</div>
                        <div class="col-md-3">
                            <input id="MAIL_FROM_NAME" type="text" class="form-control{{ $errors->has('MAIL_FROM_NAME') ? ' is-invalid' : '' }}" name="MAIL_FROM_NAME" value="{{setting('email.MAIL_FROM_NAME')}}"  autofocus>

                            @if ($errors->has('MAIL_FROM_NAME'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('MAIL_FROM_NAME') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('From Address') }}</div>
                        <div class="col-md-3">
                            <input id="MAIL_FROM_ADDRESS" type="text" class="form-control{{ $errors->has('MAIL_FROM_ADDRESS') ? ' is-invalid' : '' }}" name="MAIL_FROM_ADDRESS" value="{{setting('email.MAIL_FROM_ADDRESS')}}"  autofocus>

                            @if ($errors->has('MAIL_FROM_ADDRESS'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('MAIL_FROM_ADDRESS') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div id="smtp_fields" @if(setting('email.MAIL_DRIVER') != 'smtp') class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('SMTP Host') }}</div>
                            <div class="col-md-3">
                                <input id="MAIL_HOST" type="text" class="form-control{{ $errors->has('MAIL_HOST') ? ' is-invalid' : '' }}" name="MAIL_HOST" value="{{setting('email.MAIL_HOST')}}"  autofocus>

                                @if ($errors->has('MAIL_HOST'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('MAIL_HOST') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('SMTP Port') }}</div>
                            <div class="col-md-3">
                                <input id="MAIL_PORT" type="text" class="form-control{{ $errors->has('MAIL_PORT') ? ' is-invalid' : '' }}" name="MAIL_PORT" value="{{setting('email.MAIL_PORT')}}"  autofocus>

                                @if ($errors->has('MAIL_PORT'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('MAIL_PORT') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('SMTP Username') }}</div>
                            <div class="col-md-3">
                                <input id="MAIL_USERNAME" type="text" class="form-control{{ $errors->has('MAIL_USERNAME') ? ' is-invalid' : '' }}" name="MAIL_USERNAME" value="{{setting('email.MAIL_USERNAME')}}"  autofocus>

                                @if ($errors->has('MAIL_USERNAME'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('MAIL_USERNAME') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('SMTP Password') }}</div>
                            <div class="col-md-3">
                                <input id="MAIL_PASSWORD" type="text" class="form-control{{ $errors->has('MAIL_PASSWORD') ? ' is-invalid' : '' }}" name="MAIL_PASSWORD" value="{{setting('email.MAIL_PASSWORD')}}"  autofocus>

                                @if ($errors->has('MAIL_PASSWORD'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('MAIL_PASSWORD') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Encryption') }}</div>
                            <div class="col-md-3">
                                <input id="MAIL_ENCRYPTION" type="text" class="form-control{{ $errors->has('MAIL_ENCRYPTION') ? ' is-invalid' : '' }}" name="MAIL_ENCRYPTION" value="{{setting('email.MAIL_ENCRYPTION')}}"  autofocus>

                                @if ($errors->has('MAIL_ENCRYPTION'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('MAIL_ENCRYPTION') }}</strong>
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

        @if (session('test_email_success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('test_email_success') }}
            </div>
        @endif

        @if (session('test_email_error'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('test_email_error') }}<br><br>
                "{{ session('test_email_error_message') }}"
            </div>
        @endif

        <div class="card bg-white mt-3">
            <div class="card-body">
                <form method="POST" action="{{route('settings.send_test_email')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right"></div>
                        <div class="col-md-3"><strong>{{ __('Send a Test Email') }}</strong></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ __('Send To') }}</div>
                        <div class="col-md-3">
                            <input id="send_to_email" type="text" class="form-control{{ $errors->has('send_to_email') ? ' is-invalid' : '' }}" name="send_to_email" autofocus>

                            @if ($errors->has('send_to_email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('send_to_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row mb-0">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Email') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
