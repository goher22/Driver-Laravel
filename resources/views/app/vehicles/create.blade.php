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
                    <span class="breadcrumb-item active">{{__('Add New Vehicles')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card bg-white">
            <div class="card-body">
                <form method="POST" action="{{ route('vehicles.store') }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Make') }}</div>
                        <div class="col-md-8">
                            <input id="make" type="text" class="form-control{{ $errors->has('make') ? ' is-invalid' : '' }}" name="make" value="{{ old('make') }}" required autofocus>

                            @if ($errors->has('make'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('make') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Model') }}</div>
                        <div class="col-md-8">
                            <input id="model" type="text" class="form-control{{ $errors->has('model') ? ' is-invalid' : '' }}" name="model" value="{{ old('model') }}" required autofocus>

                            @if ($errors->has('model'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('model') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Year') }}</div>
                        <div class="col-md-8">
                            <input id="year" type="text" class="form-control{{ $errors->has('year') ? ' is-invalid' : '' }}" name="year" value="{{ old('year') }}" required autofocus>

                            @if ($errors->has('year'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('year') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('License Plate') }}</div>
                        <div class="col-md-8">
                            <input id="licensePlate" type="text" class="form-control{{ $errors->has('licensePlate') ? ' is-invalid' : '' }}" name="licensePlate" value="{{ old('licensePlate') }}" required autofocus>

                            @if ($errors->has('licensePlate'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('licensePlate') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Drivers') }}</div>
                        <div class="col-md-3">
                            <select class="form-control {{ $errors->has('driver_id') ? ' is-invalid' : '' }}" name="driver_id">
                                <option value="0">{{__('Select a driver')}}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('driver_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('driver_id') }}</strong>
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
                            <a href="{{route('vehicles.index')}}" class="btn btn-danger">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection