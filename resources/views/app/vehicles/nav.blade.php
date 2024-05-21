<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'vehicles.show') active @endif" href="{{route('vehicles.show', $user->id)}}">{{__('Vehicles Details')}}</a>
    </li>
</ul>