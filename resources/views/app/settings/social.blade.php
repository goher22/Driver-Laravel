@extends('app.layout')

@section('sub_content')

    <div class="page-header">
        <div class="page-title">
            <h4>{{__('Settings')}}</h4>
        </div>

        <div class="breadcrumb-line">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item"><i class="material-icons mr-2">home</i> {{__('Home')}}</a>
                    <span class="breadcrumb-item active">{{__('Settings')}}</span>
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
                @include('app.settings.nav')

                <form method="POST" action="{{route('settings.social_update')}}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Facebook')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="facebook" @if(setting('social.facebook')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="facebook-settings" @if(!setting('social.facebook')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Facebook Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="facebook_client_id" type="text" class="form-control{{ $errors->has('facebook_client_id') ? ' is-invalid' : '' }}" name="facebook_client_id" value="{{setting('social.facebook_client_id')}}"  autofocus>

                                @if ($errors->has('facebook_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('facebook_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Facebook Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="facebook_client_secret" type="text" class="form-control{{ $errors->has('facebook_client_secret') ? ' is-invalid' : '' }}" name="facebook_client_secret" value="{{setting('social.facebook_client_secret')}}"  autofocus>

                                @if ($errors->has('facebook_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('facebook_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchFacebook" name="facebook_register"  @if(setting('social.facebook_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchFacebook">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Google')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="google" @if(setting('social.google')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="google-settings" @if(!setting('social.google')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Google Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="google_client_id" type="text" class="form-control{{ $errors->has('google_client_id') ? ' is-invalid' : '' }}" name="google_client_id" value="{{setting('social.google_client_id')}}"  autofocus>

                                @if ($errors->has('google_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('google_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Google Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="google_client_secret" type="text" class="form-control{{ $errors->has('google_client_secret') ? ' is-invalid' : '' }}" name="google_client_secret" value="{{setting('social.google_client_secret')}}"  autofocus>

                                @if ($errors->has('google_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('google_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchGoogle" name="google_register"  @if(setting('social.google_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchGoogle">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Twitter')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="twitter" @if(setting('social.twitter')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="twitter-settings" @if(!setting('social.twitter')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Twitter Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="twitter_client_id" type="text" class="form-control{{ $errors->has('twitter_client_id') ? ' is-invalid' : '' }}" name="twitter_client_id" value="{{setting('social.twitter_client_id')}}"  autofocus>

                                @if ($errors->has('twitter_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('twitter_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Twitter Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="twitter_client_secret" type="text" class="form-control{{ $errors->has('twitter_client_secret') ? ' is-invalid' : '' }}" name="twitter_client_secret" value="{{setting('social.twitter_client_secret')}}"  autofocus>

                                @if ($errors->has('twitter_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('twitter_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchTwitter" name="twitter_register"  @if(setting('social.twitter_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchTwitter">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('LinkedIn')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="linkedin" @if(setting('social.linkedin')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="linkedin-settings" @if(!setting('social.linkedin')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('LinkedIn Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="linkedin_client_id" type="text" class="form-control{{ $errors->has('linkedin_client_id') ? ' is-invalid' : '' }}" name="linkedin_client_id" value="{{setting('social.linkedin_client_id')}}"  autofocus>

                                @if ($errors->has('linkedin_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('linkedin_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('LinkedIn Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="linkedin_client_secret" type="text" class="form-control{{ $errors->has('linkedin_client_secret') ? ' is-invalid' : '' }}" name="linkedin_client_secret" value="{{setting('social.linkedin_client_secret')}}"  autofocus>

                                @if ($errors->has('linkedin_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('linkedin_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchLinkedin" name="linkedin_register"  @if(setting('social.linkedin_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchLinkedin">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Github')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="github" @if(setting('social.github')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="github-settings" @if(!setting('social.github')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Github Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="github_client_id" type="text" class="form-control{{ $errors->has('github_client_id') ? ' is-invalid' : '' }}" name="github_client_id" value="{{setting('social.github_client_id')}}"  autofocus>

                                @if ($errors->has('github_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('github_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Github Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="github_client_secret" type="text" class="form-control{{ $errors->has('github_client_secret') ? ' is-invalid' : '' }}" name="github_client_secret" value="{{setting('social.github_client_secret')}}"  autofocus>

                                @if ($errors->has('github_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('github_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchGithub" name="github_register"  @if(setting('social.github_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchGithub">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{__('Bitbucket')}}</div>
                        <div class="col-md-8">
                            <label class="switch">
                                <input type="checkbox" class="success social-auth-provider" name="bitbucket" @if(setting('social.bitbucket')) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div id="bitbucket-settings" @if(!setting('social.bitbucket')) class="d-none" @endif>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Bitbucket Client ID') }}</div>
                            <div class="col-md-3">
                                <input id="bitbucket_client_id" type="text" class="form-control{{ $errors->has('bitbucket_client_id') ? ' is-invalid' : '' }}" name="bitbucket_client_id" value="{{setting('social.bitbucket_client_id')}}"  autofocus>

                                @if ($errors->has('bitbucket_client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('bitbucket_client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ __('Bitbucket Client Secret') }}</div>
                            <div class="col-md-3">
                                <input id="bitbucket_client_secret" type="text" class="form-control{{ $errors->has('bitbucket_client_secret') ? ' is-invalid' : '' }}" name="bitbucket_client_secret" value="{{setting('social.bitbucket_client_secret')}}"  autofocus>

                                @if ($errors->has('bitbucket_client_secret'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('bitbucket_client_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitchBitbucket" name="bitbucket_register"  @if(setting('social.bitbucket_register')) checked @endif>
                                    <label class="custom-control-label" for="customSwitchBitbucket">{{ __('Allow Registration') }}</label>
                                </div>
                            </label>
                            </div>
                        </div>
                    </div>
                

                    <div class="form-group row mb-0">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update Settings') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
