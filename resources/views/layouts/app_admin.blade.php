<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/x-icon" href="{{ asset('landing/assets/img/boedjang.png')}}" />
        <title>Boedjang Group Indonesia - Dashboard</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Custom fonts for this template-->
        <link href="{{asset('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{asset('admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
        <script src="https://cdn.ckeditor.com/4.15.1/standard-all/ckeditor.js"></script>
        <link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
        <link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('admin/vendor/datatables/buttons.dataTables.min.css')}}" rel="stylesheet">
        <link href="{{asset('admin/vendor/datatables/fixedHeader.dataTables.min.css')}}" rel="stylesheet">
        {{-- datetimepicker --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
        <style type="text/css">
            .red{
                color: red;
            }
            .swal-modal .swal-text {
                text-align: center;
            }
            tr,td,th{
                text-align: center
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}" style="height: auto;padding: 10px">
                    <div class="sidebar-brand-icon">
                        <!-- <i class="fas fa-laugh-wink"></i> -->
                        <img src="{{ asset('landing/assets/img/boedjang.png')}}" class="img img-responsive" style="height: 100px">
                    </div>
                    <!-- <div class="sidebar-brand-text">Boedjang Group</div> -->
                </a>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Karyawan
                </div>
                <!-- Nav Item - Dashboard -->
                <li class="nav-item @if($page == 'dashboard') active @endif">
                    <a class="nav-link" href="{{route('dashboard')}}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item @if($page == 'datadiri') active @endif">
                    <a class="nav-link" href="{{route('data-diri.index')}}">
                        <i class="fas fa-id-card-alt"></i>
                        <span>Data Diri</span>
                    </a>
                </li>
                <li class="nav-item @if($page == 'absensi') active @endif">
                    <a class="nav-link" href="{{route('absensi.index')}}">
                        <i class="fas fa-address-book"></i>
                        <span>Absensi</span>
                    </a>
                </li>
                <li class="nav-item @if($page == 'slipgaji') active @endif">
                    <a class="nav-link" href="{{route('slipgaji.index')}}">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Slip Gaji</span>
                    </a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Heading -->
                @if(Auth::user()->role == 1)
                <div class="sidebar-heading">
                    Admin
                </div>
                <!-- Nav Item - Utilities Collapse Menu -->
                <li class="nav-item @if($page == 'blog' || $page == 'user') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-cube"></i>
                        <span>Master</span>
                    </a>
                    <div id="collapseUtilities" class="collapse @if($page == 'blog' || $page == 'user') show @endif" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Master Data Admin</h6>
                            <a class="collapse-item @if($page == 'user') active @endif" href="{{route('user.index')}}">Pengguna</a>
                            <a class="collapse-item @if($page == 'blog') active @endif" href="{{route('blog.index')}}">Blog</a>
                        </div>
                    </div>
                </li>
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item @if($page == 'content' || $page == 'social_media') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>Landingpage</span>
                    </a>
                    <div id="collapseTwo" class="collapse @if($page == 'content' || $page == 'social_media') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                            <a class="collapse-item @if($page == 'content') active @endif" href="{{route('content.index')}}">Content</a>
                            <a class="collapse-item @if($page == 'social_media') active @endif" href="{{route('socialmedia.index')}}">Social Media</a>
                        </div>
                    </div>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                @endif
                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
            </ul>
            <!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                        </button>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{Auth::user()->name}}</span>
                                    <img class="img-profile rounded-circle" src="{{ asset('landing/assets/img/boedjang.png')}}">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{route('user.edit',Auth::user()->id)}}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->

                    <div id="content-admin">
                        @yield('content')
                    </div>
                    
                </div>
                <!-- End of Main Content -->
                <!-- Footer -->
                <footer class="sticky-footer bg-white" style="margin-top: 120px;">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>{!! DB::table('content')->where('type','footer')->first()->content !!}</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <!-- Bootstrap core JavaScript-->
        <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>
        <script src="{{ asset('admin/vendor/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('admin/js/formatUang.js') }}"></script>


        <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>

        <script type="text/javascript">
            $( ".datepicker" ).datepicker({
                    dateFormat: 'dd MM yy',
                    changeMonth: true,
                    changeYear: true,
                    widgetPositioning:{
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        </script>
        <!-- Page level plugins -->
        <!-- <script src="{{asset('admin/vendor/chart.js/Chart.min.js')}}"></script> -->
        <!-- Page level custom scripts -->
        <!-- <script src="{{asset('admin/js/demo/chart-area-demo.js')}}"></script> -->
        <!-- <script src="{{asset('admin/js/demo/chart-pie-demo.js')}}"></script> -->
        {{-- Other Script --}}
        @stack('other-script')
        {{-- End Other Script --}}
    </body>
</html>