<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Boedjang Group Indonesia - Login</title>
        <!-- Custom fonts for this template-->
        <link href="{{asset('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{asset('admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="{{ asset('landing/assets/img/boedjang.png')}}" />
        <style type="text/css">
            @media only screen and (max-width: 600px) {
              #desktop {
                display: none
              }
            }
        </style>
    </head>
    <body class="bg-gradient-primary">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-4">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row p-3 bg-gray-800">
                                <div class="text-center col-lg-12">
                                    <h3 style="color: white">LOGIN</h3>
                                    <h5 style="color: white">Boedjang Group Indonesia</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-3">
                                        <div class="text-center">
                                            <!-- <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1> -->
                                            <img class="img img-responsive" src="{{asset('landing/assets/img/boedjang.png')}}" height="150px" alt="" style="margin-bottom: 20px" />
                                        </div>
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group input-group">
                                                <div class="input-group-prepend">
                                                  <div class="btn btn-primary"><i class="fa fa-user"></i></div>
                                                </div>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror form-control-user" id="exampleInputEmail" name="username" value="{{ old('username') }}" aria-describedby="NIP" placeholder="NIP" required autocomplete="username" autofocus>
                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group input-group">
                                                <div class="input-group-prepend">
                                                  <div class="btn btn-primary"><i class="fas fa-lock"></i></div>
                                                </div>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror form-control-user" id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="current-password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block"><i class="fas fa-sign-in-alt"></i>
                                                Login
                                            </button>
                                            <!-- <hr> -->
                                        </form>
                                        <!-- <hr> -->

                                        
                                        <!-- <div class="text-center">
                                            <a class="small" href="register.html">Create an Account!</a>
                                        </div> -->
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row bg-gray-800" style="margin: 0 auto;">
                                <div class="text-center col-lg-6 p-3" style="margin-top: 20px;color: white">
                                    {!! DB::table('content')->where('type','footer')->first()->content !!}
                                </div>
                                <div class="text-center col-lg-6 p-3" id="desktop">
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/bebekboedjang.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/bts.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/muaraikansambal.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/bebekbejo.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/bale.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                    <img class="img img-responsive" src="{{asset('landing/assets/img/brand/sotosemar.webp')}}" style="height: 50px;margin-left: 10px" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- <footer class="sticky-footer bg-white" style="bottom: 0;width: 100%;position: absolute;">
            <div class="container my-auto">
                <div class="copyright my-auto">
                    <span>Copyright © Boedjang Group Indonesia 2020</span>
                </div>
            </div>
        </footer> -->
        <!-- Bootstrap core JavaScript-->
        <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>
    </body>
</html>