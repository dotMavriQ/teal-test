@extends('layouts.app')

@section('content')
<div class="container login-page">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card login-form-container">
                <div class="card-header">
                    <h4 class="text-center">{{ __('Welcome to TEAL') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('simple.login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <span class="material-icons">email</span> {{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <span class="material-icons">lock</span> {{ __('Password') }}
                            </label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary w-100">
                                <span class="material-icons">login</span> {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection