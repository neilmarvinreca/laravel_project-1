<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Application Styles -->
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('styles')
</head>
<body class="antialiased">
    <!-- Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-white text-lg ml-3">{{ config('app.name', 'Laravel') }}</span>
            </a>
            <button type="button" class="mobile-menu-toggler" aria-label="Toggle menu">
                <i data-lucide="menu" class="w-8 h-8 text-white"></i>
            </button>
        </div>
    </div>

    <!-- Top Navigation -->
    @includeWhen(View::exists('layouts.top-bar'), 'layouts.top-bar')

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @includeWhen(View::exists('layouts.sidebar'), 'layouts.sidebar')

        <!-- Page Content -->
        <main class="content flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Session Messages -->
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if (session()->has($msg))
                    <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible show flex items-center mb-4" role="alert">
                        @php
                            $icons = [
                                'success' => 'check-circle',
                                'error' => 'alert-octagon',
                                'warning' => 'alert-triangle',
                                'info' => 'info'
                            ];
                        @endphp
                        <i data-lucide="{{ $icons[$msg] ?? 'info' }}" class="w-6 h-6 mr-2"></i>
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif
            @endforeach

            @hasSection('content')
                @yield('content')
            @else
                <div class="p-4">
                    @yield('body')
                </div>
            @endif
        </main>
    </div>

    <!-- Application JavaScript -->
    <script src="{{ asset('dist/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Initialize modals
            document.querySelectorAll('.modal').forEach(modal => {
                if (typeof tailwind !== 'undefined') {
                    tailwind.Modal.getOrCreateInstance(modal);
                }
            });

            // Mobile menu toggle
            const mobileMenuToggler = document.querySelector('.mobile-menu-toggler');
            if (mobileMenuToggler) {
                mobileMenuToggler.addEventListener('click', () => {
                    document.querySelector('.side-nav')?.classList.toggle('active');
                });
            }

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const closeBtn = alert.querySelector('[data-tw-dismiss="alert"]');
                    if (closeBtn) closeBtn.click();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
