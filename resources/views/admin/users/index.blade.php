@extends('layouts\admin')

@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>Users List</h3>
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
        <button type="button" class="btn btn-secondary" onclick="$('#users-table').DataTable().ajax.reload();">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="users-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Register Date</th>
                <th>Coin Balance</th>
                <th>Redeemed Amount</th>
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
        window.location.href = "{{ route('admin.users.index') }}/" + id + "/edit";
    }

    $(document).on('change', '.status-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.users.toggle-status') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                toastrSuccess(response.message);
            },
            error: function(xhr) {
                toastrError('Error updating status');
            }
        });
    });

    $(document).ready(function() {
        var table = $('#users-table').DataTable({
            // dom: 'Bfrtip',
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
                url: "{{ route('admin.users.index') }}",
                data: function (d) {
                    d.status = $('#fil_status').val(),
                    d.name = $('#fil_name').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id', orderable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone_no', name: 'phone_no'},
                {data: 'created_at', name: 'created_at'},
                {data: 'balance', name: 'balance'},
                {data: 'redeemed_amount', name: 'redeemed_amount'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {
                    data: 'action', name: 'action', orderable: false,
                    render: function (data, type, row) {
                        var edit_fun = "edit_id('" + row.action + "')";
                        var delete_fun = "remove_id('" + row.action + "','{{ route('admin.users.destroy', ':id') }}','#users-table')";
                        return '<div class="invoice-action d-flex gap-1">' +
                            '<a href="javascript:void(0)" class="edit_icon text-center border rounded shadow-sm p-1" id="edit_' + row.action + '" onclick="' + edit_fun + '">' +
                            '<i class="fas fa-pen"></i>' +
                            '</a>' +
                            '<a href="javascript:void(0)" class="delete_icon text-center text-danger border rounded shadow-sm p-1" id="remove_' + row.action + '"  onclick="' + delete_fun + '">' +
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
</script>
@endpush
@endsection
