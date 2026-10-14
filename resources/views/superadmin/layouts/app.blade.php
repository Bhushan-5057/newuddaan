    @include('superadmin.layouts.header')
    @yield('content')
    @include('superadmin.layouts.footer')
    {{-- This is important --}}
    @yield('scripts')
</body>
<script>
     @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif
</script>
