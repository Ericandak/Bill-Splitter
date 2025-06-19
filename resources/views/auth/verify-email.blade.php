@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Verify Your Email</h3>
                </div>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-3x text-primary"></i>
                        </div>
                        <p class="mb-4">
                            Thanks for signing up! Before getting started, please verify your email address by clicking on 
                            the link we just emailed to you.
                        </p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <div class="d-grid gap-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
