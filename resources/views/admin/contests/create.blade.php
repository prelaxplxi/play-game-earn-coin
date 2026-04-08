@extends('layouts.admin')

@section('title', 'Create Contest')

@section('content')
<div class="card theme-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Create New Contest</h3>
        <a href="{{ route('admin.contests.index') }}" class="btn btn-secondary btn-sm">Back</a>
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

        <form action="{{ route('admin.contests.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contest_name" class="form-label">Contest Name</label>
                    <input type="text" name="contest_name" id="contest_name" class="form-control" value="{{ old('contest_name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="rules" class="form-label">Rules</label>
                    <textarea name="rules" id="rules" class="form-control" rows="5">{{ old('rules') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                        <label class="form-check-input-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-success">Create Contest</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#rules'), {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'undo', 'redo', '|', 'outdent', 'indent', '|', 'imageUpload', 'insertTable', 'mediaEmbed'
            ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
@endsection
