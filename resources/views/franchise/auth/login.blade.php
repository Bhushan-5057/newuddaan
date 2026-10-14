<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Franchise Login | {{ company_info('title') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl px-8 py-10">

            {{-- LOGO --}}
            <div class="text-center mb-8">
                <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ company_info('title') }}"
                    class="mx-auto h-16 sm:h-20 object-contain mb-4">

                <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                    {{ company_info('title') }}
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Franchise Login
                </p>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('franchise.login.submit') }}" class="space-y-5">
                @csrf

                {{-- LOGIN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Username / Email / Mobile
                    </label>
                    <input type="text" name="login" value="{{ old('login') }}"
                        placeholder="Enter username, email or mobile"
                        class="w-full h-11 px-4 rounded-lg border border-gray-300
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  focus:border-blue-500 text-sm">
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>

                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter password"
                            class="w-full h-11 px-4 pr-11 rounded-lg border border-gray-300
                      focus:outline-none focus:ring-2 focus:ring-blue-500
                      focus:border-blue-500 text-sm">

                        <!-- Eye Button -->
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                            <!-- Eye Open -->
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                         c4.478 0 8.268 2.943 9.542 7
                         -1.274 4.057-5.064 7-9.542 7
                         -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <!-- Eye Closed -->
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                         c-4.478 0-8.268-2.943-9.543-7
                         a9.973 9.973 0 012.442-3.998M6.223 6.223
                         A9.953 9.953 0 0112 5
                         c4.478 0 8.268 2.943 9.543 7
                         a9.97 9.97 0 01-4.293 5.774M15 12a3 3 0 00-3-3
                         m0 0a3 3 0 013 3m-3-3
                         L3 3m18 18L3 3" />
                            </svg>
                        </button>
                    </div>
                </div>


                {{-- OPTIONS --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Remember me
                    </label>

                    <a href="javascript:void(0)" class="text-blue-600 hover:underline">
                        Forgot password?
                    </a>
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    class="w-full h-11 bg-blue-600 text-white rounded-lg
                               hover:bg-blue-700 transition font-medium text-sm">
                    Login
                </button>
            </form>
        </div>

        {{-- FOOTER --}}
        <p class="text-center text-xs text-gray-500 mt-6">
            © {{ date('Y') }} {{ company_info('company_name') }}. All rights reserved.
        </p>
    </div>

    {{-- LOGIN ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <script>
            let errorHtml = '';
            @foreach ($errors->all() as $error)
                errorHtml += `<div class="text-left text-sm">• {{ $error }}</div>`;
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorHtml,
                confirmButtonText: 'Fix it'
            });
        </script>
    @endif

    <script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeClosed.classList.add('hidden');
        eyeOpen.classList.remove('hidden');
    }
}
</script>


</body>

</html>
