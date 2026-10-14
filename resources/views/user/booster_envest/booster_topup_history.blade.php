

@extends('user.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">

    <!-- <div class="container-xxl flex-grow-1 container-p-y"> -->

        <h4 class="py-3 breadcrumb-wrapper mb-4">

            <span class="text-muted fw-light"> Invest/</span> Topup History

        </h4>

        <div class="row">

            <div class="col-12">

                <div class="card border-end">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <div class="d-inline-flex align-items-center">

                                    <h2 class=" mb-1 font-weight-medium">${{ $totalamount }}</h2>

                                    <span

                                        class="badge bg-primary font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">+18.33%</span>

                                </div>

                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Package Amount

                                </h6>

                            </div>

                            <div class="ms-auto mt-md-3 mt-lg-0">

                                <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

           <div class="col-12">

                <div class="card p-3 shadow">

                   <div class="card-datatable table-responsive p-2">

                        <table id="topupHistoryTable" class="table ">

                            <thead>

                                <tr>

                                    <th>S No.</th>

                                    <th>Type</th>

                                    <th>Amount</th>

                                    <th>Date</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<!-- jQuery (required by DataTables) -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<!-- DataTables JS -->

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function () {

    $('#topupHistoryTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: '{{ route('user.order.history.data') }}',

       columns: [

    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

    { data: 'tx_type', name: 'tx_type' },

    { data: 'order_amount', name: 'order_amount' },

    { data: 'added_on', name: 'added_on' },

    { data: 'status', name: 'status' }

],

order: [[3, 'desc']]



    });

});

</script>    

@endsection     