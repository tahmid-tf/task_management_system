@extends('layouts.admin')

@section('content')
    <div class="container-xl px-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Task Categories</h1>
                <div class="text-muted">Manage the Excel-style tabs above your task board.</div>
            </div>
            <a href="{{ route('admin.tasks.board') }}" class="btn btn-outline-primary">Open Board</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="categoryForm" class="row g-3">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Sprint 1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" value="#0d6efd" class="form-control form-control-color">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">Reorder Categories</h5>
                        <div class="text-muted small">Drag and drop to control the tab order.</div>
                    </div>
                    <button id="saveCategoryOrder" class="btn btn-outline-primary btn-sm">Save Order</button>
                </div>

                <div id="categoryList" class="row g-3">
                    @foreach ($categories as $category)
                        <div class="col-12 col-md-6 col-xl-4" data-id="{{ $category->id }}">
                            <div class="border rounded-3 p-3 h-100 d-flex flex-column gap-3" style="border-left:4px solid {{ $category->color ?? '#0d6efd' }};">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <div class="fw-bold">{{ $category->name }}</div>
                                        <div class="text-muted small">{{ $category->tasks_count }} task(s)</div>
                                    </div>
                                    <span class="badge" style="background: {{ $category->color ?? '#0d6efd' }};">
                                        {{ $category->slug }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary js-edit-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-color="{{ $category->color ?? '#0d6efd' }}">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger js-delete-category"
                                        data-id="{{ $category->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            new Sortable(document.getElementById('categoryList'), {
                animation: 150
            });

            $('#saveCategoryOrder').on('click', function () {
                const ids = $('#categoryList > div').map(function () {
                    return $(this).data('id');
                }).get();

                $.ajax({
                    url: '{{ route('admin.task-categories.reorder') }}',
                    method: 'PATCH',
                    data: { ids: ids }
                }).done(function (response) {
                    Swal.fire('Success', response.message, 'success');
                }).fail(function () {
                    Swal.fire('Error', 'Unable to reorder categories.', 'error');
                });
            });

            $('#categoryForm').on('submit', function (e) {
                e.preventDefault();

                $.post('{{ route('admin.task-categories.store') }}', $(this).serialize())
                    .done(function (response) {
                        Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                    }).fail(function (xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Unable to create category.', 'error');
                    });
            });

            $(document).on('click', '.js-delete-category', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Delete this category?',
                    text: 'Tasks linked to soft-deleted categories will remain in the database.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: '{{ url('/admin/task-categories') }}/' + id,
                        method: 'DELETE'
                    }).done(function (response) {
                        Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                    }).fail(function () {
                        Swal.fire('Error', 'Unable to delete category.', 'error');
                    });
                });
            });

            $(document).on('click', '.js-edit-category', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const color = $(this).data('color');

                Swal.fire({
                    title: 'Update category',
                    html: `
                        <input id="swal-category-name" class="swal2-input" value="${name}">
                        <input id="swal-category-color" type="color" class="swal2-input" value="${color}">
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    preConfirm: () => ({
                        name: $('#swal-category-name').val(),
                        color: $('#swal-category-color').val()
                    })
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: '{{ url('/admin/task-categories') }}/' + id,
                        method: 'PUT',
                        data: result.value
                    }).done(function (response) {
                        Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                    }).fail(function () {
                        Swal.fire('Error', 'Unable to update category.', 'error');
                    });
                });
            });
        });
    </script>
@endpush
