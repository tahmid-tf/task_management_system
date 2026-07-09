@extends('layouts.admin')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="user-plus"></i>
                                </div>
                                Add User
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <img
                                id="imagePreview"
                                class="img-account-profile rounded-circle mb-2"
                                src="{{ asset('assets/img/illustrations/profiles/profile-1.png') }}"
                                alt="Profile preview"
                                style="width: 200px; height: 200px; object-fit: cover;"
                            />
                            <div class="small font-italic text-muted mb-4">
                                JPG, JPEG, PNG or WEBP up to 5 MB
                            </div>
                            <label class="btn btn-primary mb-0" for="image">
                                Upload new image
                            </label>
                            <input
                                class="d-none"
                                id="image"
                                name="image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                form="addUserForm"
                            />
                            <div class="small text-muted mt-3" id="imageName">No file selected</div>
                            @error('image')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">User Details</div>
                        <div class="card-body">
                            <form id="addUserForm" method="POST" action="{{ route('admin.add-user.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="name">Name</label>
                                        <input
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Enter user name"
                                        />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="email">Email</label>
                                        <input
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="Enter user email"
                                        />
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="password">Password</label>
                                        <input
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            type="password"
                                            name="password"
                                            placeholder="Enter password"
                                        />
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="password_confirmation">Confirm Password</label>
                                        <input
                                            class="form-control"
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            placeholder="Confirm password"
                                        />
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="role">Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                            <option value="">Select role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="submit">
                                    Create account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonText: 'Okay'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('image');
            const preview = document.getElementById('imagePreview');
            const imageName = document.getElementById('imageName');
            const fallback = @json(asset('assets/img/illustrations/profiles/profile-1.png'));

            if (!input || !preview || !imageName) {
                return;
            }

            input.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) {
                    preview.src = fallback;
                    imageName.textContent = 'No file selected';
                    return;
                }

                imageName.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
