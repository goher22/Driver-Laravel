<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'users.show') active @endif" href="{{route('users.show', $user->id)}}">{{__('User Details')}}</a>
    </li>
    @can('users_activity')
        <li class="nav-item">
            <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'users.activity') active @endif" href="{{route('users.activity', $user->id)}}">{{__('Activity Log')}}</a>
        </li>
    @endcan
</ul>