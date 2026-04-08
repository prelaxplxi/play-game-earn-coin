@extends('layouts.admin')

@section('title', 'Withdrawal History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>Withdrawal History</h3>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="fil_user" class="form-label">User (User-wise Filter)</label>
        <select id="fil_user" class="form-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-secondary" onclick="$('#withdrawal-table').DataTable().ajax.reload();">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="withdrawal-table" class="table table-bordered table-striped w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Coins</th>
                <th>Amount (Rs)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#withdrawal-table').DataTable({
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
        ajax: {
            url: "{{ route('admin.withdrawals.index') }}",
            data: function (d) {
                d.user_id = $('#fil_user').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            { data: 'id', name: 'id', orderable: false },
            { data: 'user', name: 'user' },
            { data: 'withdraw_coins', name: 'withdraw_coins' },
            { data: 'amount', name: 'amount' },
            { data: 'created_at', name: 'created_at' }
        ],
        order: [[4, 'desc']], // Sort by date by default
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
});
</script>
@endpush
