@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="authentication-wrapper authentication-cover">
    <a href="{{ route('login') }}" class="app-brand auth-cover-brand">
        <span class="app-brand-logo demo">
            <img src="{{ asset('fosalogo.png') }}" alt="FOSA" style="height:40px;width:auto;" />
        </span>
        <span class="app-brand-text demo text-heading fw-bold">FOSA</span>
    </a>

    <div class="authentication-inner row m-0">
        <div class="d-none d-lg-flex col-lg-8 p-0">
            <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                <img src="{{ asset('vuexy/img/illustrations/auth-login-illustration-light.png') }}"
                    alt="auth-cover" class="my-5 auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png">
                <img src="{{ asset('vuexy/img/illustrations/bg-shape-image-light.png') }}"
                    alt="bg-shape" class="platform-bg"
                    data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png">
            </div>
        </div>

        <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
            <div class="w-px-400 mx-auto mt-12 pt-5">
                <h4 class="mb-1">Welcome to FOSA! 👋</h4>
                <p class="mb-6">Please sign-in to your account</p>

                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="login" class="form-label">Email or Username</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror"
                            id="login" name="login"
                            placeholder="Enter your email or username"
                            value="{{ old('login') }}"
                            autofocus required>
                    </div>
                    <div class="mb-6 form-password-toggle">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control"
                                name="password" placeholder="············" required />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="my-8">
                        <div class="form-check mb-0 ms-2">
                            <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                            <label class="form-check-label" for="remember-me">Remember Me</label>
                        </div>
                    </div>
                    <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
