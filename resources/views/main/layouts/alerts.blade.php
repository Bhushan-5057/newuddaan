<div id="flash-container" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: auto; min-width: 300px;">
    @if(session('success'))
        <div class="custom-alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="custom-alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
</div>

<style>
    .custom-alert {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-radius: 4px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        transition: opacity 0.5s ease-in-out;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 5px solid #28a745;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 5px solid #dc3545;
    }
    .custom-alert i {
        margin-right: 12px;
        font-size: 18px;
    }
    .close-btn {
        background: none;
        border: none;
        font-size: 22px;
        margin-left: auto;
        padding-left: 20px;
        cursor: pointer;
        line-height: 1;
        color: inherit;
        opacity: 0.5;
    }
    .close-btn:hover {
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.custom-alert');
        
        alerts.forEach(function(alert) {
            // 10 સેકન્ડ (10000ms) પછી હટાવવા માટે
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500; // fade out effect માટે અડધી સેકન્ડ
            }, 10000); 
        });
    });
</script>