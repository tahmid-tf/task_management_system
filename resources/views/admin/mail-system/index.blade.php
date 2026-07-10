@extends('layouts.admin')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="toggle-right"></i>
                                </div>
                                Mail System
                            </h1>
                            <div class="page-header-subtitle">
                                Control whether assignees receive automatic task-assignment emails.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                                <div>
                                    <div class="text-muted small text-uppercase fw-bold">Current Status</div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <span class="badge rounded-pill {{ $enabled ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $enabled ? 'On' : 'Off' }}
                                        </span>
                                        <span class="fw-semibold">
                                            {{ $enabled ? 'Automatic task assignment emails are active.' : 'Automatic task assignment emails are disabled.' }}
                                        </span>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="btn {{ $enabled ? 'btn-outline-danger' : 'btn-outline-success' }} js-mail-system-toggle"
                                    data-enabled="{{ $enabled ? 0 : 1 }}"
                                    data-current="{{ $enabled ? 'on' : 'off' }}"
                                >
                                    {{ $enabled ? 'Turn Off' : 'Turn On' }}
                                </button>
                            </div>

                            <div class="alert alert-light border mb-0">
                                <strong>What this controls:</strong>
                                When this switch is turned on, a mail is sent to the assignee every time a task is created
                                or duplicated and assigned to a user. When it is turned off, those automatic assignment
                                emails are skipped.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            $(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonText: 'Okay',
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            $(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation error',
                    text: @json($errors->first()),
                    confirmButtonText: 'Okay',
                });
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).on('click', '.js-mail-system-toggle', function() {
                const $button = $(this);
                const nextEnabled = Number($button.data('enabled')) === 1;
                const action = nextEnabled ? 'turn on' : 'turn off';

                Swal.fire({
                    icon: 'warning',
                    title: `Are you sure you want to ${action} the mail system?`,
                    text: nextEnabled
                        ? 'Automatic assignment emails will be enabled.'
                        : 'Automatic assignment emails will be disabled.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: '{{ route('admin.mail-system.update') }}',
                        method: 'PATCH',
                        data: {
                            enabled: nextEnabled ? 1 : 0
                        }
                    }).done(function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Mail system updated successfully.',
                            confirmButtonText: 'Okay',
                        }).then(function() {
                            window.location.reload();
                        });
                    }).fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Unable to update mail system.',
                            confirmButtonText: 'Okay',
                        });
                    });
                });
            });
        });
    </script>
@endpush
