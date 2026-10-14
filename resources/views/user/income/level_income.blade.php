@extends('user.layouts.app')



@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">



<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card p-3">

            <h4>Level Income History</h4>

            <div class="card-datatable table-responsive p-2">
<div class="mb-3">
    <label for="levelFilter" class="form-label">Filter by Level</label>
    <select id="levelFilter" class="form-select" style="max-width: 200px;">
        <option value="">All Levels</option>
        @for ($i = 1; $i <= 7; $i++)
            <option value="{{ $i }}">Level {{ $i }}</option>
        @endfor
    </select>
</div>
                <table class="table " id="fundTransferTable">

                    <thead>

                        <tr>

                          

                            <th>Amount</th>

                            <th>Remark</th>

                            <th>Date</th>

                          

                        

                         

                        </tr>

                    </thead>



                </table>

            </div>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<!-- jQuery (required by DataTables) -->

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->



<!-- DataTables JS -->

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {

    const table =$('#fundTransferTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: {
            url: '{{ route('user.level.history.data') }}',
            data: function (d) {
                d.level = $('#levelFilter').val(); // send selected level
            }
        },

        columns: [

              {

                data: 'amount',

                name: 'amount'

            },

           

            {

                data: 'remark',

                name: 'remark'

            },

        

          {

                data: 'date',

                name: 'date'

            },

          

        ],

        order: [

            [1, 'desc']

        ],

        error: function(xhr, error, thrown) {

            console.error('DataTables error:', xhr.responseText);

            alert('Error loading data. Check console for details.');

        }

    });
    // trigger redraw when filter changes
    $('#levelFilter').on('change', function () {
        table.ajax.reload();
    });

});

</script>

@endsection