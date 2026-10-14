@extends('user.layouts.app')

@section('content')
<div class="page-breadcrumb" style="
    margin-left: 260px;
    padding-top: 80px;
">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light"> Fund/</span> Add Fund
        </h4>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4 mb-4">
                <div class="col-sm-12 col-xl-6">

                    <div class="row">
                        <div class="col-xl">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Fund Transfer</h5>

                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label" for="amount">Enter Amount</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                placeholder="0.00" />
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')



    @endsection