<!DOCTYPE html>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('dist/images/logodssc.png') }}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="DSSC Inventory Management System">
        <meta name="keywords" content="admin template, inventory, management, system">
        <meta name="author" content="DSSC">
        <title>Reset Password - DSSC Inventory Management System</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Reset Password Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="https://dssc.edu.ph/" class="-intro-x flex items-center pt-5" target="_blank" rel="noopener noreferrer">
                        <img alt="DSSC Logo" height="300" width="300" src="http://studentportal.dssc.edu.ph:4455/uploads/settings/1_theG1690183835.gif">
                    </a>
                    <div class="my-auto">
                        <img alt="DSSC IMS" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/bg.png') }}">
                        <div class="-intro-x text-white font-small text-3xl leading-tight mt-10">
                            Create New Password
                            <br>
                            DSSC Inventory Management System
                        </div>
                    </div>
                </div>
                <!-- END: Reset Password Info -->

                <!-- BEGIN: Reset Password Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left mb-4">
                            Reset Password
                        </h2>
                        
                        <div class="intro-x mt-2 text-slate-400 text-center xl:text-left mb-6">
                            Please enter your new password below.
                        </div>

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="intro-x mt-8">
                                <!-- Email -->
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $request->email) }}" 
                                    placeholder="Email"
                                    required 
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback mt-2 text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <!-- Password -->
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="intro-x login__input form-control py-3 px-4 block mt-4 @error('password') is-invalid @enderror" 
                                    placeholder="New Password"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback mt-2 text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <!-- Confirm Password -->
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="intro-x login__input form-control py-3 px-4 block mt-4" 
                                    placeholder="Confirm New Password"
                                    required
                                >
                            </div>

                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-auto mr-3">
                                    Reset Password
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-auto mt-3 xl:mt-0">
                                    Back to Login
                                </a>
                            </div>
                        </form>

                        <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left">
                            Powered by BSIT-3C: James Reid and Daniel Padilla
                        </div>
                    </div>
                </div>
                <!-- END: Reset Password Form -->
            </div>
        </div>
        
        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>
