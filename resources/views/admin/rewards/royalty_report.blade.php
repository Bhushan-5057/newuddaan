@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>CTO Business</h5>

                    <h2>
                        ₹ {{ number_format($ctoBusiness,2) }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Royalty</h5>

                    <h2>
                        ₹ {{ number_format($totalRoyalty,2) }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Users</h5>

                    <h2>
                        {{ $totalUsers }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow">

        <div class="card-header">

            <h4 class="mb-0">
                Royalty Income Report
            </h4>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>User</th>

                        <th>Amount</th>

                        <th>Remark</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($royalties as $key=>$royalty)

                        <tr>

                            <td>
                                {{ $key+1 }}
                            </td>

                            <td>
                                {{ $royalty->user->username ?? '-' }}
                            </td>

                            <td>
                                ₹ {{ number_format($royalty->amount,2) }}
                            </td>

                            <td>
                                {{ $royalty->remark }}
                            </td>

                            <td>
                                {{ $royalty->added_on }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            {{ $royalties->links() }}

        </div>

    </div>

</div>

@endsection