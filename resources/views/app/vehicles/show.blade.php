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
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    @include('app.vehicles.nav')
                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Make') }}</div>
                        <div class="col-md-8">{{ $vehicle->make }}</div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Model') }}</div>
                        <div class="col-md-8">{{ $vehicle->model }}</div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Year') }}</div>
                        <div class="col-md-8">{{ $vehicle->year }}</div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('License Plate') }}</div>
                        <div class="col-md-8">{{ $vehicle->license_plate }}</div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Created at') }}</div>
                        <div class="col-md-3">{{ $vehicle->created_at }}</div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">{{ __('Status') }}</div>
                        <div class="col-md-3">
                            @if($vehicle->isStatus())
                                <span class="badge badge-lg badge-success text-white">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-lg badge-danger text-white">{{ __('Deactive') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.deleteVehicleBtn');
        const activateButtons = document.querySelectorAll('.activateBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-form-id');
                const confirmMessage = this.getAttribute('data-confirm-message');
                bootbox.confirm({
                    message: confirmMessage,
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-primary'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            document.getElementById(formId).submit();
                        }
                    }
                });
            });
        });

        activateButtons.forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-form-id');
                const confirmMessage = this.getAttribute('data-confirm-message-status');
                bootbox.confirm({
                    message: confirmMessage,
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-primary'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            document.getElementById(formId).submit();
                        }
                    }
                });
            });
        });
    });
</script>
@endsection
