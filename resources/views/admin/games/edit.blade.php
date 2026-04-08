@extends('layouts.admin')

@section('title', 'Edit Game')

@section('content')
<div class="card theme-card">
    <div class="card-header d-flex justify-content-between">
        <h3>Edit Game: {{ $game->name }}</h3>
        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Game Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $game->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $game->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $game->url) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                    @if($game->thumbnail)
                        <div class="mt-2 text-center border p-2 rounded bg-light">
                            <img src="{{ asset('storage/'.$game->thumbnail) }}" width="150" class="img-fluid rounded shadow-sm">
                            <p class="mb-0 small text-muted">Current Thumbnail</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 mb-3">
                    <label for="coins" class="form-label">Coins</label>
                    <input type="number" name="coins" id="coins" class="form-control" value="{{ old('coins', $game->coins) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="time_duration" class="form-label">Time Duration (Seconds)</label>
                    <input type="number" name="time_duration" id="time_duration" class="form-control" value="{{ old('time_duration', $game->time_duration) }}" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $game->description) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $game->is_active) == 1 ? 'checked' : '' }}>
                        <label class="form-check-input-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Game</button>
        </form>
    </div>
</div>
@endsection
