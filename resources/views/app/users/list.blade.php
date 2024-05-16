@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Users')}}</h4>

            <div class="heading">
                @can('users_create')
                    <a href="{{route('users.create')}}" class="btn btn-primary btn-round"><i class="material-icons">add</i> {{__('Add New User')}}</a>
                @endcan
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    @if($request->has('new'))
                        <a href="{{route('users.index')}}" class="breadcrumb-item">{{__('Users')}}</a>
                        <span class="breadcrumb-item">{{__('New Users')}}</span>
                    @elseif($request->has('active'))
                        <a href="{{route('users.index')}}" class="breadcrumb-item">{{__('Users')}}</a>
                        <span class="breadcrumb-item">{{__('Active Users')}}</span>
                        @elseif($request->has('banned'))
                        <a href="{{route('users.index')}}" class="breadcrumb-item">{{__('Users')}}</a>
                        <span class="breadcrumb-item">{{__('Banned Users')}}</span>
                    @else
                        <span class="breadcrumb-item active">{{__('Users')}}</span>
                    @endif
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

        <div class="card bg-white">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form class="w-100">
                            <div class="input-group bg-light">
                                <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search')}}" @if(!empty($term)) value="{{$term}}" @endif>
                                <div class="input-group-append">
                                     @if(!empty($term))
                                        <a href="{{route('users.index')}}" class="btn btn-light">
                                            <i class="material-icons md-18">close</i>
                                        </a>
                                    @endif
                                    <button class="btn btn-primary">
                                        <i class="material-icons md-18">search</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th width="1"></th>
                                <th width="1"></th>
                                <th width="1"></th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('E-mail')}}</th>
                                <th>{{__('Role')}}</th>
                                <th>{{__('Status')}}</th>
                                <th width="1">{{__('Last Login')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->total() == 0)
                                <tr>
                                    <td colspan="7">{{__('No results found.')}}</td>
                                </tr>
                            @else
                                @foreach($users as $user)
                                    <tr>
                                        <td width="1">
                                            @can('users_show')
                                                <a href="{{route('users.show', $user->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('View User')}}">
                                                    <i class="material-icons md-18 text-grey">remove_red_eye</i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td width="1">
                                            @can('users_edit')
                                                <a href="{{route('users.edit', $user->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('Edit User')}}">
                                                    <i class="material-icons md-18 text-grey">edit</i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td width="1">
                                            @if(!$user->isSuperAdmin())
                                                @can('users_delete')
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this user?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete User')}}"><i class="material-icons md-18 text-grey">delete</i></a>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                        <td>
                                            @if(auth()->user()->can('users_show'))
                                                <a href="{{route('users.show', $user->id)}}">{{$user->name}}</a>
                                            @else
                                                {{$user->name}}
                                            @endif
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td><span class="badge badge-lg badge-secondary text-white">{{@$user->getRoleNames()[0]}}</span></td>
                                        <td>
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
                                        </td>
                                        <td class="nowrap">{{$user->last_login_at}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="float-left">
                        @if(!empty($term))
                            {{ $users->appends(['s' => $term])->links() }}
                        @else
                            {{ $users->links() }}
                        @endif
                    </div>

                    <div class="float-right text-muted">
                        {{__('Showing')}} {{ $users->firstItem() }} - {{ $users->lastItem() }} / {{ $users->total() }} ({{__('page')}} {{ $users->currentPage() }} )
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
