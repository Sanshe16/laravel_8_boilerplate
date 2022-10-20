<p>{{__('Username:')}} {{ $name }}</p>
<p>{{__('E-Mail:')}} {{ $email }}</p>
<p>{{__('Subject:')}} {{ $title }}</p>
<p>{{__('PIN:')}} {{ $otp }}</p>
<br>
<p>{{__('Message: Please click below link or paste above otp in the page.')}}</p>
<p><a href="{{$otp_url}}">{{__('Click here to Reset your password.')}}</a></p>