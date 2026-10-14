@extends('superadmin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Notice Board</h4>
        <button class="btn btn-primary" id="createNoticeBtn">Create Notice</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-datatable table-responsive p-2">
            <table class="table " id="noticeBoardTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="noticeForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="_method" value="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="noticeModalLabel">Create Notice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label>Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="marquee">Marquee</option>
                    <option value="popup">Popup</option>
                </select>
            </div>

            <div class="mb-3" id="img_field" style="display: none;">
                <label>Image</label>
                <input type="file" name="img_path" id="img_path" class="form-control">
                <div id="existingImage" class="mt-2"></div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="submitNotice">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const modal = new bootstrap.Modal(document.getElementById('noticeModal'));
    const table = $('#noticeBoardTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('superadmin.newsevents.data') }}',
      columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'title', name: 'title' },
    { data: 'description', name: 'description' },
    { data: 'type', name: 'type' },
    { data: 'img_path', name: 'img_path', orderable: false, searchable: false },
    { data: 'status', name: 'status', orderable: false, searchable: false },
    { data: 'added_on', name: 'added_on' },
    { data: 'action', name: 'action', orderable: false, searchable: false }
]

    });

    // Open modal for create
    $('#createNoticeBtn').click(function () {
        resetForm();
        $('#noticeModalLabel').text('Create Notice');
        $('#noticeForm').attr('action', '{{ route('superadmin.noticeboard.store') }}');
        $('#_method').val('POST');
        modal.show();
    });

    // Open modal for edit
    $(document).on('click', '.edit-notice-btn', function () {
        const id = $(this).data('id');
        $.get(`/superadmin/noticeboard/${id}/edit`, function (res) {
            $('#noticeModalLabel').text('Edit Notice');
            $('#title').val(res.title);
            $('#description').val(res.description);
            $('#type').val(res.type).trigger('change');
            $('#_method').val('POST');
            $('#noticeForm').attr('action', `/superadmin/noticeboard/${id}`);

            if (res.img_path) {
                $('#existingImage').html(`<img src="/storage/${res.img_path}" width="100">`);
            } else {
                $('#existingImage').html('');
            }

            modal.show();
        });
    });

    // Show/hide image field
    $('#type').on('change', function () {
        $('#img_field').toggle(this.value === 'popup');
    });

    // Submit form
    $('#noticeForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                modal.hide();
                table.ajax.reload();
            },
            error: function (xhr) {
                alert('Something went wrong!');
                console.log(xhr.responseText);
            }
        });
    });

$(document).on('click', '.toggle-status', function () {
    const id = $(this).data('id');

    $.post('{{ route('superadmin.noticeboard.toggleStatus') }}', {
        _token: '{{ csrf_token() }}',
        id: id
    }, function (res) {
        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Status changed successfully!',
                timer: 1500,
                showConfirmButton: false
            });
            table.ajax.reload(null, false);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to change status.'
            });
        }
    }).fail(function (xhr) {
        console.error(xhr.responseText);
        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Something went wrong while changing status.'
        });
    });
});


// Delete notice
$(document).on('click', '.delete-notice-btn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/superadmin/noticeboard/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.success) {
                        Swal.fire(
                            'Deleted!',
                            'The notice has been deleted.',
                            'success'
                        );
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire(
                            'Error!',
                            res.message || 'Failed to delete the notice.',
                            'error'
                        );
                    }
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the notice.',
                        'error'
                    );
                    console.error(xhr.responseText);
                }
            });
        }
    });
});


    function resetForm() {
        $('#noticeForm')[0].reset();
        $('#existingImage').html('');
        $('#img_field').hide();
    }
});
</script>
@endsection
