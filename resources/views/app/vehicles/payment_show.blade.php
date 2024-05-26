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

                @if($payment->validationPhoto('voucher'))
                    @if($payment->status == 'pending')
                        @can('payment_approve')
                            <form id="update-form-{{ $payment->id }}" action="{{ route('vehicles.status_payment', $payment->id) }}" method="POST" class="d-inline">
                                @method('PUT')
                                @csrf
                                <button type="button" class="btn btn-primary btn-round updatePaymentBtn" data-form-id="update-form-{{ $payment->id }}" data-confirm-message-payment="{{ __("Are you sure you want to approve this payment?") }}">
                                    <span class="d-md-inline d-none">{{ __('Approve') }}</span>
                                </button>
                            </form>
                        @endcan
                    @endif
                @else
                    <span class="badge badge-lg badge-danger text-white">{{__('Pending Voucher To Upload')}}</span>
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
            <div class="col-md-9">
                <div class="card bg-white">
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Amount') }}</div>
                            <div class="col-md-8">
                                {{ $payment->amount }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Payment Method') }}</div>
                            <div class="col-md-8">
                                {{ $payment->payment_method }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Payment Date') }}</div>
                            <div class="col-md-8">
                                {{ $payment->payment_date }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Status') }}</div>
                            <div class="col-md-8">
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
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center" id="voucherPhoto">
                            <div class="thumb thumb-rounded thumb-slide mb-2">
                                <div id="voucherPhotoImg">
                                    <img src="{{$payment->urlPhoto('voucher')}}" alt="">
                                </div>
                                @if(!$payment->validationPhoto('voucher'))
                                    <div class="caption">
                                        <span>
                                            <a href="#" id="voucherEditPhoto" class="btn btn-success btn-round"><i class="material-icons">edit</i></a>
                                            @if($user->photo !== null)
                                                <a href="{{route('users.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete user profile photo?')}}"><i class="material-icons md-18">delete</i></a>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <h5>{{__('Voucher')}}</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center d-none" id="voucherUpdatePhoto">
                                <div id="voucher-upload-photo-container"></div>

                                <input name="voucher" type="file" class="custom-file-input profile-photo-input" id="voucherUpload">

                                <button class="btn btn-success btn-round mt-2 voucher-upload-result">
                                    <i class="material-icons">photo</i> 
                                    {{__('Update Photo')}}
                                </button>
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
            const updatePaymenButtons = document.querySelectorAll('.updatePaymentBtn');

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

            updatePaymenButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const formId = this.getAttribute('data-form-id');
                    const confirmMessage = this.getAttribute('data-confirm-message-payment');
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $voucherUploadCrop = $('#voucher-upload-photo-container').croppie({
            url: "{{$vehicle->urlPhoto('voucher')}}",
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
            },
            boundary: {
                width: 250,
                height: 250
            }
        });

        $("#voucherEditPhoto").click(function(){
            $("#voucherPhoto").hide();
            $("#voucherUpdatePhoto").removeClass('d-none');
            $("#voucherUpload").trigger("click");
        });

        $('#voucherUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                $voucherUploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    //console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.voucher-upload-result').on('click', function (ev) {
            $voucherUploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "{{route('payment.update_photo', ['name_file' => 'voucher', 'id' =>$payment->id])}}",
                    type: "POST",
                    data: {"image":resp},
                    success: function (data) {
                        location.reload();
                    }
                });
            });
        });

    </script>

@endsection