<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'vehicles.show') active @endif" href="{{route('vehicles.show', $vehicle->id)}}">{{__('Vehicles Details')}}</a>
    </li>
    @can('document_show')
        <li class="nav-item">
            <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'vehicles.show_document') active @endif" href="{{route('vehicles.show_document', $vehicle->id)}}">{{__('Vehicles Documents')}}</a>
        </li>
    @endcan
</ul>