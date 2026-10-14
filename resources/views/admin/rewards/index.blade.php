@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-dark text-white p-3">
            <h5 class="mb-0 text-white fw-bold"><i class="fas fa-th-list me-2"></i> Rank Reward Master Configuration</h5>
            <small class="text-muted text-light">Modify cell values and click the "Update" button on the right side of the row to save changes.</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="excelRankTable">
                    <thead class="table-light text-uppercase text-secondary small">
                        <tr>
                            <th class="bg-light text-center" width="50">ID</th>
                            <th>Rank Name</th>
                            <th class="bg-warning-light text-primary text-end">Left Target (BV)</th>
                            <th class="bg-warning-light text-primary text-end">Right Target (BV)</th>
                            <th class="bg-warning-light text-success text-end">Bonus (₹)</th>
                            <th class="bg-warning-light">Reward Tier</th>
                            <th class="bg-warning-light">Bonanza</th>
                            <th class="bg-warning-light">Duration</th>
                            <th class="text-center" width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ranks as $rank)
                        <tr id="row-{{ $rank->id }}" data-id="{{ $rank->id }}">
                            <td class="text-center bg-light fw-bold text-muted">{{ $rank->id }}</td>
                            
                            <td contenteditable="true" data-field="name" class="editable-cell fw-bold">{{ $rank->name }}</td>
                            
                            <td contenteditable="true" data-field="left" class="editable-cell fw-medium text-end">{{ $rank->left }}</td>
                            <td contenteditable="true" data-field="right" class="editable-cell fw-medium text-end">{{ $rank->right }}</td>
                            <td contenteditable="true" data-field="bonus" class="editable-cell fw-bold text-success text-end">{{ $rank->bonus }}</td>
                            <td contenteditable="true" data-field="reward" class="editable-cell text-uppercase">{{ $rank->reward }}</td>
                            <td contenteditable="true" data-field="bonanza" class="editable-cell">{{ $rank->bonanza }}</td>
                            <td contenteditable="true" data-field="duration" class="editable-cell text-muted">{{ $rank->duration }}</td>
                            
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success px-3" onclick="saveRankRow(this, {{ $rank->id }})">
                                    <i class="fas fa-save me-1"></i> Update
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted p-4">No records found in the system configuration datatable.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999;">
    <div id="saveToast" class="toast align-items-center text-white bg-success border-0 p-2" role="alert" style="display: none; min-width: 280px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
        <div class="d-flex align-items-center justify-content-between">
            <div class="toast-body fw-bold" id="toastMessage">Updating data...</div>
            <button type="button" class="btn-close btn-close-white ms-2" onclick="document.getElementById('saveToast').style.display='none';"></button>
        </div>
    </div>
</div>

<style>
    #excelRankTable th, #excelRankTable td {
        padding: 12px 15px;
        border: 1px solid #dee2e6 !important;
    }
    .editable-cell {
        cursor: text;
        transition: background-color 0.15s;
    }
    .editable-cell:focus {
        background-color: #fffbeb !important;
        outline: 2px solid #28a745 !important;
    }
    .bg-light { background-color: #f8fafc !important; }
</style>

<script>
function saveRankRow(buttonElement, rowId) {
    if (typeof $ === 'undefined') {
        alert("jQuery is missing! Please check your master template.");
        return;
    }

    let btn = $(buttonElement);
    let row = $('#row-' + rowId);
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');

    let fieldsData = {};
    row.find('.editable-cell').each(function() {
        let cell = $(this);
        let fieldName = cell.data('field');
        let cellValue = cell.text().trim();
        fieldsData[fieldName] = cellValue;
    });

    let token = '{{ csrf_token() }}';

    $.ajax({
        url: "{{ route('admin.ranks.updateField') }}",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: {
            id: rowId,
            fields: fieldsData 
        },
        success: function(response) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update');
            if(response.success) {
                row.find('.editable-cell').css('background-color', '#bbf7d0');
                setTimeout(() => row.find('.editable-cell').css('background-color', ''), 800);
                
                $('#saveToast').removeClass('bg-danger').addClass('bg-success');
                $('#toastMessage').text(response.message || "Row updated successfully!");
                $('#saveToast').stop(true, true).fadeIn();
                setTimeout(() => $('#saveToast').fadeOut(), 3500);
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update');
            row.find('.editable-cell').css('background-color', '#fecaca');
            setTimeout(() => row.find('.editable-cell').css('background-color', ''), 800);
            
            let errMsg = "Data sync fault exception!";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            }
            
            $('#saveToast').removeClass('bg-success').addClass('bg-danger');
            $('#toastMessage').text(errMsg);
            $('#saveToast').stop(true, true).fadeIn();
            setTimeout(() => $('#saveToast').fadeOut(), 3500);
        }
    });
}
</script>
@endsection