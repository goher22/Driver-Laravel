@extends('app.layout')

@section('sub_content')
    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Vehicles')}}</h4>
            <div class="heading">
                @can('vehicles_create')
                    <a href="{{route('vehicles.create')}}" class="btn btn-primary btn-round"><i class="material-icons">add</i> {{__('Add New Vehicle')}}</a>
                @endcan
            </div>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    @if($request->has('new'))
                        <a href="{{route('vehicles.index')}}" class="breadcrumb-item">{{__('Vehicles')}}</a>
                        <span class="breadcrumb-item">{{__('New Vehicles')}}</span>
                    @elseif($request->has('active'))
                        <a href="{{route('vehicles.index')}}" class="breadcrumb-item">{{__('Vehicles')}}</a>
                        <span class="breadcrumb-item">{{__('Active Vehicles')}}</span>
                    @elseif($request->has('banned'))
                        <a href="{{route('vehicles.index')}}" class="breadcrumb-item">{{__('Vehicles')}}</a>
                        <span class="breadcrumb-item">{{__('Banned Vehicles')}}</span>
                    @else
                        <span class="breadcrumb-item active">{{__('Vehicles')}}</span>
                    @endif
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
        </div>

        <div class="card bg-white">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form class="w-100">
                            <div class="input-group bg-light">
                                <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search')}}" @if(!empty($term)) value="{{$term}}" @endif>
                                <div class="input-group-append">
                                    @if(!empty($term))
                                        <a href="{{route('vehicles.index')}}" class="btn btn-light">
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
                                @if($user->isSuperAdmin())
                                    <th width="1"></th>
                                    <th width="1"></th>
                                    <th>{{ __('User') }}</th>
                                @else
                                    <th width="1"></th>
                                @endif
                                <th>{{ __('Make') }}</th>
                                <th>{{ __('Model') }}</th>
                                <th>{{ __('Year') }}</th>
                                <th>{{ __('License Plate') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($vehicles->total() == 0)
                                <tr>
                                    @if($user->isSuperAdmin())
                                        <td colspan="9">{{__('No results found.')}}</td>
                                    @else
                                        <td colspan="5">{{__('No results found.')}}</td>
                                    @endif
                                </tr>
                            @else
                                @foreach($vehicles as $vehicle)
                                    <tr>
                                        <td width="1">
                                            @can('vehicles_show')
                                                <a href="{{route('vehicles.show', $vehicle->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('View Vehicle')}}">
                                                    <i class="material-icons md-18 text-grey">remove_red_eye</i>
                                                </a>
                                            @endcan
                                        </td>
                                        @if($user->isSuperAdmin())
                                            <td width="1">
                                                @can('vehicles_edit')
                                                    <a href="{{route('vehicles.edit', $vehicle->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('Edit Vehicle')}}">
                                                        <i class="material-icons md-18 text-grey">edit</i>
                                                    </a>
                                                @endcan
                                            </td>
                                            <td width="1">
                                                @can('vehicles_delete')
                                                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <a href="#" class="deleteBtn" data-confirm-message="{{__("Are you sure you want to delete this vehicle?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete Vehicle')}}"><i class="material-icons md-18 text-grey">delete</i></a>
                                                    </form>
                                                @endcan
                                            </td>
                                            <td>{{ $vehicle->user->name }}</td>
                                        @else
                                            <td width="1"></td>
                                        @endif
                                        <td>{{ $vehicle->make }}</td>
                                        <td>{{ $vehicle->model }}</td>
                                        <td>{{ $vehicle->year }}</td>
                                        <td>{{ $vehicle->license_plate }}</td>
                                        <td>
                                            @if($vehicle->isStatus())
                                                <span class="badge badge-lg badge-success text-white">{{__('Active')}}</span>
                                            @else
                                                <span class="badge badge-lg badge-danger text-white">{{__('Deactive')}}</span>
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
@endsection