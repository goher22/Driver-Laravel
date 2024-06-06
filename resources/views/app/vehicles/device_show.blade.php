@extends('app.layout')

@section('sub_content')
<div class="page-header">
    <div class="page-title">
        <h4>{{ __('Vehicles') }}</h4>

        <div class="heading">
            @if($user->isSuperAdmin())
                @can('vehicles_edit')
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-round">
                        <i class="material-icons md-18">edit</i>
                        <span class="d-md-inline d-none">{{ __('Edit') }}</span>
                    </a>
                @endcan

                @can('vehicles_delete')
                    <form id="delete-form-{{ $vehicle->id }}" action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger btn-round deleteVehicleBtn" data-form-id="delete-form-{{ $vehicle->id }}" data-confirm-message="{{ __("Are you sure you want to delete this vehicle?") }}">
                            <i class="material-icons md-18">delete</i>
                            <span class="d-md-inline d-none">{{ __('Delete') }}</span>
                        </button>
                    </form>
                @endcan

                <form id="status-form-{{ $vehicle->id }}" action="{{ route('vehicles.status', $vehicle->id) }}" method="POST" class="d-inline">
                    @method('PUT')
                    @csrf
                    <button type="button" class="btn {{ $vehicle->isStatus() ? 'btn-primary' : 'btn-warning' }} btn-round activateBtn" data-form-id="status-form-{{ $vehicle->id }}" data-confirm-message-status="{{ __("Are you sure you want to change the status of the vehicle?") }}">
                        <span class="d-md-inline d-none">{{ $vehicle->isStatus() ? __('Active') : __('Deactive') }}</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary btn-round">
                <i class="material-icons md-18">arrow_back</i>
                <span class="d-md-inline d-none">{{ __('Back To List') }}</span>
            </a>
        </div>
    </div>

    <div class="breadcrumb-line">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-item">
                    <i class="material-icons mr-2">home</i>
                    {{ __('Home') }}
                </a>
                <a href="{{ route('vehicles.index') }}" class="breadcrumb-item">{{ __('Vehicles') }}</a>
                <span class="breadcrumb-item active">{{ __('Show Vehicles') }}</span>
                <span class="breadcrumb-item active">{{ $vehicle->make }} {{ $vehicle->model }} {{ $vehicle->license_plate }}</span>
                <span class="breadcrumb-item active">{{ __('Device') }} {{ $vehicle->id_device ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection