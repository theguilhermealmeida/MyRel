<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta5
* @link https://tabler.io
* Copyright 2018-2022 The Tabler Authors
* Copyright 2018-2022 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>MyRel - Reset Password</title>

    <link href="{{ asset('./dist/css/tabler.min.css') }}" rel="stylesheet">

</head>

<body class=" border-top-wide border-primary d-flex flex-column theme-dark">
    <style>
        input {
            color: white;
        }
    </style>


@if ($errors->has('email'))
    <div class="alert alert-danger">
        {{ $errors->first('email') }}
    </div>
@endif



    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36"
                        alt=""></a>
            </div>
           
            <form method="POST" action="{{ route('password.update') }}">
                {{ csrf_field() }}

                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Create your new password</h2>

                    <div class="mb-3">
                        <label class="form-label" for="email">E-Mail Address</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="error">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" type="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="error">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="password-confirm">Confirm Password</label>
                        <input class="form-control" id="password-confirm" type="password" name="password_confirmation" required>
                    </div>

                    <div class="form-footer">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <button class="btn btn-primary w-100" type="submit">
                            Reset Password
                        </button>
                        {{-- <div class="text-center text-muted mt-3">
                          Already have an account? <a class="button button-outline" href="{{ route('login') }}">Login</a>
                        </div> --}}
                    </div>
            </form>
            
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script type="text/javascript" src={{ asset('dist/js/tabler.min.js') }} defer>
        < /body> <
        /html>
