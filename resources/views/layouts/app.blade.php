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
{{ csrf_field() }}


@if (Auth::check())
<meta id="is-authenticated" content="true">
@endif

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>MyRel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script defer src="/js/myrel.js"></script>
    <link href="{{ asset('./dist/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('./css/posts.css') }}" rel="stylesheet">

</head>
<body class="theme-dark">
    <div class="sticky-top wrapper">





    
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">

                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                  
                    <a href="/posts" style="display:flex; align-items:center;"><img src="{{ asset('./img/logo.png') }}" style="height:42px;" alt="MyRel" class="navbar-brand-image"></a>
                </h1>
                <div class="navbar-nav flex-row order-md-last" style="">
                    @if (Auth::check())
                        
                    <div class="nav-item dropdown d-none d-md-flex me-3" id='notifications-bell'>
                        
                        <a class="nav-link px-0" href='{{'../notifications/mark-all-as-read/'.Auth::user()->id}}' role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="notification-bell">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                </svg>
                            </div>
                            @if (Auth::user()->unreadNotifications()->count()) 
                                <span class="badge badge-light">{{ Auth::user()->unreadNotifications()->count() }}</span>
                            @endif
                        </a>
                    
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach (Auth::user()->unreadNotifications() as $notification) 
                                <a class="dropdown-item" method='POST' href='#'>{{ $notification->text }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif 

                    @if (Auth::check())
                    @can('beAdmin', Auth::user())
                    <div>
                        <a class='btn btn-primary' href="/admin" style="margin-top:5px;">
                            <span>Admin</span>
                        </a>
                    </div>
                        
                    @endcan

                    <fieldset>
                    <div class="dropdown">
                      <button style="background:none; border:none;" class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img style="width:30px; height:30px;" class="avatar avatar-sm" alt="" src={{ Auth::user()->photo }}></img>

                                <div>{{Auth::user()->name}}</div>

                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                       
                      <a href="{{ url('/logout') }}" class="dropdown-item">Logout</a>
                      </div>
                    </div></fieldset>
                    @else
                    <div class="dropdown">
                      <button style="background:none; border:none;" class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <div> Options </div>

                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                       
                      <a href="{{ url('/login') }}" class="dropdown-item">Login</a>
                      <a href="{{ url('/register') }}" class="dropdown-item">Register</a>
                      </div>
                    </div>
                    @endif

                 
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <div class="container-xl">
                <!-- Page title -->
                <div class="page-header d-print-none">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body" id="content">
                <div class="container-xl" style="min-height: 80vh;">
                    <div class="container">





                    
                        <div class="row" id="main-row-content">
                            
                        <div class="col-3" id="col-A">




  <nav class="navbar navbar-expand-sm navbar-light" style="background:#1b2434;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    <nav>

    <a class="left-menu-item" href="/posts">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <polyline points="5 12 3 12 12 3 21 12 19 12"></polyline>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                        </svg>
                                        <span>Feed</span>
                                    </a>
                                    @if (Auth::check())
                                    <a class="left-menu-item" href={{"/notifications/".Auth::user()->id}}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                        </svg>
                                        <span>Notifications</span>
                                    </a>
                                    @endif
                                    <hr style="margin:5px 0px; width: 80%;">
                                    <a class="left-menu-item" href="/posts?type=friends">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-snowman" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 3a4 4 0 0 1 2.906 6.75a6 6 0 1 1 -5.81 0a4 4 0 0 1 2.904 -6.75z"></path>
                                            <path d="M17.5 11.5l2.5 -1.5"></path>
                                            <path d="M6.5 11.5l-2.5 -1.5"></path>
                                            <path d="M12 13h.01"></path>
                                            <path d="M12 16h.01"></path>
                                        </svg>
                                        <span>Friends</span>
                                    </a>
                                    <a class="left-menu-item" href="/posts?type=closefriends">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                        </svg>
                                        <span>Close Friends</span>
                                    </a>
                                    <a class="left-menu-item" href="/posts?type=family">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-seeding" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 10a6 6 0 0 0 -6 -6h-3v2a6 6 0 0 0 6 6h3"></path>
                                            <path d="M12 14a6 6 0 0 1 6 -6h3v1a6 6 0 0 1 -6 6h-3"></path>
                                            <line x1="12" y1="20" x2="12" y2="10"></line>
                                        </svg>
                                        <span>Family</span>
                                    </a>
                                    <hr style="margin:5px 0px; width: 80%;">
                                    @if (Auth::check())
                                    <a class="left-menu-item" href="/user/{{Auth::user()->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <circle cx="12" cy="10" r="3"></circle>
                                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                    @endif
                                
    </nav>
    </div>
  </nav>



                            </div>
                            <div class="col-6" id="feed"> @yield('content') </div>
                            <div class="col-3" id="col-B">
                                <section id="search"> <?php
                    echo Form::open(array('url' => '/search', 'method' => 'get'));
                    echo "Search:";
                    echo Form::text('search', null, array('placeholder' => 'Search'));
                    echo Form::close();
                  ?> </section>
                            </div>
                        </div>
                    </div>
                    <!-- Content here -->
                </div>
            </div>
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="/about-us" class="link-secondary">About Us</a></li>
                                <li class="list-inline-item"><a href="/contacts" class="link-secondary">Contacts</a></li>
                                <li class="list-inline-item"><a href="https://git.fe.up.pt/lbaw/lbaw2223/lbaw2212" target="_blank" class="link-secondary">Documentation</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"> Projeto LBAW 2022/2023 <a href="." class="link-secondary">G2212</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script type="text/javascript" src={{ asset('dist/js/tabler.min.js') }} defer>
        < /body> < /html>