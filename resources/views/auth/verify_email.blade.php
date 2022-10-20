@extends('layouts.authapp')

@section('title')
    {{trans('auth.VERIFY_EMAIL')}}
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
                        <h4>{{__('Thank you for registration')}} </h4>
                        <p>Please check your email that has been sent you on your email <b> {{isset($user['email']) ? $user['email'] : ''}} </b> for email confirmation. </p>
                       
                        <form id="otp_form"  action="">
                            @csrf
                            <div class="row">
                                <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter OTP" value="" required>
                                    </div>
                                    <div class="alert alert-success" id="success_message" style="display: none;">
                                        <strong>{{__('Success!')}} </strong>
                                    </div>
                                    <div class="alert alert-danger" id="error_message" style="display: none;">
                                        <strong>{{__('Danger!')}} </strong>
                                    </div>
                                    @php
                                        if(isset($user) && !is_null($user) && !is_null($user->otp_resend_time))
                                        {
                                            $init = Carbon\Carbon::parse($user->otp_resend_time)->diffInSeconds(Carbon\Carbon::now());
                                            $minutes = floor(($init / 60) % 60);
                                            $seconds = $init % 60;
                                            echo '<a href="javascript:void(0);" onclick="resendOTP()" id="resendotp" class="otp_activate_palsome" style="float:right;color: #3B5998;font-weight: 500;" data-resend-time="'.Carbon\Carbon::parse($user->otp_resend_time)->diffInSeconds(Carbon\Carbon::now()).'" disabled> Resend OTP in <span id="__otp__resend_time" >'.$minutes .' minutes '.$seconds.' seconds '.'</span></a>';
                                        }
                                        elseif (isset($user) && !is_null($user) && is_null($user->otp_resend_time))
                                        {
                                            echo '<a href="javascript:void(0);" onclick="resendOTP()" id="resendotp" class="otp_activate_palsome" style=" float:right; color: #3B5998;font-weight: 500;" data-resend-time="0"> Resend OTP </a>';
                                        }
                                        else
                                        {
                                            echo '<a href="javascript:void(0);" onclick="resendOTP()" id="resendotp" class="otp_activate_palsome" style="float:right;color: #3B5998;font-weight: 500;" data-resend-time="0"> Resend OTP </a>';
                                        }
                                    @endphp
                                    {!! nl2br(isset($user) && !is_null($user) ? '<p style="color:black">Attempts left: <span id="__otp_attempts">'.$user->otp_attempts.'</span></p>' : '' )!!}

                                    @php
                                        if(isset($user) && !is_null($user) && !is_null($user->otp_time))
                                            {
                                                $otp_time = Carbon\Carbon::parse($user->otp_time)->diffInSeconds(Carbon\Carbon::now());
                                                $otp_minutes = floor(($otp_time / 60) % 60);
                                                $otp_seconds = $otp_time % 60;
                                                echo '<button type="button" onclick="CheckOTP()" id="checkOTP" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" data-resend-time="'.Carbon\Carbon::parse($user->otp_time)->diffInSeconds(Carbon\Carbon::now()).'" disabled> Wait for  <span id="__otp_time" >'.$otp_minutes .' minutes '.$otp_seconds.' seconds '.'</span></button>';
                                            }
                                            elseif (isset($user) && !is_null($user) && is_null($user->otp_resend_time))
                                            {
                                                echo '<button type="button" onclick="CheckOTP()" id="checkOTP" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" data-resend-time="0"> Continue </button>';
                                            }
                                            else
                                            {
                                                echo '<button type="button" onclick="CheckOTP()" id="checkOTP" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" data-resend-time="0"> Continue </button>';
                                            }
                                    @endphp
                                  
                                </div>
                            </div>
                            <input type="hidden" name="token" value="{{isset($token)?$token:''}}">
                            <input type="hidden" name="email" id="__email" value="{{isset($email)?$email:''}}">
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
            var otp = $("#otp").val();
            var __email = $("#__email").val();
            if($.trim(otp) == '')
            {
                $("#otp").after($('<div class="content-id"  id="otp_error_id" ><span style="color: white;">OTP Field is compulsory</span></div>'))
                setTimeout(function () {
                    $('#otp_error_id').remove();
                }, 3000);
                return;
            }
            $('#checkOTP').attr('disabled', true);

            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{route('checkOTP')}}",
                data: {otp: otp, _token: token, otp_token:$("input[name='token']").val(), 'email': __email},
                success: function (data) {
                    console.log(data, 'consoledata');
                    var status = data.status;
                    if(status == 'exits'){
                        $("#error_message").show();
                        $("#resendotp").hide();
                        $("#success_message").hide();
                        $("#error_message").html("<strong>Otp Active!</strong> "+ data.message);
                        $('#checkOTP').removeAttr('disabled');
                        setInterval(function() {
                            window.location.href = "{{route('login')}}";
                        }, 3000);
                    }
                    if(status == 'active'){
                        $("#success_message").show();
                        $("#resendotp").hide();
                        $("#error_message").hide();
                        $("#success_message").html("<strong>Success!</strong> "+ data.message);
                        $('#checkOTP').removeAttr('disabled');
                        setInterval(function() {
                            window.location.href = "{{route('home')}}";
                        }, 3000);
                    }

                    if(status == 'otp wrong'){
                        $("#error_message").show();
                        // $("#resendotp").hide();
                        $("#success_message").hide();
                        $("#error_message").html("<strong>OTP Wrong!</strong> "+ data.message);
                        if(data.__user['otp_attempts'] >= '1')
                        {
                            $('#__otp_attempts').html(data.__user['otp_attempts']);
                            $('#checkOTP').removeAttr('disabled');
                        }
                        setTimeout(function () {
                            $("#error_message").html("");
                            $("#error_message").hide();
                        },1500);

                        if(data.__user['otp_time'] != null)
                        {
                            let time__ = Number(data._otp_time_handle);
                            $('#checkOTP').attr('disabled', true);
                            $('#checkOTP').data('resend-time', time__);
                            $('#checkOTP').html("Wait for  <span id='__otp_time' ></span>");
                            $("#resendotp").hide();


                            var checkOTP_2_Interval = setInterval(function() {
                                if(time__ > 1)
                                {
                                    time__ = time__ - 1;
                                    var m = Math.floor(time__ % 3600 / 60);
                                    var s = Math.floor(time__ % 3600 % 60);

                                    var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
                                    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
                                    $('#__otp_time').text(mDisplay + sDisplay);
                                }
                                else
                                {
                                    resend_time = 0;
                                    $('#checkOTP').removeAttr('disabled');
                                    $('#checkOTP').data('resend-time', '0');
                                    $('#checkOTP').html('Continue');
                                    $("#resendotp").show();
                                    $('#__otp_attempts').html('3');
                                    clearInterval(checkOTP_2_Interval);
                                }

                            }, 1000);
                        }
                    }

                    if(status == 'otp error'){
                        $("#error_message").show();
                        $("#success_message").hide();
                        $("#error_message").html("<strong>OTP Error!</strong> "+ data.message);
                        if(data.__user['otp_attempts'] >= '1')
                        {
                            $('#__otp_attempts').html(data.__user['otp_attempts']);
                            $('#checkOTP').removeAttr('disabled');
                        }
                        setTimeout(function () {
                            $("#error_message").html("");
                            $("#error_message").hide();
                        },1500);

                        if(data.__user['otp_time'] != null)
                        {
                            let time__ = Number(data._otp_time_handle);

                            $('#checkOTP').attr('disabled', true);
                            $('#checkOTP').data('resend-time', time__);
                            $('#checkOTP').html("Wait for  <span id='__otp_time' ></span>");
                            $("#resendotp").hide();

                            var checkOTP_2_Interval = setInterval(function() {
                                if(time__ > 1)
                                {
                                    time__ = time__ - 1;
                                    var m = Math.floor(time__ % 3600 / 60);
                                    var s = Math.floor(time__ % 3600 % 60);

                                    var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
                                    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
                                    $('#__otp_time').text(mDisplay + sDisplay);
                                }
                                else
                                {
                                    resend_time = 0;
                                    $('#checkOTP').removeAttr('disabled');
                                    $('#checkOTP').data('resend-time', '0');
                                    $('#checkOTP').html('Continue');
                                    $("#resendotp").show();
                                    $('#__otp_attempts').html('3');
                                    clearInterval(checkOTP_2_Interval);
                                }

                            }, 1000);
                        }
                    }

                    if(status == 'no_user')
                    {
                        $("#error_message").show();
                        $("#success_message").hide();
                        $("#resendotp").hide();
                        $('#__otp_attempts').html(data.__user['otp_attempts']);
                        $("#error_message").html("<strong>Error!</strong> "+ data.message);
                        setInterval(function() {
                            window.location.href = "{{route('home')}}";
                        }, 3000);
                    }

                }
            });
        }
        function resendOTP(){
            var email = $("#__email").val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{route('saveOTP')}}",
                data: {email: email, _token: token},
                success: function (data) {
                    var status = data.status;
                    if(status == 'success'){
                        $("#success_message").show();
                        $("#success_message").html("<strong>Success!</strong> "+ data.message);
                        setTimeout(function () {
                            $("#success_message").html("");
                            $("#success_message").hide();
                        },1500);

                        if(data._opt_resend_time_handle != null && data._opt_resend_time_handle != '0')
                        {
                            let time__ = Number(data._opt_resend_time_handle);
                            if(time__ > 0)
                            {
                                $('#resendotp').attr('disabled', true);
                                $('#resendotp').data('resend-time', time__);
                                $('#resendotp').html("Resend OTP in  <span id='__otp__resend_time' ></span>");
                                var resend_otp_interval = setInterval(function() {
                                    if(time__ > 1)
                                    {
                                        time__ = time__ - 1;
                                        let min = Math.floor(time__ % 3600 / 60);
                                        let sec = Math.floor(time__ % 3600 % 60);

                                        let minDisplay = min > 0 ? min + (min == 1 ? " minute " : " minutes ") : "";
                                        let secDisplay = sec > 0 ? sec + (sec == 1 ? " second" : " seconds") : "";

                                        $('#__otp__resend_time').text(minDisplay + secDisplay);
                                    }
                                    else
                                    {
                                        time__ = 0;
                                        $('#resendotp').removeAttr('disabled');
                                        $('#resendotp').data('resend-time', '0');
                                        $('#resendotp').html('Resend OTP');
                                        clearInterval(resend_otp_interval);
                                    }

                                }, 1000);
                            }

                        }
                    }
                }
            });
        }

        // window.addEventListener("beforeunload", function (e) {
        //     var message = "Are you sure you want to leave?";
        //
        //     (e || window.event).returnValue = message;
        //     return message;
        // });

        $( document ).ready(function() {
            var resend_time = Number($('#resendotp').data('resend-time'));
            if(resend_time > 0)
            {
                var resend_time_interval = setInterval(function() {
                    if(resend_time > 1)
                    {
                        resend_time = resend_time - 1;
                        var min = Math.floor(resend_time % 3600 / 60);
                        var sec = Math.floor(resend_time % 3600 % 60);

                        var minDisplay = min > 0 ? min + (min == 1 ? " minute " : " minutes ") : "";
                        var secDisplay = sec > 0 ? sec + (sec == 1 ? " second" : " seconds") : "";
                        $('#__otp__resend_time').text(minDisplay + secDisplay);
                    }
                    else
                    {
                        resend_time = 0;
                        $('#resendotp').removeAttr('disabled');
                        $('#resendotp').data('resend-time', '0');
                        $('#resendotp').html('Resend OTP');
                        clearInterval(resend_time_interval);
                    }

                }, 1000);
            }

            var checkOTP_time = Number($('#checkOTP').data('resend-time'));
            if(checkOTP_time > 0)
            {
                $("#resendotp").hide();
                var checkOTP_Interval = setInterval(function() {
                    if(checkOTP_time > 1)
                    {
                        checkOTP_time = checkOTP_time - 1;
                        var m = Math.floor(checkOTP_time % 3600 / 60);
                        var s = Math.floor(checkOTP_time % 3600 % 60);

                        var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
                        var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
                        $('#__otp_time').text(mDisplay + sDisplay);
                    }
                    else
                    {
                        resend_time = 0;
                        $('#checkOTP').removeAttr('disabled');
                        $('#checkOTP').data('resend-time', '0');
                        $('#checkOTP').html('Continue');
                        $("#resendotp").show();
                        clearInterval(checkOTP_Interval);
                    }

                }, 1000);
            }
        });
    </script>
@endsection