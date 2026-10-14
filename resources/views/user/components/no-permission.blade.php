@extends('user.layouts.app')

@section('content')
    <div class="container-fluid py-5 d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="text-center border border-danger rounded p-5 bg-white shadow" style="max-width: 400px; transition: transform 0.3s;">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem; animation: bounce 1s infinite;"></i>
            </div>
            <h3 class="text-danger mb-3">Access Denied</h3>
            <p class="text-muted">You do not have permission to access this page.</p>
            <a href="{{ route('dashboard.user') }}" class="btn btn-danger mt-3" style="border-radius: 25px; padding: 10px 20px;">
                <i class="fas fa-arrow-left me-2"></i> Go Back
            </a>
        </div>
    </div>

    <style>
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .border-danger {
            border-width: 2px;
        }
        
             @media (max-width: 576px) {

              
            .text-center {
                padding: 20px;
            }

            .btn-danger {
                width: 100%;
            }
        }
    </style>


@endsection