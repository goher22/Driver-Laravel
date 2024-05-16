@extends('app.layout')

@section('sub_content')
	
    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Account Settings')}}</h4>

            <div class="heading">
                <a href="{{route('account.edit', $user->id)}}" class="btn btn-primary btn-round float-right"><i class="material-icons md-18">edit</i> {{__('Edit Profile')}}</a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
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
                        @include('app.account.nav')

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Name') }}</div>
                            <div class="col-md-8">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Phone') }}</div>
                            <div class="col-md-3">
                                {{ $user->phone }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Address') }}</div>
                            <div class="col-md-8">
                                {{ $user->address }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('City') }}</div>
                            <div class="col-md-3">
                                {{ $user->city }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Country') }}</div>
                            <div class="col-md-3">
                                {{ @$user->country->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('E-Mail Address') }}</div>
                            <div class="col-md-8">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Created at') }}</div>
                            <div class="col-md-3">
                                {{$user->created_at}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Status') }}</div>
                            <div class="col-md-8">
                                @if($user->banned)
                                    <span class="badge badge-lg badge-danger text-white">{{__('Banned')}}</span>
                                @else
                                    @if(setting('auth.email_verification'))
                                        @if($user->email_verified_at == null)
                                            <span class="badge badge-lg badge-dark text-white">{{__('Unconfirmed')}}</span>
                                        @else
                                            <span class="badge badge-lg badge-success text-white">{{__('Active')}}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-lg badge-success text-white">{{__('Active')}}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Role') }}</div>
                            <div class="col-md-3">
                                <span class="badge badge-lg badge-secondary text-white">{{@$user->getRoleNames()[0]}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-photo text-center" id="showPhoto">
                            <div class="thumb thumb-rounded thumb-slide mb-2">
                                <div id="profilePhotoImg">
                                    @if($user->photo !== null) 
                                        <img src="{{url('uploads/avatars/'.$user->photo)}}" alt="">
                                    @else
                                        @if($user->social_avatar !== null)
                                            <img src="{{$user->social_avatar}}" alt="">
                                        @else
                                            <img src="{{asset('img/placeholder.png')}}" alt="">
                                        @endif
                                    @endif
                                </div>
                                <div class="caption">
                                    <span>
                                        <a href="#" id="editPhoto" class="btn btn-success btn-round"><i class="material-icons">edit</i></a>
                                        @if($user->photo !== null)
                                            <a href="{{route('account.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete your profile photo?')}}"><i class="material-icons md-18">delete</i></a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <h5>{{$user->name}}</h5>
                            <h6>{{$user->email}}</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center d-none" id="updatePhoto">
                                <div id="upload-photo-container"></div>

                                <input type="file" class="custom-file-input profile-photo-input" id="upload">

                                <button class="btn btn-success btn-round mt-2 upload-result"><i class="material-icons">photo</i> {{__('Update Photo')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $uploadCrop = $('#upload-photo-container').croppie({
        url: "{{$user->photo !== null ? url('uploads/avatars/'.$user->photo) : asset('img/placeholder.png')}}",
        enableExif: true,
        viewport: {
            width: 200,
            height: 200,
            type: 'circle'
        },
        boundary: {
            width: 250,
            height: 250
        }
    });

    $('#upload').on('change', function () { 
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function(){
                //console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
    });


    $('.upload-result').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            $.ajax({
                url: "{{route('account.update_photo')}}",
                type: "POST",
                data: {"image":resp},
                success: function (data) {
                    html = '<img src="' + resp + '" />';
                    $("#profilePhotoImg").html(html);
                    $("#showPhoto").show();
                    $("#updatePhoto").addClass('d-none');
                }
            });
        });
    });

    $("#editPhoto").click(function(){
        $("#showPhoto").hide();
        $("#updatePhoto").removeClass('d-none');
        $("#upload").trigger("click");
    });
    </script>
@endsection
