@extends('layouts.admin')

@section('title', 'User Survey Response Details')

@section('content')
<div class="card theme-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>User Survey Response Details</h3>
        <a href="{{ route('admin.user-surveys.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>User Information</h5>
                <p><strong>Name:</strong> {{ $response->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $response->user->email ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Survey Information</h5>
                <p><strong>Title:</strong> {{ $response->survey->title ?? 'N/A' }}</p>
                <p><strong>Reward Coins:</strong> {{ $response->reward_coins }}</p>
                <p><strong>Status:</strong> {{ ucfirst($response->status) }}</p>
                <p><strong>Completion Date:</strong> {{ $response->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        <hr>

        <h5>User Answers</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>User Answer</th>
                        <th>Correct?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($response->answers as $index => $answer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $answer->question->question ?? 'N/A' }}</td>
                            <td>{{ $answer->option->option_text ?? 'N/A' }}</td>
                            <td>
                                @if($answer->is_correct)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
