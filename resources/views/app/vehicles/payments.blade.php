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

                @can('payment_create')
                    <a href="{{route('vehicles.create_payments', $vehicle->id)}}" class="btn btn-primary btn-round"><i class="material-icons">add</i> {{__('Add New Payment')}}</a>
                @endcan

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
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th width="1"></th>
                                        <th>{{ __('amount') }}</th>
                                        <th>{{ __('Payment Method') }}</th>
                                        <th>{{ __('Payment Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($payments->count() == 0)
                                        <tr>
                                            @if($user->isSuperAdmin())
                                                <td colspan="5">{{__('No results found.')}}</td>
                                            @else
                                                <td colspan="5">{{__('No results found.')}}</td>
                                            @endif
                                        </tr>
                                    @else
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td width="1">
                                                    @can('vehicles_show')
                                                        <a href="{{route('vehicles.view_payments', ['id' => $vehicle->id, 'id_payment' => $payment->id])}}" data-toggle="tooltip" data-placement="top" title="{{__('View Vehicle')}}">
                                                            <i class="material-icons md-18 text-grey">remove_red_eye</i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td>{{ $payment->amount }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>{{ $payment->payment_date }}</td>
                                                <td>
                                                    @if($payment->validationPhoto('voucher'))
                                                        @if($payment->status == 'pending')
                                                            <span class="badge badge-lg badge-danger text-white">
                                                                {{__('Pending Approval')}}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-lg badge-success text-white">
                                                                {{__('Approve')}}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-lg badge-danger text-white">{{__('Pending Voucher To Upload')}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
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