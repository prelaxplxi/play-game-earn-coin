@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="card theme-card">
    <div class="card-header d-flex justify-content-between">
        <h3>Edit User: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone_no" class="form-label">Phone No</label>
                    <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{ old('phone_no', $user->phone_no) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="balance" class="form-label">Balance</label>
                    <input type="number" step="0.01" name="balance" id="balance" class="form-control" value="{{ old('balance', $user->balance) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) == 1 ? 'checked' : '' }}>
                        <label class="form-check-input-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</div>
@endsection
