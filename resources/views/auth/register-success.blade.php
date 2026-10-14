<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/png" />
    <title>Registration Successful</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Bootstrap Modal -->
<div class="modal fade" id="registerSuccessModal" tabindex="-1" aria-labelledby="registerSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(90deg, #4bbee1, #28a745);">
                <h5 class="modal-title" id="registerSuccessModalLabel">
                    <i class='bx bx-user-check me-2'></i> Welcome, {{ $data['name'] ?? 'User' }}!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class='bx bx-check-circle fs-1 text-success mb-3 d-block'></i>
                <p class="lead fw-bold">Your registration was successful!</p>
                
                <hr class="my-3">
                
                <div class="user-info text-start mx-3" style="background: #f8f9fa; border-radius: 10px; padding: 15px 20px;">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="fw-semibold text-secondary">Full Name</span>
                        <span class="fw-bold">{{ $data['name'] ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="fw-semibold text-secondary">Username</span>
                        <span class="fw-bold text-primary">{{ $data['username'] ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="fw-semibold text-secondary">Unique Name</span>
                        <span class="fw-bold">{{ $data['unique_name'] ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="fw-semibold text-secondary">Email</span>
                        <span class="fw-bold">{{ $data['email'] ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="fw-semibold text-secondary">Password</span>
                        <span class="fw-bold text-danger">{{ $data['raw_password'] ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="alert alert-info mt-3" style="font-size: 14px;">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Important:</strong> Please save your credentials. You will need them to login.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
           
                
        <!--// Auto redirect to login after 15 seconds-->
<!-- Go to Login Button -->
<a href="{{ route('login', ['email_username' => $data['username'] ?? ($data['email'] ?? ''), 'password' => $data['raw_password'] ?? '']) }}" class="btn btn-primary px-5 py-2">
    <i class='bx bx-log-in me-1'></i> Go to Login
</a>

            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border: none !important;
        border-radius: 16px !important;
    }

    .modal-header {
        border-bottom: none;
        border-radius: 16px 16px 0 0 !important;
    }

    .modal-footer {
        border-top: none;
        border-radius: 0 0 16px 16px !important;
    }

    .modal-body {
        font-size: 16px;
    }

    .btn-close-white {
        filter: invert(1);
    }

    .lead {
        font-weight: 500;
        color: #28a745;
    }

    .modal-body i.bx-check-circle {
        font-size: 60px;
    }

    .user-info .d-flex {
        padding: 8px 0;
    }

    .user-info .d-flex:last-child {
        border-bottom: none !important;
    }

    @media (max-width: 576px) {
        .modal-content {
            border-radius: 12px !important;
            margin: 10px;
        }
        .user-info {
            padding: 10px 15px !important;
        }
        .user-info .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 2px;
        }
    }
</style>

<!-- Trigger Modal with JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Show modal with backdrop static (prevent closing by clicking outside)
        var myModal = new bootstrap.Modal(document.getElementById('registerSuccessModal'), {
            backdrop: 'static',
            keyboard: false
        });
        myModal.show();


<!-- JavaScript Auto-redirect (15 Seconds) -->

    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function() {
            window.location.href = "{{ route('login', ['email_username' => $data['username'] ?? ($data['email'] ?? ''), 'password' => $data['raw_password'] ?? '']) }}";
        }, 15000);
    });

    });
</script>

</body>
</html>