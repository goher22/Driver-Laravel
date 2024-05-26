@extends('app.layout')

@section('sub_content')
    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Vehicles')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('vehicles.index')}}" class="breadcrumb-item">{{__('Vehicles')}}</a>
                    <span class="breadcrumb-item active">{{__('Add New Payment')}}</span>
                    <span class="breadcrumb-item active">{{$vehicle->make}} {{$vehicle->model}} {{$vehicle->license_plate}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card bg-white">
            <div class="card-body">
                <form method="POST" action="{{ route('vehicles.save_payments', $vehicle->id) }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Amount') }}</div>
                        <div class="col-md-8">
                            <input id="amount" type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required autofocus>

                            @if ($errors->has('amount'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Payment Method') }}</div>
                        <div class="col-md-8">
                            <input id="payment_method" type="text" class="form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}" name="payment_method" value="{{ old('payment_method') }}" required autofocus>

                            @if ($errors->has('payment_method'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payment_method') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Payment Date') }}</div>
                        <div class="col-md-8">
                            <input id="payment_date" type="date" class="form-control{{ $errors->has('payment_date') ? ' is-invalid' : '' }}" name="payment_date" value="{{ old('payment_date') }}" required autofocus>

                            @if ($errors->has('payment_date'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payment_date') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-18">add</i> {{ __('Create') }}
                            </button>
                            <a href="{{route('vehicles.show_payments', $vehicle->id)}}" class="btn btn-danger">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection