@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Profile Settings</h2>
</div>

<div class="grid grid-cols-12 gap-6">
    <!-- BEGIN: Profile Information -->
    <div class="col-span-12">
        <!-- BEGIN: Display Information -->
        <div class="intro-y box lg:mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Profile Information</h2>
            </div>
            <div class="p-5">
                <form method="post" action="{{ route('profile.update') }}" class="grid grid-cols-12 gap-5">
                    @csrf
                    @method('patch')
                    
                    <div class="col-span-12 xl:col-span-6">
                        <div class="input-form">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-span-12 xl:col-span-6">
                        <div class="input-form">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-12 flex items-center justify-start">
                        <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                        @if (session('status') === 'profile-updated')
                            <div class="text-success">Saved.</div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Display Information -->

        <!-- BEGIN: Change Password -->
        <div class="intro-y box mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Change Password</h2>
            </div>
            <div class="p-5">
                <form method="post" action="{{ route('profile.password') }}" class="grid grid-cols-12 gap-5">
                    @csrf
                    @method('put')

                    <div class="col-span-12 xl:col-span-6">
                        <div class="input-form">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
                            @error('current_password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-6">
                        <div class="input-form">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-6">
                        <div class="input-form">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-12 flex items-center justify-start">
                        <button type="submit" class="btn btn-primary mr-2">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <div class="text-success">Saved.</div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Change Password -->

        <!-- BEGIN: Delete Account -->
        <div class="intro-y box mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Delete Account</h2>
            </div>
            <div class="p-5">
                <div class="text-slate-500 mb-4">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                </div>

                <button type="button" class="btn btn-danger" data-tw-toggle="modal" data-tw-target="#delete-account-modal">
                    Delete Account
                </button>

                <!-- BEGIN: Delete Account Modal -->
                <div id="delete-account-modal" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Delete Account</h2>
                            </div>
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <div class="text-slate-500">
                                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                                    </div>
                                </div>
                                <div class="col-span-12">
                                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-3">
                                        @csrf
                                        @method('delete')

                                        <div class="input-form">
                                            <label for="delete-account-password" class="form-label">Password</label>
                                            <input type="password" id="delete-account-password" name="password" class="form-control" required>
                                            @error('password', 'userDeletion')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mt-5 text-right">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                            <button type="submit" class="btn btn-danger w-24">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Delete Account Modal -->
            </div>
        </div>
        <!-- END: Delete Account -->
    </div>
</div>
@endsection
