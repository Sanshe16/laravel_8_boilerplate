@extends('layouts.authapp')

@section('title')
    {{trans('auth.REGISTER_TEXT')}}
@endsection

@section('login_body')


<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="brand-logo">
                        <img src="{{asset('assets/images/logo.svg')}}" alt="logo" />
                    </div>
                    <h4>New here?</h4>
                    <h6 class="font-weight-light">Signing up is easy. It only takes a few steps.</h6>
                    <form class="pt-3" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row ml-2 mb-2">
                            <div class="col">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="individual_radio_btn" name="vendor_account_type" value="individual">
                                    <label class="form-check-label ml-1" for="individual_radio_btn">Individual</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="business_radio_btn" name="vendor_account_type" value="business" checked>
                                    <label class="form-check-label ml-1" for="business_radio_btn">Business</label>
                                </div>
                            </div> 
                        </div>

                        <div class="form-group">
                            <input id="first_name" type="text" placeholder="First Name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="name" autofocus>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input id="last_name" type="text" placeholder="Last Name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="name" autofocus>
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <input id="country" type="text" disabled class="form-control @error('country') is-invalid @enderror" name="country" value="Pakistan" autocomplete="country">
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}

                        <div class="form-group">
                            <input id="email" type="email" placeholder="Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="phone_number" type="text" placeholder="Phone Number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" autocomplete="phone_number">
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <input id="store_name" type="text" placeholder="Store Name" class="form-control @error('store_name') is-invalid @enderror" name="store_name" value="{{ old('store_name') }}" autocomplete="store_name" autofocus>
                            @error('store_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}

                        {{-- <div class="mb-4">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Keep me signed in
                                </label>
                            </div>
                        </div> --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn loding_btn">SIGN UP</button>
                        </div>
                        <div class="mt-2 mb-2">
                            <button type="button" class="btn btn-block btn-facebook auth-form-btn" disabled><i class="ti-facebook mr-2"></i>Connect using Facebook</button>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-block btn-google auth-form-btn" disabled><i class="ti-google mr-2" ></i>Connect using Google</button>
                        </div>
                        <div class="text-center mt-4 font-weight-light">Already have an account? <a href="{{route('login')}}" class="text-primary">Login</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('body').on('click', '#business_radio_btn', function() 
    {
        if($('#business_radio_btn').is(':checked')) 
        { 
            console.log('business type radiobutton');
        }                      
    });
    </script>
@endsection