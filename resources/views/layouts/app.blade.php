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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link href="{{ asset('./dist/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('./css/posts.css') }}" rel="stylesheet">
</head>

<body class="theme-dark">
    <div class="wrapper">
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="/posts" style="display:flex; align-items:center;">LBAW</a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown d-none d-md-flex me-3">
                        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                            </svg>
                            <span class="badge bg-red"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-card">
                            <div class="card">
                                <div class="card-body"> Test notifications </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url({{ Auth::user()->photo }})"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{Auth::user()->name}}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Logout</a> @if (Auth::check()) <a class="button" href="{{ url('/logout') }}"> Logout </a> <a href="/user/{{ Auth::user()->id }}">{{ Auth::user()->name }}</a> @endif
                        </div>
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
                        <div class="row">
                            <div class="col-3">
                                <!--Create a navbar with feed, friends-->
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
                                    <hr style="margin:5px 0px; width: 80%;">
                                    <a class="left-menu-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                        </svg>
                                        <span>Close Friends</span>
                                    </a>
                                    <a class="left-menu-item">
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
                                    <a class="left-menu-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-seeding" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 10a6 6 0 0 0 -6 -6h-3v2a6 6 0 0 0 6 6h3"></path>
                                            <path d="M12 14a6 6 0 0 1 6 -6h3v1a6 6 0 0 1 -6 6h-3"></path>
                                            <line x1="12" y1="20" x2="12" y2="10"></line>
                                        </svg>
                                        <span>Family</span>
                                    </a>
                                    <hr style="margin:5px 0px; width: 80%;">
                                    <a class="left-menu-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <circle cx="12" cy="10" r="3"></circle>
                                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                    <button class="post-btn btn" data-toggle="modal" data-target="#exampleModal"> POST </button>
                                </nav>
                            </div>
                            <div class="col-6" id="feed"> @yield('content') </div>
                            <div class="col-3">
                                <section id="search"> <?php
                    echo Form::open(array('url' => '/search', 'method' => 'get'));
                    echo "Search:";
                    echo Form::text('search');
                    echo Form::button('Search', array('type' => 'submit'));
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
                                <li class="list-inline-item"><a href="https://git.fe.up.pt/lbaw/lbaw2223/lbaw2212" target="_blank" class="link-secondary">Documentation</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"> Projeto LBAW 2022/2023 <a href="." class="link-secondary">G2212</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="exampleModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Post</h5>
                    <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section id="new_post">
                        <article style="display:flex;">
                            <header>
                                <h2 onclick="location.href='/user/{{Auth::user()->id}}';" style="cursor:pointer;"><img src={{ Auth::user()->photo }}</a></h2>
                            </header>
                            <div style="margin-left:10px; margin-top:10px;"> <?php
            echo Form::open(array('url' => 'api/posts', 'method' => 'put'));
            echo Form::textarea('text', 'Share your thoughts with us...');
            echo "Visibility";
            echo Form::select('visibility', array('Close Friends' => 'Close Friends', 'Friends' => 'Friends', 'Family' => 'Family', 'Strangers' => 'Strangers'));
            echo Form::button('Create new Post', array('type' => 'submit'));
            echo Form::close();
          ?> </div>
                        </article>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script type="text/javascript" src={{ asset('dist/js/tabler.min.js') }} defer>
        < /body> < /html>