@extends('layouts.admin')

@section('title', 'Surveys')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>Surveys List</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.surveys.create') }}" class="btn btn-primary">Add New Survey</a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="filter-title" class="form-label">Title</label>
        <input type="text" id="filter-title" class="form-control" placeholder="Search by title">
    </div>
    <div class="col-md-3">
        <label for="filter-status" class="form-label">Status</label>
        <select id="filter-status" class="form-select">
            <option value="">All</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button id="filter-btn" class="btn btn-secondary w-100">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="surveys-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Reward Coins</th>
                <th>Status</th>
                <th>Store Answers</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var table = $('#surveys-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.surveys.index') }}",
            data: function (d) {
                d.title = $('#filter-title').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'total_reward_coins', name: 'total_reward_coins' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'store_answers', name: 'store_answers', orderable: false, searchable: false },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="{{ url('admin/surveys') }}/${data}/edit" class="btn btn-sm btn-info text-white">Edit</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Delete</button>
                    `;
                }
            },
        ]
    });

    $('#filter-btn').on('click', function() {
        table.draw();
    });

    // Toggle Status
    $(document).on('change', '.status-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.surveys.toggle-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                }
            }
        });
    });

    // Toggle Store Answers
    $(document).on('change', '.store-answers-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.surveys.toggle-store-answers') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                }
            }
        });
    });

    // Delete Survey
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/surveys') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success) {
                            table.draw();
                            Swal.fire('Deleted!', 'Survey has been deleted.', 'success');
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
