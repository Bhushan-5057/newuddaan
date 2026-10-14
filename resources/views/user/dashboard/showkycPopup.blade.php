<!-- Rest of your dashboard content here -->

@if(isset($showKycPopup) && $showKycPopup)
<!-- KYC Alert Modal -->
<div class="modal fade" id="kycAlertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="kycAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="kycAlertModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> KYC Action Required
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5 fw-bold text-danger">{{ $kycMessage }}</p>
                <p class="text-muted">Account compliance and continuous services require valid identity verifications.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <!-- Redirecting to your requested route -->
                <a href="{{ route('user.kyc.show') }}" class="btn btn-primary px-4">
                    Go to KYC Page
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to auto-open modal on page load -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('kycAlertModal'));
        myModal.show();
    });
</script>
@endif