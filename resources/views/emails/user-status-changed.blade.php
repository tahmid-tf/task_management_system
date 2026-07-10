@component('mail::message')
# Account Status Update

Hello {{ $userName }},

Your account status has been updated by {{ $changedBy }}.

@component('mail::panel')
Current status: **{{ ucfirst($status) }}**
@endcomponent

@if ($status === 'inactive')
Your access to the dashboard is temporarily suspended. Please contact the administrator if you believe this is a mistake.
@else
Your access has been restored and you can now continue using the platform normally.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
