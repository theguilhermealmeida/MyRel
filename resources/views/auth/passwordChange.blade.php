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
    <title>MyRel - Change Password</title>

    <link href="{{ asset('./dist/css/tabler.min.css') }}" rel="stylesheet">

</head>

<body class=" border-top-wide border-primary d-flex flex-column theme-dark">
    <style>
        input {
            color: white;
        }
    </style>
   
   @if (session('status'))
      <div class='alert alert-success' role='alert'> 
        {{session('status')}}
      </div>
   @endif

    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36"
                        alt=""></a>
            </div>
            

            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Want to change your password?</h2>


                    <div class="mb-3">
                        <label class="form-label" for="email">E-Mail Address</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="error">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Send reset password e-mail</button>
                    </div>

                </div>
            </form>
            <div class="text-center text-muted mt-3">
                Back to profile <a class="button button-outline" href="../user/{{Auth::user()->id}}">Profile</a>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script type="text/javascript" src={{ asset('dist/js/tabler.min.js') }} defer>
        < /body> <
        /html>
