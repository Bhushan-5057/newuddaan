@extends('superadmin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">Support Ticket List</h5>
            </div>

            <!-- Filters -->
            <div class="row mb-3 mt-2">
                <div class="col-md-3">
                    <select id="filterStatus" class="form-control">
                        <option value="">All Status</option>
                        <option value="0">Open</option>
                        <option value="1">Pending</option>
                        <option value="2">Waiting</option>
                        <option value="3">Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="filterUsername" class="form-control" placeholder="Search by Username">
                </div>
                <div class="col-md-3">
                    <input type="date" id="filterFrom" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" id="filterTo" class="form-control">
                </div>
            </div>

            <!-- Table -->
            <div class="card-datatable table-responsive p-2">
                <table id="transactionTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Ticket Id</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Create Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="replyForm">
            @csrf
            <input type="hidden" name="ticket_id" id="ticket_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reply to Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea name="reply" id="reply" class="form-control" placeholder="Type your reply..." rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(function () {
        let table = $('#transactionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('superadmin.support.pendinglist') }}",
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.username = $('#filterUsername').val();
                    d.from = $('#filterFrom').val();
                    d.to = $('#filterTo').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'ticket', name: 'ticket' },
                { data: 'username', name: 'username' },
                { data: 'message', name: 'message' },
                { data: 'timestamp', name: 'timestamp' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[4, 'desc']]
        });

        // Filter trigger
        $('#filterStatus, #filterUsername, #filterFrom, #filterTo').on('change keyup', function () {
            table.draw();
        });

        // Open reply modal
        $(document).on('click', '.reply-btn', function () {
            const ticketId = $(this).data('id');
            $('#ticket_id').val(ticketId);
            $('#reply').val('');
            $('#replyModal').modal('show');
        });

        // Submit reply
        $('#replyForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('superadmin.support.reply') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#replyModal').modal('hide');
                        table.ajax.reload();
                        alert('Reply sent successfully!');
                    } else {
                        alert('Error occurred while replying.');
                    }
                },
                error: function () {
                    alert('Something went wrong.');
                }
            });
        });
    });
</script>
@endsection
