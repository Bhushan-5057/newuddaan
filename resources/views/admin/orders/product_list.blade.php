@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <h3>{{ $title }}</h3>
    
    <div class="card p-3 mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Quick Filter</label>
                <select id="date_range" class="form-control">
                    <option value="">All Time</option>
                    <option value="weekly">Last 7 Days</option>
                    <option value="15days">Last 15 Days</option>
                    <option value="monthly">This Month</option>
                    <option value="3months">Last 3 Months</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" id="from_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" id="to_date" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button id="btnFilter" class="btn btn-primary">Filter</button>
                <button id="btnReset" class="btn btn-secondary">Reset</button>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <table id="orderTable" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    let table = $('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
           
            url: "{{ route('admin.product.orders.data', $status) }}",
            data: function(d) {
                d.date_range = $('#date_range').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
 columns: [
    { 
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        orderable: false, // આ લાઈન એરર સોલ્વ કરશે
        searchable: false 
    },
    { data: 'action', name: 'action', orderable: false, searchable: false },
    { data: 'user.username', name: 'user.username' },
    { data: 'tx_id', name: 'tx_id' },
    { data: 'order_amount', name: 'order_amount' },
    { data: 'status_label', name: 'status_label', orderable: false },
    { data: 'added_on', name: 'added_on' }
],
// બાય ડિફોલ્ટ ઓર્ડર ID અથવા તારીખ પર સોર્ટિંગ સેટ કરો
order: [[6, 'desc']],
        dom: 'Bfrtip',
        buttons: ['csv', 'excel', 'pdf', 'print']
    });

    $('#btnFilter').click(function() { table.draw(); });
    $('#btnReset').click(function() { 
        $('.form-control').val(''); 
        table.draw(); 
    });

    $(document).on('click', '.btn-delete-order', function () {
        const url = $(this).data('url');
        if (!url) return;

        const doDelete = function () {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    table.draw(false);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Deleted', (res && res.message) ? res.message : 'Order deleted.', 'success');
                    } else {
                        alert((res && res.message) ? res.message : 'Order deleted.');
                    }
                },
                error: function (xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Failed to delete order.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', msg, 'error');
                    } else {
                        alert(msg);
                    }
                }
            });
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete this order?',
                text: 'Cancelled/returned order will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then(function (result) {
                if (result.isConfirmed) doDelete();
            });
        } else if (confirm('Delete this cancelled/returned order permanently?')) {
            doDelete();
        }
    });
});
</script>
@endsection