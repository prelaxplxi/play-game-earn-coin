@extends('layouts.admin')

@section('title', 'User Survey Responses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>User Survey Responses</h3>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="filter-user" class="form-label">User Name</label>
        <input type="text" id="filter-user" class="form-control" placeholder="Search by user name">
    </div>
    <div class="col-md-4">
        <label for="filter-survey" class="form-label">Survey Title</label>
        <input type="text" id="filter-survey" class="form-control" placeholder="Search by survey title">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button id="filter-btn" class="btn btn-secondary w-100">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="user-surveys-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Survey</th>
                <th>Reward Coins</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var table = $('#user-surveys-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.user-surveys.index') }}",
            data: function (d) {
                d.user = $('#filter-user').val();
                d.survey = $('#filter-survey').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'survey', name: 'survey' },
            { data: 'reward_coins', name: 'reward_coins' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="{{ url('admin/user-surveys') }}/${data}" class="btn btn-sm btn-info text-white">View Answers</a>
                    `;
                }
            },
        ]
    });

    $('#filter-btn').on('click', function() {
        table.draw();
    });
});
</script>
@endpush
