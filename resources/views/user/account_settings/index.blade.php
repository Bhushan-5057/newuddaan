@extends('user.layouts.app')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Left Form -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Account Settings</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('user.accountsettings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label>Payment Method</label>
                                <select name="method" class="form-control" id="method-select" required>
                                    <option value="">Select Method</option>
                                    @foreach($methods as $method)
                                        <option value="{{ $method->unique_name }}"
                                            data-fields='@json($method->fields_required)'
                                            data-image="{{ $method->image }}">
                                            {{ $method->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="dynamic-fields-container"></div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Table -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Your Payment Accounts</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Default</th>
                                        <th>Account Type</th>
                                        <th>Details</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userMethods as $index => $payment)
                                        @php
                                            $details = json_decode($payment->accounts, true)[0] ?? [];
                                            $method = $methods->firstWhere('unique_name', $details['account_type'] ?? '');
                                            $fieldsMap = $method ? $method->fields_required : [];
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="default-account-checkbox"
                                                    data-id="{{ $payment->id }}"
                                                    {{ $payment->default_account ? 'checked' : '' }}>
                                            </td>
                                            <td>{{ $method ? $method->method_name : ($details['account_type'] ?? '') }}</td>
                                            <td>
                                                @foreach($details as $key => $value)
                                                    @if($key != 'account_type')
                                                        <div>
                                                            <strong>{{ $fieldsMap[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            {{ $value }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <form action="{{ route('user.accountsettings.delete', $payment->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(count($userMethods) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">No payment methods added.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const methodSelect = document.getElementById('method-select');
        const container = document.getElementById('dynamic-fields-container');

        methodSelect.addEventListener('change', function () {
            const selected = methodSelect.options[methodSelect.selectedIndex];
            const fieldsJson = selected.getAttribute('data-fields');
            container.innerHTML = '';

            if (fieldsJson) {
                try {
                    const fields = JSON.parse(fieldsJson);
                    for (const key in fields) {
                        const label = fields[key];
                        const div = document.createElement('div');
                        div.className = 'form-group mb-3';
                        div.innerHTML = `
                            <label>${label}</label>
                            <input type="text" name="additional_fields[${key}]" class="form-control" required>
                        `;
                        container.appendChild(div);
                    }
                } catch (e) {
                    console.error("Invalid JSON in data-fields", e);
                }
            }
        });

        // Handle default checkbox
        document.querySelectorAll('.default-account-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const selectedId = this.getAttribute('data-id');

                fetch("{{ route('user.accountsettings.setdefault') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: selectedId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll('.default-account-checkbox').forEach(box => {
                            box.checked = box.getAttribute('data-id') === selectedId;
                        });
                    } else {
                        alert('Failed to update default.');
                    }
                });
            });
        });
    });
</script>
@endsection
