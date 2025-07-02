<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icewall Template CSS -->
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body>
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <span class="text-white text-lg ml-3">Menu</span>
            </a>
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
        </div>
    </div>
    <!-- END: Mobile Menu -->

    <!-- BEGIN: Top Bar -->
    @include('layouts.top-bar')
    <!-- END: Top Bar -->

    <!-- BEGIN: Main Layout -->
    <div class="flex">
        <!-- BEGIN: Side Menu -->
        @include('layouts.sidebar')
        <!-- END: Side Menu -->

        <!-- BEGIN: Content -->
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
                    <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i> 
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
                    <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
        <!-- END: Content -->
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="{{ asset('dist/js/app.js') }}"></script>
    <!-- END: JS Assets-->

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initialize all modals
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(function(el) {
                const modal = tailwind.Modal.getOrCreateInstance(el);
            });

            // Mobile menu toggle
            const mobileMenuToggler = document.querySelector('.mobile-menu-toggler');
            if (mobileMenuToggler) {
                mobileMenuToggler.addEventListener('click', function() {
                    const sideNav = document.querySelector('.side-nav');
                    sideNav.classList.toggle('active');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
