<h2>Hello {{ $data['name'] }}</h2>
<p>Congratulations! your account is now set-up. To fully utilize our services, please verify your email by clicking the following link: <a href="{{ $data['link'] }}">Verification link</a> </p>
<p>Optionally, you can copy and open the following url into your browser: {{ $data['link'] }}</p>
<p>Regards,<br />
<strong>Hey ho! Team.</strong></p>