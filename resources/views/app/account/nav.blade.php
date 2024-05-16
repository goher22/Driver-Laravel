<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'account.index' || Route::getCurrentRoute()->getName() == 'account.edit') active @endif" href="{{route('account.index')}}">{{__('User Details')}}</a>
    </li>
    @if($user->provider == null)
	    <li class="nav-item">
	        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'account.password') active @endif" href="{{route('account.password')}}">{{__('Password')}}</a>
	    </li>
    
        @if(setting('auth.two_factor'))
    	    <li class="nav-item">
    	        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'account.two_factor') active @endif" href="{{route('account.two_factor')}}">{{__('Two Factor Authentication')}}</a>
    	    </li>
        @endif
    @endif
</ul>