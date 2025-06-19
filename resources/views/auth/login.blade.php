@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Welcome Back!</h3>
                    <p class="small mb-0">Sign in to manage your bills</p>
                </div>

                <div class="card-body p-4">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="remember" 
                                       id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Sign In
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-muted" href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                <div class="card-footer text-center py-3">
                    <p class="mb-0">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
.card {
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.9);
}

.form-control {
    border-radius: 8px;
    padding: 12px;
}

.btn-primary {
    padding: 12px;
    border-radius: 8px;
}

body {
    background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
    min-height: 100vh;
}
</style>
@endsection
