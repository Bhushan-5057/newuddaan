@extends('user.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">User Profile /</span> ID Card
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0"><i class="fa fa-id-card me-1"></i> My ID Card</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.idcard.download') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-download me-1"></i> Download PDF
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="printIdCard()">
                        <i class="fa fa-print me-1"></i> Print
                    </button>
                </div>
            </div>

            <div class="card-body">
                @php
                    $missing = array_keys(array_filter($details, fn ($value) => $value === null || $value === ''));
                @endphp

                @if ($missing)
                    <div class="alert alert-warning py-2">
                        Not available on your card yet: <strong>{{ implode(', ', $missing) }}</strong>.
                        <a href="{{ route('user.profile.edit') }}" class="alert-link">Update profile</a>
                    </div>
                @endif

                <div id="idCardPrint">
                    @include('user.idcard._card')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Print only the card: copy it into a hidden frame so the sidebar and
        // header never reach the printer and no blank pages follow.
        function printIdCard() {
            var frame = document.createElement('iframe');
            frame.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;';
            document.body.appendChild(frame);

            var doc = frame.contentWindow.document;
            doc.open();
            doc.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>ID Card</title>' +
                '<style>@page{margin:10mm}body{margin:0}</style></head><body>' +
                document.getElementById('idCardPrint').innerHTML +
                '</body></html>');
            doc.close();

            frame.contentWindow.onafterprint = function () {
                frame.remove();
            };

            setTimeout(function () {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            }, 300);
        }
    </script>
@endsection
