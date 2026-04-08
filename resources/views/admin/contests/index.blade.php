@extends('layouts.admin')

@section('title', 'Contests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>Contests List</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.contests.create') }}" class="btn btn-primary">Add New Contest</a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">
        <label for="fil_status" class="form-label">Status</label>
        <select id="fil_status" class="form-select">
            <option value="">All</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="fil_name" class="form-label">Name</label>
        <input type="text" id="fil_name" class="form-control" placeholder="Search by name">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-secondary" onclick="$('#contest-table').DataTable().ajax.reload();">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="contest-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Contest Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    function edit_id(id) {
        window.location.href = "{{ route('admin.contests.index') }}/" + id + "/edit";
    }

    $(document).on('change', '.status-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.contests.toggle-status') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if(typeof toastrSuccess === 'function') {
                    toastrSuccess(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                if(typeof toastrError === 'function') {
                    toastrError('Error updating status');
                } else {
                    alert('Error updating status');
                }
            }
        });
    });

    $(document).ready(function() {
        var table = $('#contest-table').DataTable({
            dom:
                "<'row'<'col-sm-12 col-md-6 text-left'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: false,
            scrollX: !0,
            processing: true,
            serverSide: true,
            stateSave: true,
            lengthChange: !1,
            stateSaveParams: function (settings, data) {
                data.fil_status = $('#fil_status').val();
                data.fil_name = $('#fil_name').val();
            },
            stateLoadParams: function (settings, data) {
                $('#fil_status').val(data.fil_status);
                $('#fil_name').val(data.fil_name);
            },
            stateSaveCallback: function (settings, data) {
                localStorage.setItem(settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem(settings.sInstance))
            },
            ajax: {
                url: "{{ route('admin.contests.index') }}",
                data: function (d) {
                    d.status = ($('#fil_status').val() != '') ? $('#fil_status').val() : 1,
                    d.name = ($('#fil_name').val() != '') ? $('#fil_name').val() : null,
                    d.search = ($('input[type="search"]').val() != '') ? $('input[type="search"]').val() : null
                }
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'contest_name', name: 'contest_name'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {
                    data: 'action', name: 'action', orderable: false,
                    render: function (data, type, row) {
                        var edit_fun = "edit_id('" + row.action + "')";
                        var delete_url = "{{ route('admin.contests.destroy', ':id') }}".replace(':id', row.action);
                        return '<div class="invoice-action d-flex gap-1">' +
                            '<a href="javascript:void(0)" class="edit_icon text-center border rounded shadow-sm p-1" onclick="' + edit_fun + '">' +
                            '<i class="fas fa-pen"></i>' +
                            '</a>' +
                            '<a href="javascript:void(0)" class="delete_icon text-center text-danger border rounded shadow-sm p-1" onclick="remove_id(' + row.action + ', \'' + delete_url + '\', \'#contest-table\')">' +
                            '<i class="far fa-trash-alt"></i>' +
                            '</a>' +
                            '</div>';
                    }
                },
            ],
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    });

    function remove_id(id, url, table_id) {
        if (confirm('Are you sure you want to delete this contest?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(table_id).DataTable().ajax.reload();
                    if(typeof toastrSuccess === 'function') {
                        toastrSuccess(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    if(typeof toastrError === 'function') {
                        toastrError('Error deleting contest');
                    } else {
                        alert('Error deleting contest');
                    }
                }
            });
        }
    }
</script>
@endpush
@endsection
