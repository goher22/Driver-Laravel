@extends('app.layout')

@section('sub_content')
<div class="page-header">
        <div class="page-title">
            <h4>{{__('Vehicles')}}</h4>

            <div class="heading">
                @if($user->isSuperAdmin())
                    @can('vehicles_edit')
                        <a href="{{route('vehicles.edit', $vehicle->id)}}" class="btn btn-primary btn-round"><i class="material-icons md-18">edit</i> <span class="d-md-inline d-none">{{__('Edit')}}</span></a>
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

                <a href="{{route('vehicles.index')}}" class="btn btn-secondary btn-round"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">{{__('Back To List')}}</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('vehicles.index')}}" class="breadcrumb-item">{{__('Vehicles')}}</a>
                    <span class="breadcrumb-item active">{{__('Show Vehicles')}}</span>
                    <span class="breadcrumb-item active">{{$vehicle->make}} {{$vehicle->model}} {{$vehicle->license_plate}}</span>
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
                            <h4>{{__('Documents')}}</h4>
                            <div class="row mb-4">

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center" id="expensiveInsurancePhoto">
                                                <div class="thumb thumb-slide mb-2">
                                                    <div id="expensiveInsurancePhotoImg">
                                                        <img src="{{$vehicle->urlPhoto('expensiveInsurance')}}" alt="">
                                                    </div>
                                                    <div class="caption">
                                                        <span>
                                                            <a href="#" id="expensiveInsuranceEditPhoto" class="btn btn-success btn-round"><i class="material-icons">edit</i></a>
                                                            @if($user->photo !== null)
                                                                <a href="{{route('users.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete user profile photo?')}}"><i class="material-icons md-18">delete</i></a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5>{{__('Expensive Insurance')}}</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center d-none" id="expensiveInsuranceUpdatePhoto">
                                                    <div id="expensive-insurance-upload-photo-container"></div>

                                                    <input type="file" class="custom-file-input profile-photo-input" id="expensiveInsuranceUpload">

                                                    <button class="btn btn-success btn-round mt-2 expensive-insurance-upload-result"><i class="material-icons">photo</i> {{__('Update Photo')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center" id="titleOfTheCarshowPhoto">
                                                <div class="thumb thumb-slide mb-2">
                                                    <div id="titleOfTheCarsPhotoImg">
                                                        <img src="{{$vehicle->urlPhoto('titleOfTheCar')}}" alt="">
                                                    </div>
                                                    <div class="caption">
                                                        <span>
                                                            <a href="#" id="titleOfTheCarsEditPhoto" class="btn btn-success btn-round"><i class="material-icons">edit</i></a>
                                                            @if($user->photo !== null)
                                                                <a href="{{route('users.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete user profile photo?')}}"><i class="material-icons md-18">delete</i></a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5>{{__('Title Of The Car')}}</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center d-none" id="titleOfTheCarsUpdatePhoto">
                                                    <div id="title-of-the-cars-upload-photo-container"></div>

                                                    <input type="file" class="custom-file-input profile-photo-input" id="titleOfTheCarsUpload">

                                                    <button class="btn btn-success btn-round mt-2 title-of-the-cars-upload-result"><i class="material-icons">photo</i> {{__('Update Photo')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4>{{__('Gallery')}}</h4>
                            <div class="row mb-4">
                                @if($user->isSuperAdmin())
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="text-center" id="addGalleryPhoto">
                                                    <div class="thumb thumb-slide mb-2">
                                                        <div id="addGalleryPhotoImg">
                                                            <img src="{{asset('img/img_photo.png')}}" alt="">
                                                        </div>
                                                        <div class="caption">
                                                            <span>
                                                                <a href="#" id="addGalleryEditPhoto" class="btn btn-success btn-round"><i class="material-icons">add</i></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <h5>{{__('Add Photo')}}</h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center d-none" id="updateGalleryPhoto">
                                                        <div id="gallery-upload-photo-container"></div>

                                                        <input type="file" class="custom-file-input profile-photo-input" id="addGalleryUpload">

                                                        <button class="btn btn-success btn-round mt-2 gallery-upload-result"><i class="material-icons">photo</i> {{__('Update Photo')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @foreach ($images as $image)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="thumb thumb-slide mb-2">
                                                        <img src="{{ url('/uploads/vehicles/'.basename($image)) }}" alt="Imagen de Vehículo">
                                                    </div>
                                                    <div class="caption">
                                                        <span>
                                                            @if($user->isSuperAdmin())
                                                                <a href="{{route('users.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete user profile photo?')}}">
                                                                    <i class="material-icons md-18">delete</i>
                                                                </a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $expensiveInsuranceUploadCrop = $('#expensive-insurance-upload-photo-container').croppie({
            url: "{{$vehicle->urlPhoto('expensiveInsurance')}}",
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

        $("#expensiveInsuranceEditPhoto").click(function(){
            $("#expensiveInsurancePhoto").hide();
            $("#expensiveInsuranceUpdatePhoto").removeClass('d-none');
            $("#expensiveInsuranceUpload").trigger("click");
        });

        $('#expensiveInsuranceUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                $expensiveInsuranceUploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    //console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.expensive-insurance-upload-result').on('click', function (ev) {
            $expensiveInsuranceUploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "{{route('vehicles.update_photo', ['name_file' => 'expensiveInsurance', 'id' =>$vehicle->id])}}",
                    type: "POST",
                    data: {"image":resp},
                    success: function (data) {
                        html = '<img src="' + resp + '" />';
                        $("#expensiveInsurancePhotoImg").html(html);
                        $("#expensiveInsurancePhoto").show();
                        $("#expensiveInsuranceUpdatePhoto").addClass('d-none');
                    }
                });
            });
        });

        $titleOfTheCarUploadCrop = $('#title-of-the-cars-upload-photo-container').croppie({
            url: "{{$vehicle->urlPhoto('titleOfTheCar')}}",
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

        $("#titleOfTheCarsEditPhoto").click(function(){
            $("#titleOfTheCarshowPhoto").hide();
            $("#titleOfTheCarsUpdatePhoto").removeClass('d-none');
            $("#titleOfTheCarsUpload").trigger("click");
        });

        $('#titleOfTheCarsUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                $titleOfTheCarUploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    //console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.title-of-the-cars-upload-result').on('click', function (ev) {
            $titleOfTheCarUploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "{{route('vehicles.update_photo', ['name_file' => 'titleOfTheCar', 'id' =>$vehicle->id])}}",
                    type: "POST",
                    data: {"image":resp},
                    success: function (data) {
                        html = '<img src="' + resp + '" />';
                        $("#titleOfTheCarsPhotoImg").html(html);
                        $("#titleOfTheCarshowPhoto").show();
                        $("#titleOfTheCarsUpdatePhoto").addClass('d-none');
                    }
                });
            });
        });

        $addGalleryUploadCrop = $('#gallery-upload-photo-container').croppie({
            url: "{{asset('img/img_photo.png')}}",
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

        $("#addGalleryEditPhoto").click(function(){
            $("#addGalleryPhoto").hide();
            $("#updateGalleryPhoto").removeClass('d-none');
            $("#addGalleryUpload").trigger("click");
        });
        
        $('#addGalleryUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log(e.target.result);
                $addGalleryUploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    //console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.gallery-upload-result').on('click', function (ev) {
            $addGalleryUploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                console.log(resp);
                $.ajax({
                    url: "{{route('vehicles.update_photo', ['name_file' => 'gallery', 'id' =>$vehicle->id])}}",
                    type: "POST",
                    data: {"image":resp},
                    success: function (data) {
                        $("#addGalleryPhoto").show();
                        $("#updateGalleryPhoto").addClass('d-none');
                    }
                });
            })
        });
        

    </script>
@endsection