@component('mail::message')
# {{ $heading }}

{{ $intro }}

@if (! empty($customMessage))
{{ $customMessage }}

@endif

@if (! empty($tasks))
@component('mail::table')
| Task | Category | Priority | Status | Due Date | Assigned By |
|:--|:--|:--|:--|:--|:--|
@foreach ($tasks as $task)
| {{ $task['title'] }} | {{ $task['category'] }} | {{ $task['priority'] }} | {{ $task['status'] }} | {{ $task['due_date'] }} | {{ $task['assigned_by'] }} |
@endforeach
@endcomponent

@endif

@if (! empty($closingLine))
{{ $closingLine }}

@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
