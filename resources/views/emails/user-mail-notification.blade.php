@component('mail::message')
# {{ $heading }}

{{ $intro }}

@if (! empty($customMessage))
@component('mail::panel')
{{ $customMessage }}
@endcomponent
@endif

@if (! empty($tasks))
@foreach ($tasks as $task)
@component('mail::panel')
<div style="font-size:18px;font-weight:700;color:#111827;line-height:1.4;margin-bottom:12px;">
    {{ $task['title'] }}
</div>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom:14px;">
    <tr>
        <td style="padding:6px 0;width:34%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">
            Category
        </td>
        <td style="padding:6px 0;font-size:14px;color:#111827;">
            {{ $task['category'] }}
        </td>
    </tr>
    <tr>
        <td style="padding:6px 0;width:34%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">
            Priority
        </td>
        <td style="padding:6px 0;font-size:14px;color:#111827;">
            {{ $task['priority'] }}
        </td>
    </tr>
    <tr>
        <td style="padding:6px 0;width:34%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">
            Status
        </td>
        <td style="padding:6px 0;font-size:14px;color:#111827;">
            {{ $task['status'] }}
        </td>
    </tr>
    <tr>
        <td style="padding:6px 0;width:34%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">
            Due Date
        </td>
        <td style="padding:6px 0;font-size:14px;color:#111827;">
            {{ $task['due_date'] }}
        </td>
    </tr>
    <tr>
        <td style="padding:6px 0;width:34%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">
            Assigned By
        </td>
        <td style="padding:6px 0;font-size:14px;color:#111827;">
            {{ $task['assigned_by'] }}
        </td>
    </tr>
</table>

<div style="padding:14px 16px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;">
    <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;margin-bottom:8px;">
        Description
    </div>
    <div style="font-size:14px;line-height:1.75;color:#111827;white-space:pre-wrap;word-break:break-word;">
        {{ $task['description'] }}
    </div>
</div>
@endcomponent
@endforeach
@endif

@if (! empty($closingLine))
{{ $closingLine }}

@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
