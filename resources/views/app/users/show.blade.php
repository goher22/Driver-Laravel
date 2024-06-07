@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Users')}}</h4>

            <div class="heading">
                @can('users_edit')
                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-primary btn-round"><i class="material-icons md-18">edit</i> <span class="d-md-inline d-none">{{__('Edit')}}</span></a>
                @endcan

                @if(!$user->isSuperAdmin())
                    @can('users_ban')
                        @if(!$user->banned)
                            <a href="{{route('users.ban', $user->id)}}" class="btn btn-dark btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to ban this user?')}}"><i class="material-icons md-18">warning</i> <span class="d-md-inline d-none">{{__('Ban User')}}</span></a>
                        @endif
                    @endcan
                @endif

                @if(!$user->isSuperAdmin())
                    @can('users_delete')
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger btn-round deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this user?")}}"><i class="material-icons md-18">delete</i> <span class="d-md-inline d-none">{{__('Delete')}}</span></button>
                        </form>
                    @endcan
                @endif

                <a href="{{route('users.index')}}" class="btn btn-secondary btn-round"><i class="material-icons md-18">arrow_back</i> <span class="d-md-inline d-none">{{__('Back To List')}}</span></a>
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item">{{__('Users')}}</a>
                    <span class="breadcrumb-item active">{{__('Show User')}}</span>
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
                        @include('app.users.nav')
                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Document') }}</div>
                            <div class="col-md-8">
                                {{ $user->document }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('License Number') }}</div>
                            <div class="col-md-8">
                                {{ $user->license_number }}
                            </div>
                        </div>


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
                            <div class="col-md-2 mb-3">{{ __('Documents') }}</div>
                            <div class="col-md-12 row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center" id="documentUserPhoto">
                                            <div class="thumb thumb-slide mb-2">
                                                <div id="documentUserPhotoImg">
                                                    <img src="{{$user->urlPhoto('documentUser')}}" alt="">
                                                </div>
                                                @can('users_document_edit')
                                                    <div class="caption">
                                                        <span>
                                                            <a href="#" id="documentUserEditPhoto" class="btn btn-success btn-round">
                                                                <i class="material-icons">edit</i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                @endcan
                                            </div>
                                            <h5>{{__('Document')}}</h5>
                                        </div>
                                        <div class="row"> 
                                            <div class="col-md-12 text-center d-none" id="documentUserUpdatePhoto">
                                                    <div class="thumb thumb-slide mb-2">
                                                        <div id="document-user-upload-photo-container"></div>
                                                    </div>

                                                    <input type="file" class="custom-file-input document-user-input" id="documentUserUpload">

                                                    <button class="btn btn-success btn-round mt-2 document-user-upload-result">
                                                        <i class="material-icons">photo</i> {{__('Update Photo')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center" id="lincenseNumbershowPhoto">
                                                <div class="thumb thumb-slide mb-2">
                                                    <div id="lincenseNumberPhotoImg">
                                                        <img src="{{$user->urlPhoto('lincenseNumber')}}" alt="">
                                                    </div>
                                                    @can('users_document_edit')
                                                        <div class="caption">
                                                            <span>
                                                                <a href="#" id="lincenseNumberEditPhoto" class="btn btn-success btn-round"><i class="material-icons">edit</i></a>
                                                            </span>
                                                        </div>
                                                    @endcan
                                                </div>
                                                <h5>{{__('License Number')}}</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center d-none" id="lincenseNumberUpdatePhoto">
                                                    <div class="thumb thumb-slide mb-2">
                                                        <div id="lincense-number-upload-photo-container"></div>
                                                    </div>

                                                    <input type="file" class="custom-file-input lincense-number-input" id="lincenseNumberUpload">

                                                    <button class="btn btn-success btn-round mt-2 lincense-number-upload-result"><i class="material-icons">photo</i> {{__('Update Photo')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    @can('users_ban')
                                        <a href="{{route('users.activate', $user->id)}}" class="confirmBtn" data-confirm-message="{{__('Are you sure you want to activate this user account?')}}">{{__('Activate user account')}}</a>
                                    @endcan
                                @else
                                    @if(setting('auth.email_verification'))
                                        @if($user->email_verified_at == null)
                                            <span class="badge badge-lg badge-dark text-white">{{__('Unconfirmed')}}</span>
                                            <a href="{{route('users.resend', $user->id)}}">{{__('Resend verification link')}}</a>
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

                        <div class="form-group row">
                            <div class="col-md-2">{{ __('Permissions') }}</div>
                            <div class="col-md-10">
                                <table class="table table-striped table-bordered permissions_table">
                                    @foreach($groups as $group)
                                        <tr>
                                            <td>
                                                <h6 class="mb-2 font-weight-bold">{{$group['name']}}</h6>
                                                <div>
                                                    @foreach($group['permissions'] as $perm)
                                                        <label class="mr-4">
                                                            @if($user->hasPermissionTo($perm['id'])) 
                                                                <i class="material-icons md-18 text-success">check_circle</i>
                                                            @else
                                                                <i class="material-icons md-18 text-danger">cancel</i>
                                                            @endif
                                                            {{$perm['display_name'] !== null ? $perm['display_name'] : $perm['name']}}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
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
                                            <a href="{{route('users.delete_photo', $user->id)}}" class="btn btn-danger btn-round confirmBtn" data-confirm-message="{{__('Are you sure you want to delete user profile photo?')}}"><i class="material-icons md-18">delete</i></a>
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
                    url: "{{route('users.update_photo', $user->id)}}",
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


        $("#documentUserEditPhoto").click(function(){
            $("#documentUserPhoto").hide();
            $("#documentUserUpdatePhoto").removeClass('d-none');
            $("#documentUserUpload").trigger("click");
        });

        $('#documentUserUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                var imgHtml = '<img src="' + e.target.result + '" />';
                $("#document-user-upload-photo-container").html(imgHtml);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.document-user-upload-result').on('click', function (ev) {
            var formData = new FormData();
            var fileInput = document.getElementById('documentUserUpload');
            var file = fileInput.files[0];
            
            var reader = new FileReader();
            reader.onload = function(event) {
                formData.append('image', event.target.result);

                $.ajax({
                    url: "{{route('user.update_photo', ['name_file' => 'documentUser', 'id' =>$user->id])}}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var imgHtml = '<img src="' + event.target.result + '" />';
                        $("#documentUserPhotoImg").html(imgHtml);
                        $("#documentUserPhoto").show();
                        $("#documentUserUpdatePhoto").addClass('d-none');
                    }
                });

            };

            reader.readAsDataURL(file);


        });


        $lincenseNumberUploadCrop = $('#lincense-number-upload-photo-container').croppie({
            url: "{{$user->urlPhoto('lincenseNumber')}}",
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

        $("#lincenseNumberEditPhoto").click(function(){
            $("#lincenseNumbershowPhoto").hide();
            $("#lincenseNumberUpdatePhoto").removeClass('d-none');
            $("#lincenseNumberUpload").trigger("click");
        });

        $('#lincenseNumberUpload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                $lincenseNumberUploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    //console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.lincense-number-upload-result').on('click', function (ev) {
            $lincenseNumberUploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "{{route('user.update_photo', ['name_file' => 'lincenseNumber', 'id' =>$user->id])}}",
                    type: "POST",
                    data: {"image":resp},
                    success: function (data) {
                        html = '<img src="' + resp + '" />';
                        $("#lincenseNumberPhotoImg").html(html);
                        $("#lincenseNumbershowPhoto").show();
                        $("#lincenseNumberUpdatePhoto").addClass('d-none');
                    }
                });
            });
        });

    </script>
@endsection
