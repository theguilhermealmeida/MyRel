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
    <title>MyRel - Login</title>

    <link href="{{ asset('./dist/css/tabler.min.css') }}" rel="stylesheet">

</head>

<body class=" border-top-wide border-primary d-flex flex-column theme-dark">
    <style>
        input {
            color: white;
        }
    </style>
    @if (session('error'))
    <div class="alert alert-danger">
         {{ session('error') }}
    </div>
 @endif
    @if (session('status'))
    <div class="alert alert-success" role='alert'>
         {{ session('status') }}
    </div>
 @endif
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36"
                        alt=""></a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Login to your account</h2>


                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input id="email" type="email" class="form-control" name="email"
                            value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="error">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>


                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a  href="/forgot-password">I forgot password</a>
                            </span>
                        </label>
                        <div class="input-group input-group-flat">
                            <input id="password" type="password" name="password" class="form-control" required>
                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember"
                                {{ old('remember') ? 'checked' : '' }} />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </div>

            </form>
            <div class="text-center text-muted mt-3">
                Don't have account yet? <a class="button button-outline" href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script type="text/javascript" src={{ asset('dist/js/tabler.min.js') }} defer>
        < /body> <
        /html>
