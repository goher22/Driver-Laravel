@extends('app.layout')

@section('sub_content')
    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Dashboard')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{__('Dashboard')}}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{route('users.index')}}" class="w-100">
                    <div class="input-group bg-light">
                        <input type="text" name="s" class="form-control searchInput" placeholder="{{__('Search for users')}}" @if(!empty($term)) value="{{$term}}" @endif>
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="material-icons md-18">search</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-left">
                            <h6><a href="{{route('users.index')}}?new">{{__('New Users')}}</a></h6>
                            <h1>{{$users['new_users_count']}}</h1>
                        </div>
                        <i class="material-icons float-right text-info md-5em">group_add</i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-left">
                            <h6><a href="{{route('users.index')}}?active">{{__('Active Users')}}</a></h6>
                            <h1>{{$users['active_users']}}</h1>
                        </div>
                        <i class="material-icons float-right text-success md-5em">supervisor_account</i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-left">
                            <h6><a href="{{route('users.index')}}?banned">{{__('Banned Users')}}</a></h6>
                            <h1>{{$users['banned_users']}}</h1>
                        </div>
                        <i class="material-icons float-right text-danger md-5em">person_outline</i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-left">
                            <h6><a href="{{route('users.index')}}">{{__('Total Users')}}</a></h6>
                            <h1>{{$users['total_users']}}</h1>
                        </div>
                        <i class="material-icons float-right text-primary md-5em">people_outline</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="194"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{__('New Users')}}</div>
                    <div class="card-body">
                       <table class="table table-striped">
                           <tbody>
                                @foreach($users['new_users'] as $user)
                                    <tr>
                                        <td><a href="{{ route('users.show', $user->id) }}">{{$user->name}}</a></td>
                                        <td width="1" class="nowrap">{{$user->created_at}}</td>
                                    </tr>
                                @endforeach
                           </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="map" style="height: 400px; width: 100%;"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrEZitMafKrjugDJ48hnjDSua8qaYo6EQ&callback=initMap" async defer></script>
    <script>
        function initMap() {
            // Coordenadas de la Ciudad de Panamá
            var panamaCityLocation = { lat: 8.9833, lng: -79.5167 };
            
            // Crear el mapa centrado en la Ciudad de Panamá
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12, // Ajusta el nivel de zoom según sea necesario
                center: panamaCityLocation
            });
            
            // Colocar un marcador en la Ciudad de Panamá
            var marker = new google.maps.Marker({
                position: panamaCityLocation,
                map: map
            });
        }

        window.addEventListener('load', function() {
            if (typeof google === 'object' && typeof google.maps === 'object') {
                initMap();
            }
        });
    </script>
    
    <script>
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [@foreach($data as $d)"{{$d['month']}}",@endforeach],
                datasets: [{
                    label: "{{__('New Users')}}",
                    data: [@foreach($data as $d)"{{$d['users']}}",@endforeach],
                    backgroundColor: 'rgba(12, 174, 255, 0.3)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection
