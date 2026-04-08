@extends('layouts.admin')

@section('title', 'Games')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h3>Games List</h3>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="refreshModalCategories()">Manage Categories</button>
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">Add New Game</a>
    </div>
</div>

<!-- Category Management Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Manage Game Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" class="mb-4">
                    @csrf
                    <input type="hidden" name="id" id="edit_cat_id">
                    <div class="input-group">
                        <input type="text" name="name" id="cat_name" class="form-control" placeholder="Category Name" required>
                        <button class="btn btn-primary" type="submit" id="cat_btn">Add Category</button>
                        <button class="btn btn-outline-secondary d-none" type="button" id="cat_cancel_btn" onclick="resetCatForm()">Cancel</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="modal-categories-table" class="table table-sm table-bordered w-100">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Name</th>
                                <!-- <th width="100">Status</th> -->
                                <th width="80">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
        <button type="button" class="btn btn-secondary" onclick="$('#games-table').DataTable().ajax.reload();">Filter</button>
    </div>
</div>

<div class="card theme-card p-3 shadow-sm">
    <table id="games-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Category</th>
                <th>Coins</th>
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
        window.location.href = "{{ route('admin.games.index') }}/" + id + "/edit";
    }

    $(document).on('change', '.status-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.games.toggle-status') }}",
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

    function refreshModalCategories() {
        if (!$.fn.DataTable.isDataTable('#modal-categories-table')) {
            $('#modal-categories-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('admin.game-categories.index') }}",
                    data: function (d) {
                        d.length = 10;
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {
                            return '<div class="d-flex gap-1">' +
                                '<button class="btn btn-sm btn-info text-white py-0 px-1" onclick="editCategory(' + data + ', \'' + row.name + '\')"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-sm btn-danger py-0 px-1" onclick="deleteCategory(' + data + ')"><i class="fas fa-trash-alt"></i></button>' +
                                '</div>';
                        }
                    }
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
        } else {
            $('#modal-categories-table').DataTable().ajax.reload();
        }
    }
    function editCategory(id, name) {
        $('#edit_cat_id').val(id);
        $('#cat_name').val(name);
        $('#cat_btn').text('Update').removeClass('btn-primary').addClass('btn-success');
        $('#cat_cancel_btn').removeClass('d-none');
    }

    function resetCatForm() {
        $('#edit_cat_id').val('');
        $('#cat_name').val('');
        $('#cat_btn').text('Add Category').removeClass('btn-success').addClass('btn-primary');
        $('#cat_cancel_btn').addClass('d-none');
    }

    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_cat_id').val();
        var url = id ? "{{ url('admin/game-categories') }}/" + id : "{{ route('admin.game-categories.store') }}";
        var data = $(this).serialize();
        if(id) data += "&_method=PUT";

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(response) {
                resetCatForm();
                toastrSuccess(id ? 'Category updated successfully' : 'Category added successfully');
                refreshModalCategories();
            },
            error: function(xhr) {
                toastrError('Error saving category');
            }
        });
    });

    $(document).on('change', '.cat-status-toggle', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.game-categories.toggle-status') }}",
            type: "POST",
            data: { id: id, _token: "{{ csrf_token() }}" },
            success: function(response) {
                toastrSuccess(response.message);
            },
            error: function(xhr) {
                toastrError('Error updating status');
            }
        });
    });

    function deleteCategory(id) {
        if(confirm('Are you sure you want to delete this category?')) {
            var url = "{{ route('admin.game-categories.destroy', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: "POST",
                data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastrSuccess('Category deleted successfully');
                    refreshModalCategories();
                },
                error: function(xhr) {
                    toastrError('Error deleting category. It might be in use.');
                }
            });
        }
    }

    $(document).ready(function() {
        var table = $('#games-table').DataTable({
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
                url: "{{ route('admin.games.index') }}",
                data: function (d) {
                    d.status = $('#fil_status').val(),
                    d.name = $('#fil_name').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id', orderable: false},
                {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'category', name: 'category'},
                {data: 'coins', name: 'coins'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {
                    data: 'action', name: 'action', orderable: false,
                    render: function (data, type, row) {
                        var edit_fun = "edit_id('" + row.action + "')";
                        var delete_fun = "remove_id('" + row.action + "','{{ route('admin.games.destroy', ':id') }}','#games-table')";
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
