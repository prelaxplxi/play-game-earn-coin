@extends('layouts.admin')

@section('title', 'Add New Survey')

@section('content')
<div class="card theme-card">
    <div class="card-header d-flex justify-content-between">
        <h3>Add New Survey</h3>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-secondary">Back</a>
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

        <form action="{{ route('admin.surveys.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="total_reward_coins" class="form-label">Total Reward Coins</label>
                    <input type="number" name="total_reward_coins" id="total_reward_coins" class="form-control" value="{{ old('total_reward_coins',0) }}" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',1) == 1 ? 'checked' : '' }}>
                        <label class="form-check-input-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Store Answers</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="store_answers" id="store_answers" value="1" {{ old('store_answers',1) == 1 ? 'checked' : '' }}>
                        <label class="form-check-input-label" for="store_answers">Store user answers</label>
                    </div>
                </div>
            </div>

            <hr>

            <h5>Questions</h5>
            <div id="questions-container">
                @foreach(old('questions', []) as $qIndex => $q)
                    <div class="question-block mb-3" data-index="{{ $qIndex }}">
                        <div class="input-group mb-2">
                            <input type="text" name="questions[{{ $qIndex }}][question]" class="form-control" value="{{ $q['question'] ?? '' }}" placeholder="Question" required>
                            <button type="button" class="btn btn-danger remove-question">Remove</button>
                        </div>
                        <div class="options-container">
                            @foreach($q['options'] ?? [''] as $opt)
                                <div class="input-group mb-1">
                                    <input type="text" name="questions[{{ $qIndex }}][options][]" class="form-control" value="{{ $opt }}" placeholder="Option" required>
                                    <button type="button" class="btn btn-danger remove-option">Remove</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary add-option mb-2">Add Option</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-primary" id="add-question-btn">Add Question</button>

            <button type="submit" class="btn btn-primary mt-4">Create Survey</button>
        </form>

        {{-- hidden templates --}}
        <div id="question-template" style="display:none;">
            <div class="question-block mb-3" data-index="__INDEX__">
                <div class="input-group mb-2">
                    <input type="text" name="questions[__INDEX__][question]" class="form-control" placeholder="Question" required>
                    <button type="button" class="btn btn-danger remove-question">Remove</button>
                </div>
                <div class="options-container">
                    <div class="input-group mb-1">
                        <input type="text" name="questions[__INDEX__][options][]" class="form-control" placeholder="Option" required>
                        <button type="button" class="btn btn-danger remove-option">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary add-option mb-2">Add Option</button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    var questionsContainer = $('#questions-container');
    var nextIndex = questionsContainer.find('.question-block').length;

    function addQuestion() {
        var template = $('#question-template').html().replace(/__INDEX__/g, nextIndex);
        questionsContainer.append(template);
        nextIndex++;
    }

    $('#add-question-btn').on('click', function(){
        addQuestion();
    });

    questionsContainer.on('click', '.remove-question', function(){
        $(this).closest('.question-block').remove();
    });

    questionsContainer.on('click', '.add-option', function(){
        var questionBlock = $(this).closest('.question-block');
        var idx = questionBlock.data('index');
        var row = $('<div class="input-group mb-1">' +
            '<input type="text" name="questions['+idx+'][options][]" class="form-control" required>' +
            '<button type="button" class="btn btn-danger remove-option">Remove</button>' +
            '</div>');
        questionBlock.find('.options-container').append(row);
    });

    questionsContainer.on('click', '.remove-option', function(){
        $(this).closest('.input-group').remove();
    });
});
</script>
@endpush
