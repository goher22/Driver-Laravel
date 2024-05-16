@extends('layouts.app')

@section('content')

<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-5">
            <h5 class="text-center mb-4">{{ __('Enter Token') }}</h5>
            <div class="card">
                <div class="card-body">
                    @if (session('message'))
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="alert alert-danger">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verify-token') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                <input id="authy_token" type="text" class="form-control{{ $errors->has('authy_token') ? ' is-invalid' : '' }}" name="authy_token" value="{{ old('authy_token') }}"  autofocus>

                                @if ($errors->has('authy_token'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('authy_token') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-10 offset-md-1 text-center">
                                <button type="submit" class="btn btn-orange w-100">
                                    {{ __('Verify') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
