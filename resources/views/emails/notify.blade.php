<x-mail::message>
# Introduction

Congratulations! You now a premier user.

<p>Your purchase details:</p>
<ul>
    <li>Plan {{ $plan }}</li>
    <li>Plan ends on {{ $billing_ends }}</li>
</ul>
<x-mail::button :url="''">
Post a job
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
