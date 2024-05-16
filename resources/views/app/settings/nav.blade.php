<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.app') active @endif" href="{{route('settings.app')}}">{{__('App Settings')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.auth') active @endif" href="{{route('settings.auth')}}">{{__('Auth Settings')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.social') active @endif" href="{{route('settings.social')}}">{{__('Social Auth')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.email') active @endif" href="{{route('settings.email')}}">{{__('Email Settings')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.two-factor') active @endif" href="{{route('settings.two-factor')}}">{{__('Two Factor Authentication')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::getCurrentRoute()->getName() == 'settings.recaptcha') active @endif" href="{{route('settings.recaptcha')}}">{{__('reCAPTCHA')}}</a>
    </li>
</ul>