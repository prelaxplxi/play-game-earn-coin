// ===== Theme Toggle =====
function initThemeToggle() {
    var toggle = document.getElementById('theme-toggle');
    if (!toggle) return;
    toggle.addEventListener('click', function() {
        var html = document.documentElement;
        var current = html.getAttribute('data-bs-theme');
        var next = (current === 'dark') ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('admin-theme', next);
        updateToggleIcon(next);
    });
}

function updateToggleIcon(theme) {
    var icon = document.querySelector('#theme-toggle i');
    if (!icon) return;
    icon.className = (theme === 'dark') ? 'fas fa-sun' : 'fas fa-moon';
}

document.addEventListener('DOMContentLoaded', function() {
    var saved = localStorage.getItem('admin-theme') || 'light';
    updateToggleIcon(saved);
    initThemeToggle();
});

// ===== Toastr Helpers =====
function toastrSuccess(msg, title = 'Success') { toastr.success(msg, title); }
function toastrError(msg, title = 'Error') { toastr.error(msg, title); }
function toastrWarning(msg, title = 'Warning') { toastr.warning(msg, title); }
function toastrInfo(msg, title = 'Info') { toastr.info(msg, title); }
function toastrErrorWithText(msg, title = 'Warning') { toastr.warning(msg, title); }

function remove_id(id, url, tableName = '') {
    if (id.length <= 0) {
        toastrWarning('Please select at least one record', 'Warning');
    } else {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085D6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-danger ml-1"
            },
            buttonsStyling: false,
            preConfirm: function () {
                var finalUrl = url.includes(':id') ? url.replace(':id', id) : url;
                return $.ajax({
                    type: "POST",
                    url: finalUrl,
                    data: {
                        id: id,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if (tableName) {
                            $(tableName).DataTable().ajax.reload();
                            $("#remove_" + id).closest("tr").hide('slow');
                            $('#select_all').prop('checked', false);
                            $("#select_count").html(0);
                            toastrSuccess('Successfully removed');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr);
                        toastrError('Error in removing record', 'Error');
                    }
                });
            }
        }).then(function (t) {
            if (t.isConfirmed) {
                Swal.fire({
                    title: "Success",
                    text: "Your record has been deleted.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
}
