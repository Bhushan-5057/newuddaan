  <!-- Footer -->
<footer class="content-footer footer bg-footer-theme border-top mt-4 py-3">
    <div class="container-xxl d-flex flex-wrap justify-content-between align-items-center">
        <!-- Left Section -->
        <div class="text-muted mb-2 mb-md-0">
            © <script>document.write(new Date().getFullYear());</script>
            {{ company_info('company_name') }}. All rights reserved.
        </div>
    </div>
</footer>
  <!-- / Footer -->
  <div class="content-backdrop fade"></div>
  </div>
  <!-- Content wrapper -->
  </div>
  <!-- / Layout page -->
  </div>
  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>
  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
  <!-- Core JS -->
  <script>
document.addEventListener('DOMContentLoaded', function () {
    // Current page URL
    const currentUrl = window.location.href;
    // All sidebar links
    const menuLinks = document.querySelectorAll('#layout-menu .menu-link');
    menuLinks.forEach(link => {
        // If link matches current URL
        if (currentUrl.includes(link.getAttribute('href'))) {
            // Active child
            link.closest('.menu-item')?.classList.add('active');
            // Open parent menu(s)
            let parent = link.closest('.menu-sub');
            while (parent) {
                parent.previousElementSibling?.classList.add('active');
                parent.closest('.menu-item')?.classList.add('open', 'active');
                parent = parent.parentElement.closest('.menu-sub');
            }
        }
    });
});
</script>
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/js/app-user-view-account.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
  @if (request()->routeIs('dashboard.admin'))
  <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
  <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
  @endif
    <!-- Page JS -->
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
  </body>
  </html>