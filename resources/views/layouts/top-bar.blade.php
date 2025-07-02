<!-- BEGIN: Top Bar -->
<div class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0">
    <div class="h-full flex items-center justify-between">
        <!-- BEGIN: Logo -->
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="-intro-x flex items-center">
                <img alt="DSSC Logo" class="w-60" src="http://studentportal.dssc.edu.ph:4455/uploads/settings/1_theG1690183835.gif">
                <span class="text-white text-lg ml-3">Supply and Property Inventory Management System</span>
            </a>
        </div>
        <!-- END: Logo -->

        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="hidden sm:flex">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="#">Application</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown ml-auto">
            <div class="dropdown-toggle flex items-center" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                <div class="w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
                    <img alt="{{ auth()->user()->name }}" src="{{ asset('dist/images/logodssc.png') }}">
                </div>
                <div class="hidden sm:block ml-3 text-white">
                    <div class="font-medium">{{ auth()->user()->name }}</div>
                </div>
            </div>
            <div class="dropdown-menu w-56">
                <ul class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                    <li class="p-2">
                        <div class="font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">{{ auth()->user()->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider border-white/[0.08]"></li>
                    <li>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item hover:bg-white/5">
                            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider border-white/[0.08]"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item hover:bg-white/5 w-full text-left">
                                <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->