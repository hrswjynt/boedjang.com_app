<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{DB::table('content')->where('type','title')->first()->content}}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('landing/assets/img/boedjang.png')}}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('landing/css/styles.css')}}" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <!-- <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="assets/img/navbar-logo.svg" style="" alt="" /></a> -->
                <a class="navbar-brand js-scroll-trigger" href="#page-top">{{DB::table('content')->where('type','header_menu')->first()->content}}</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ml-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive" @guest style="zoom:90%" @else style="zoom:80%" @endguest>
                    <ul class="navbar-nav text-uppercase ml-auto">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">About Us</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#brand">Our Brands</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#blog">Blog</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">Contact</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="https://bit.ly/boedjangkalbar">Join Our Team</a></li>
                        @guest
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{route('login')}}">Login</a></li>
                        @else
                        <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">{!! DB::table('content')->where('type','header')->first()->content !!}</div>
                <div class="masthead-heading text-uppercase">{!! DB::table('content')->where('type','subheader')->first()->content !!}</div>
                <!-- <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">Tell Me More</a> -->
            </div>
        </header>

        @yield('content')

        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-left">{!! DB::table('content')->where('type','footer')->first()->content !!}</div>
                    <div class="col-lg-4 my-3 my-lg-0">
                        
                    </div>
                    <div class="col-lg-4 text-lg-right">
                        <a target="__blank" class="btn btn-dark btn-social mx-2" href="{{ DB::table('social_media')->where('type','instagram')->first()->url }}"><i class="fab fa-instagram"></i></a>
                        <a target="__blank" class="btn btn-dark btn-social mx-2" href="{{ DB::table('social_media')->where('type','facebook')->first()->url }}"><i class="fab fa-facebook-f"></i></a>
                        <a target="__blank" class="btn btn-dark btn-social mx-2" href="{{ DB::table('social_media')->where('type','tiktok')->first()->url }}"><i class="fab fa-tiktok"></i></a>
                        
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="{{ asset('landing/assets/mail/jqBootstrapValidation.js')}}"></script>
        <script src="{{ asset('landing/assets/mail/contact_me.js')}}"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('landing/js/scripts.js')}}"></script>
    </body>
</html>
