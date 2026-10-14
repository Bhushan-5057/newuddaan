@extends('user.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="nav nav-pills gap-2">
                <a href="{{ route('user.fundrequest.index') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.fundrequest.index') ? 'active bg-primary ' : '' }}">
                    <i class="fas fa-hand-holding-usd me-2"></i> Add Fund Request
                </a>
                <a href="{{ route('user.fundrequest.history') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.fundrequest.history') ? 'active bg-primary ' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i> Fund Request History
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom border-secondary py-3">
            <h3 class="card-title mb-0 d-flex align-items-center h5">
                <i class="fas fa-coins me-3 text-warning"></i> FUND DEPOSIT REQUEST
            </h3>
        </div>

        <div class="card-body  p-0">
            <div class="row g-0">
                <div class="col-lg-7 p-4 border-end ">
                    <form action="{{ route('user.fundrequest.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="position-relative mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">
                                <i class="fas fa-credit-card me-2"></i> Payment Method
                            </label>
                            <button type="button" class="form-select text-start d-flex align-items-center justify-content-between " id="method-button">
                                <span class="d-flex align-items-center">
                                    <span id="method-icon-display" class="me-2 text-primary">
                                        <i class="fas fa-credit-card"></i>
                                    </span>
                                    <span id="method-selected-text">Select Payment Method</span>
                                </span>
                            </button>
                            <input type="hidden" name="method" id="method-actual-input" required>
                            
                            <div class="dropdown-menu w-100 shadow border-0 mt-1 p-2" id="method-dropdown" style="display: none; max-height: 280px; overflow-y: auto; z-index: 1050;">
                                @foreach($methods as $method)
                                    <div class="dropdown-item d-flex align-items-center p-2 rounded cursor-pointer border-bottom " 
                                         style="cursor: pointer;"
                                         data-value="{{ $method->slug }}" 
                                         data-text="{{ $method->name }}" 
                                         data-icon="{{ $method->icon_class ?? 'fas fa-coins text-warning' }}">
                                        <i class="{{ $method->icon_class ?? 'fas fa-coins text-warning' }} fs-5 me-3"></i>
                                        <div>
                                            <div class="fw-bold  small">{{ $method->name }}</div>
                                            <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $method->description ?? 'Choose this method' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="position-relative mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">
                                <i class="fas fa-money-bill-wave me-2"></i> Payment Type
                            </label>
                            <button type="button" class="form-select text-start d-flex align-items-center justify-content-between " id="type-button" disabled>
                                <span class="d-flex align-items-center">
                                    <span id="type-icon-display" class="me-2 text-secondary">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <span id="type-selected-text">Select Payment Type</span>
                                </span>
                            </button>
                            <input type="hidden" name="payment_type" id="type-actual-input" required>
                            
                            <div class="dropdown-menu w-100 shadow border-0 mt-1 p-2" id="type-dropdown" style="display: none; max-height: 280px; overflow-y: auto; z-index: 1050;">
                                <div class="dropdown-item d-flex align-items-center p-2 text-muted disabled">
                                    <i class="fas fa-info-circle me-3"></i>
                                    <div>
                                        <div class="fw-bold small">Select Payment Type</div>
                                        <div style="font-size: 0.75rem;">Please select a method first.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">
                                <i class="fas fa-dollar-sign me-2"></i> Amount ({{ company_info('currency', '$') }})
                            </label>
                            <input type="number" name="amount" class="form-control" step="0.01" id="amountInput" required placeholder="Enter deposit amount">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">
                                <i class="fas fa-receipt me-2"></i> Transaction Reference (UTR)
                            </label>
                            <input type="text" name="utr_number" class="form-control" required placeholder="Enter transaction reference number">
                        </div>

                
                        <!-- Custom File Upload Area wrapper -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary text-uppercase small">
                                <i class="fas fa-file-upload me-2"></i> Payment Proof
                            </label>
                            <div class="row g-3">
                                <!-- Input Drop Zone -->
                                <div class="col-sm-8">
                                    <div class="border border-dashed border-secondary rounded p-4 text-center  shadow-sm position-relative h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="fas fa-cloud-upload-alt text-primary fs-2 mb-2"></i>
                                        <p class="mb-1  small fw-bold" id="file-label-text">Click or drag layout to upload payment screenshot</p>
                                        <p class="text-muted small mb-0" id="file-meta-text" style="font-size: 0.75rem;">Accepted Formats: PNG, JPG, JPEG up to 2MB</p>
                                        <input type="file" name="payment_slip" class="stretched-link opacity-0" accept="image/*" required id="paymentSlipInput">
                                    </div>
                                </div>
                                <!-- Live Preview Thumbnail Box -->
                                <div class="col-sm-4">
                                    <div class="border rounded  p-2 d-flex flex-column align-items-center justify-content-center text-center shadow-sm h-100" style="min-height: 125px;">
                                        <div id="preview-placeholder" class="text-muted small">
                                            <i class="fas fa-image fs-3 mb-1 d-block opacity-50"></i>
                                            No Preview
                                        </div>
                                        <img id="slip-preview-img" src="" alt="Slip Preview" class="img-fluid rounded d-none shadow-sm" style="max-height: 110px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-2 shadow-sm text-uppercase fw-bold fs-6">
                            <i class="fas fa-paper-plane me-2"></i> Submit Request
                        </button>
                    </form>
                </div>

                <div class="col-lg-5 p-4  align-self-stretch d-flex flex-column justify-content-center">
                    <div id="qr-section-container">
                        
                        <div id="qr-container" class="d-none border  rounded  p-4 text-center shadow-sm">
                            <h5 class=" mb-4 border-bottom pb-2 fw-bold small text-uppercase tracking-wider">
                                <i class="fas fa-qrcode text-primary me-2"></i> Deposit Details
                            </h5>

                            <div class="mb-4  d-inline-block p-3 rounded border ">
                                <img id="qr-image" src="" alt="QR Code" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>

                            <div class="input-group mb-4 shadow-sm">
                                <span class="form-control text-truncate  border-end-0 small text-center fw-bold text-muted py-2" id="wallet-address" style="font-size: 0.85rem;"></span>
                                <button type="button" class="btn btn-primary px-3 copy-btn fw-bold text-uppercase" id="clipCopyBtn" onclick="copyToClipboard()">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>

                            <div class="text-start p-3  rounded border ">
                                <h6 class="text-secondary fw-bold mb-3 small d-flex align-items-center">
                                    <i class="fas fa-info-circle text-primary me-2"></i> PROCESS INSTRUCTIONS
                                </h6>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge bg-primary rounded-circle me-2 mt-1 px-2 d-inline-flex justify-content-center align-items-center" style="width:20px; height:20px; font-size:0.7rem;">1</span>
                                    <p class="text-muted small mb-0">Scan the QR code or copy the string address to clipboard.</p>
                                </div>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge bg-primary rounded-circle me-2 mt-1 px-2 d-inline-flex justify-content-center align-items-center" style="width:20px; height:20px; font-size:0.7rem;">2</span>
                                    <p class="text-muted small mb-0">Execute transfer configuration matching precise amount calculated.</p>
                                </div>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge bg-primary rounded-circle me-2 mt-1 px-2 d-inline-flex justify-content-center align-items-center" style="width:20px; height:20px; font-size:0.7rem;">3</span>
                                    <p class="text-muted small mb-0">Upload verifiable generated payment proof statement with proper matching text reference fields.</p>
                                </div>
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-circle me-2 mt-1 px-2 d-inline-flex justify-content-center align-items-center" style="width:20px; height:20px; font-size:0.7rem;">4</span>
                                    <p class="text-muted small mb-0">Commit final context submit action payload for rapid automated administrative audits validation checks.</p>
                                </div>
                            </div>
                        </div>

                        <div id="qr-placeholder" class="border  rounded  p-5 text-center shadow-sm">
                            <div class="mb-3 text-muted">
                                <i class="fas fa-wallet display-4 text-secondary opacity-50"></i>
                            </div>
                            <h5 class=" mb-2 fw-bold">Deposit Credentials</h5>
                            <p class="text-secondary small mb-0 mx-auto" style="max-width: 280px;">Please pick a primary payment engine option to parse individual transaction parameters.</p>
                        </div>

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
        const methodButton = document.getElementById('method-button');
        const methodDropdown = document.getElementById('method-dropdown');
        const methodSelectedText = document.getElementById('method-selected-text');
        const methodActualInput = document.getElementById('method-actual-input');
        const methodIconDisplay = document.getElementById('method-icon-display');

        const typeButton = document.getElementById('type-button');
        const typeDropdown = document.getElementById('type-dropdown');
        const typeSelectedText = document.getElementById('type-selected-text');
        const typeActualInput = document.getElementById('type-actual-input');
        const typeIconDisplay = document.getElementById('type-icon-display');

        const qrContainer = document.getElementById('qr-container');
        const qrPlaceholder = document.getElementById('qr-placeholder');
        const qrImage = document.getElementById('qr-image');
        const walletAddress = document.getElementById('wallet-address');
        const fileInput = document.getElementById('paymentSlipInput');
        const fileLabelText = document.getElementById('file-label-text');

        // Display Chosen File Name
 // Image Select Preview and Size Validation Logic
if (fileInput) {
    fileInput.addEventListener('change', function () {
        const fileLabelText = document.getElementById('file-label-text');
        const fileMetaText = document.getElementById('file-meta-text');
        const previewImg = document.getElementById('slip-preview-img');
        const previewPlaceholder = document.getElementById('preview-placeholder');

        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // 1. Calculate and Format File Size (KB / MB)
            let fileSizeStr = "";
            if (file.size < 1024 * 1024) {
                fileSizeStr = (file.size / 1024).toFixed(2) + " KB";
            } else {
                fileSizeStr = (file.size / (1024 * 1024)).toFixed(2) + " MB";
            }

            // 2. Validate Size Limit (e.g., Max 2MB checking)
            const maxLimit = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxLimit) {
                fileLabelText.textContent = "File Too Large!";
                fileLabelText.className = "mb-1 text-danger small fw-bold";
                fileMetaText.textContent = `Selected: ${fileSizeStr} (Max limit is 2.00 MB)`;
                fileMetaText.className = "text-danger small mb-0";
                
                // Reset file fields & previews
                this.value = ""; 
                previewImg.classList.add('d-none');
                previewPlaceholder.classList.remove('d-none');
                return;
            }

            // 3. Render Custom Labels & Success UI 
            fileLabelText.textContent = file.name;
            fileLabelText.className = "mb-1 text-success small fw-bold";
            fileMetaText.textContent = `File Size: ${fileSizeStr}`;
            fileMetaText.className = "text-muted small mb-0 fw-bold";

            // 4. Read File buffer for Local Preview Rendering
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                previewPlaceholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);

        } else {
            // Fallback clear state if selection cancelled
            fileLabelText.textContent = "Click or drag layout to upload payment screenshot";
            fileLabelText.className = "mb-1  small fw-bold";
            fileMetaText.textContent = "Accepted Formats: PNG, JPG, JPEG up to 2MB";
            fileMetaText.className = "text-muted small mb-0";
            
            previewImg.classList.add('d-none');
            previewPlaceholder.classList.remove('d-none');
            previewImg.src = "";
        }
    });
}

        // Dropdown Visibility Handler logic toggles
        function toggleDropdown(dropdownElement) {
            const isVisible = dropdownElement.style.display === 'block';
            closeAllDropdowns();
            if (!isVisible) {
                dropdownElement.style.display = 'block';
            }
        }

        function closeAllDropdowns() {
            methodDropdown.style.display = 'none';
            typeDropdown.style.display = 'none';
        }

        methodButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(methodDropdown);
        });

        typeButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(typeDropdown);
        });

        // Event listener selection mapping bindings for layout configurations
        methodDropdown.querySelectorAll('.dropdown-item').forEach(option => {
            option.addEventListener('click', function() {
                const value = this.dataset.value;
                const text = this.dataset.text;
                const iconClass = this.dataset.icon;

                methodSelectedText.textContent = text;
                methodActualInput.value = value;
                methodIconDisplay.innerHTML = `<i class="${iconClass}"></i>`;
                methodDropdown.style.display = 'none';

                fetchPaymentTypes(value);
            });
        });

        function fetchPaymentTypes(slug) {
            // Reset state architecture displays
            typeDropdown.innerHTML = '';
            typeSelectedText.textContent = 'Select Payment Type';
            typeActualInput.value = '';
            typeIconDisplay.innerHTML = '<i class="fas fa-money-bill-wave text-secondary"></i>';

            qrContainer.classList.add('d-none');
            qrPlaceholder.classList.remove('d-none');
            qrPlaceholder.innerHTML = `
                <div class="py-3">
                    <i class="fas fa-spinner fa-spin text-primary fs-3 mb-2"></i>
                    <h5 class=" small mb-1 fw-bold">Processing System Sync</h5>
                    <p class="text-muted extra-small mb-0">Querying live platform endpoints...</p>
                </div>
            `;
            qrImage.src = '';
            walletAddress.textContent = '';

            if (!slug) {
                typeButton.disabled = true;
                return;
            }

            typeButton.disabled = false;

            fetch(`/user/get-payment-types?slug=${slug}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network structure failure error pipeline state response.');
                    return response.json();
                })
                .then(data => {
                    typeDropdown.innerHTML = ''; 
                    if (data && data.length > 0) {
                        data.forEach(child => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'dropdown-item d-flex align-items-center p-2 rounded cursor-pointer border-bottom ';
                            optionDiv.style.cursor = 'pointer';
                            optionDiv.dataset.value = child.slug;
                            optionDiv.dataset.text = child.name;
                            optionDiv.dataset.image = child.image;
                            optionDiv.dataset.address = child.address;
                            optionDiv.dataset.icon = child.icon_class ?? 'fas fa-dollar-sign text-success';

                            optionDiv.innerHTML = `
                                <i class="${child.icon_class ?? 'fas fa-dollar-sign text-success'} fs-5 me-3"></i>
                                <div>
                                    <div class="fw-bold  small">${child.name}</div>
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">${child.description ?? 'Select option layout payload'}</div>
                                </div>
                            `;
                            
                            typeDropdown.appendChild(optionDiv);

                            optionDiv.addEventListener('click', function() {
                                handleTypeSelection(this);
                            });
                        });

                        // Cascade Auto Execution Rule Trigger for matching arrays length constraints
                        if (data.length === 1) {
                            typeDropdown.querySelector('.dropdown-item').click();
                        } else {
                            qrPlaceholder.innerHTML = `
                                <div>
                                    <i class="fas fa-wallet display-4 text-secondary opacity-50 mb-3"></i>
                                    <h5 class=" mb-2 fw-bold">Select Variant Subtype</h5>
                                    <p class="text-secondary small mb-0">Choose specific provider variation mapping channel asset execution options.</p>
                                </div>
                            `;
                        }
                    } else {
                        typeDropdown.innerHTML = `
                            <div class="dropdown-item p-2 text-muted disabled">
                                <i class="fas fa-exclamation-triangle me-2"></i> No active structural variations configured layout.
                            </div>`;
                        qrPlaceholder.innerHTML = `
                            <div>
                                <i class="fas fa-exclamation-triangle text-warning display-4 mb-3"></i>
                                <h5 class=" mb-2 fw-bold">Unavailable Channels</h5>
                                <p class="text-secondary small mb-0">No dynamic providers endpoints mapped for core parameter keys context lookup.</p>
                            </div>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching structural endpoints components:', error);
                    typeDropdown.innerHTML = '<div class="dropdown-item text-danger p-2 disabled"><i class="fas fa-times-circle me-2"></i> Fetch Error</div>';
                    qrPlaceholder.innerHTML = `
                        <div>
                            <i class="fas fa-times-circle text-danger display-4 mb-3"></i>
                            <h5 class=" mb-2 fw-bold">System Connection Failure</h5>
                            <p class="text-secondary small mb-0">Failed tracking dynamic metadata structures options models fallback state.</p>
                        </div>`;
                });
        }

        function handleTypeSelection(optionElement) {
            const image = optionElement.dataset.image;
            const address = optionElement.dataset.address;
            const text = optionElement.dataset.text;
            const value = optionElement.dataset.value;
            const iconClass = optionElement.dataset.icon;

            typeSelectedText.textContent = text;
            typeActualInput.value = value;
            typeIconDisplay.innerHTML = `<i class="${iconClass}"></i>`;
            typeDropdown.style.display = 'none';

            if (image && address) {
                qrImage.src = `/storage/${image}`;
                walletAddress.textContent = address;
                qrContainer.classList.remove('d-none');
                qrPlaceholder.classList.add('d-none');
            } else {
                qrContainer.classList.add('d-none');
                qrPlaceholder.classList.remove('d-none');
                qrImage.src = '';
                walletAddress.textContent = '';
                qrPlaceholder.innerHTML = `
                    <div>
                        <i class="fas fa-hand-pointer text-muted display-4 mb-3"></i>
                        <h5 class=" mb-2 fw-bold">Invalid Option Configuration</h5>
                        <p class="text-secondary small mb-0">Target entry properties missing standard file references endpoints payloads keys.</p>
                    </div>`;
            }
        }

        // Dismiss execution context actions when catching outside window triggers instances
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#method-button') && !event.target.closest('#method-dropdown') &&
                !event.target.closest('#type-button') && !event.target.closest('#type-dropdown')) {
                closeAllDropdowns();
            }
        });
    });

    function copyToClipboard() {
        const walletAddressElement = document.getElementById('wallet-address');
        const copyBtn = document.getElementById('clipCopyBtn');
        if (!walletAddressElement || !copyBtn) return;

        const textToCopy = walletAddressElement.textContent.trim();
        if(!textToCopy) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            const originalHtml = copyBtn.innerHTML;
            
            copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> COPIED!';
            copyBtn.classList.remove('btn-primary');
            copyBtn.classList.add('btn-success');

            setTimeout(() => {
                copyBtn.innerHTML = originalHtml;
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-primary');
            }, 2000);
        }).catch(err => {
            console.error('Failed copying context attributes value to system interface registers:', err);
        });
    }
</script>
@endsection