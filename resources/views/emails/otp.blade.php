@component('mail::message')
# Verification

Hello {{ ucwords($user->firstname)}}!

<p class="mail-body-text">
You're OTP is: <h1>{{ $user->otp }}</h1>
</p>


@slot('subcopy')
@component('mail::subcopy')
<p>
Cheers,<br>
The Support team
</p>
@endcomponent
@endslot


@endcomponent