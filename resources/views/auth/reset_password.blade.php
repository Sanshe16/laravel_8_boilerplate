@extends('layouts.authapp')

@section('title')
    {{trans('auth.RESET_PASSWORD_TEXT')}}
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
                    <h4>{{__('Reset Password')}} </h4>
                    <h6 class="pb-3">Please enter OTP below that has been sent on your email<b>{{$email}}.</b></h6>
                    <form id="otp_form"  action="">
                        @csrf
                        <div class="row">
                            <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <input type="text" id="pin" name="pin" placeholder="Enter OTP" class="form-control" value="{{$pin}}" required >
                                </div>

                                <div class="form-group">
                                    <input id="password" type="password" placeholder="Enter new Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password-confirm" type="password" placeholder="Enter new Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>

                                <div class="alert alert-success form-group label-floating" id="success_message" style="display: none;">
                                    <strong>{{__('Success!')}}</strong>
                                </div>
                                <div class="alert alert-danger form-group label-floating" id="error_message" style="display: none;">
                                    <strong>{{__('Danger!')}}</strong>
                                </div>
                                <button type="button" onclick="CheckOTP()" class="btn btn-block btn-facebook auth-form-btn">
                                    {{ __('Save New Password') }}
                                </button>
                            </div>
                            </div>
                            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        function CheckOTP(){
            var pin = $("#pin").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var passwordconfirm = $("#password-confirm").val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{route('saveResetPassword')}}",
                data: {pin: pin, email: email, password: password, passwordconfirm: passwordconfirm, _token: token},
                success: function (data) {
                    var status = data.status;
                    if(status == 'success'){
                        $("#success_message").show();
                        $("#error_message").hide();
                        $("#success_message").html("<strong>Success!</strong> "+ data.message);
                        setInterval(function() {
                            window.location.href = "{{route('home')}}";
                        }, 3000);
                    }

                    if(status == 'error'){
                        $("#error_message").show();
                        $("#success_message").hide();
                        $("#error_message").html("<strong>Error!</strong> "+ data.message);
                    }
                    if(status == 'validation_error'){
                        $("#error_message").show();
                        $("#success_message").hide();

                        $("#error_message").html("<strong>Error!</strong> "+ data.message.toString());
                    }
                }
            });
        }
    </script>
@endsection
