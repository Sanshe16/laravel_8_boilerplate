@extends('layouts.authapp')

@section('title')
    {{trans('auth.LOGIN_TEXT')}}
@endsection

@section('login_body')

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="brand-logo d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/images/gifter-logo.png')}}" alt="logo" style="width: 80px;" />
                    </div>
                    <h4>Hello! let's get started</h4>
                    <h6 class="font-weight-light">Sign in to continue.</h6>
                    <form class="pt-3" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <input type="email" id="email"class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Your Email" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password" />

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="mt-3">
                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn loding_btn">SIGN IN</button>
                        </div>
                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Keep me signed in
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#restore-password" class="auth-link text-black">Forgot password?</a>
                            @endif
                        </div>

                        <div class="mb-2">
                            <button type="button" class="btn btn-block btn-facebook auth-form-btn" disabled><i class="ti-facebook mr-2"></i>Connect using Facebook</button>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-block btn-google auth-form-btn" disabled><i class="ti-google mr-2"></i>Connect using Google</button>
                        </div>
                        <div class="text-center mt-4 font-weight-light">Don't have an account? <a href="{{'register'}}" class="text-primary">Create</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- forgot password modal start  --}}

<div class="modal fade" id="restore-password" tabindex="-1" role="dialog" aria-labelledby="restore-password" aria-hidden="true">
    <div class="modal-dialog window-popup restore-password-popup" role="document">
        <div class="modal-content m-auto" style="width:80%">
           <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 18px; right: 18px; color: #888da8;">
                <svg class="olymp-close-icon" style="width: 14px; height: 14px;">
                    <use xlink:href="#olymp-close-icon">
                    <svg id="olymp-close-icon" viewBox="0 0 32 32">
                          <path d="M14.222 17.778h3.556v-3.556h-3.556v3.556zM31.084 3.429l-2.514-2.514-10.057 10.057 2.514 2.514 10.057-10.057zM0.916 28.571l2.514 2.514 10.057-10.055-2.516-2.514-10.055 10.055zM18.514 21.029l10.057 10.055 2.514-2.514-10.057-10.055-2.514 2.514zM0.916 3.431l10.057 10.055 2.516-2.514-10.059-10.057-2.514 2.516z"></path>
                    </svg>
                    </use>
                </svg>
            </a>

            <div class="modal-header" style="display: flex; height: 55px;">
                <h6 class="title pt-1" style="text-align: center">{{__('Find your account')}} </h6>
            </div>

            <div class="modal-body" style="margin-top: -1%;">
                <form>
                    <div class="form-group label-floating" style="margin-top: 8px;">
                        <input class="form-control" placeholder="Enter your email" name="forgot_email" id="forgot_email" type="email" value="">
                    </div>
                    <div class="alert alert-success" id="success_message" style="display: none;">
                        <strong>{{__('Success!')}} </strong>
                    </div>
                    <div class="alert alert-danger form-group label-floating" id="error_message" style="display: none;">
                        <strong>{{__('Danger!')}}</strong>
                    </div>
                    <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" onclick="forgotPassword()">{{__('Next')}} </a>

                </form>

            </div>
        </div>
    </div>
</div>
{{-- forgot password modal end  --}}

@section('scripts')
<script>

    function forgotPassword(){
        var email = $("#forgot_email").val();
        let url = "{{ route('resetPassword', ':email')}}";
        url = url.replace(':email', email);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: "POST",
            url: "{{route('forgotPassword')}}",
            data: {email: email},
            success: function (data) {
                var status = data.status;

                console.log(data['email']);

                if(status == 'success'){
                    $("#error_message").hide();
                    $("#success_message").show();
                    $("#success_message").html("<strong>Success!</strong> "+ data.message);
                    setInterval(function() {
                        window.location.href = url;
                    }, 3000);
                }
                if(status == 'error'){
                    $("#error_message").show();
                    $("#success_message").hide();
                    $("#error_message").html("<strong>Error!</strong> "+ data.message);
                }
            }
        });
    }

    $(document).ready(function() {
      $('body').on('keydown','#forgot_email',function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    });
</script>
@endsection
