@extends('user.layouts.app')



@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">



<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card p-3">

            <h4>Roi Income History</h4>

            <div class="card-datatable table-responsive p-2">

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

    $('#fundTransferTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: '{{ route('user.roi.history.data') }}',

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

});

</script>

@endsection