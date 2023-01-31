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
        <link rel="stylesheet" href="{{ asset('admin/vendor/select2/dist/css/select2.min.css') }}">
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
            .bmd-label-floating{
                font-weight: 700;
            }
            @font-face {
                font-family: 'Comic Sans MS';
                src: url("{{asset('7cc6719bd5f0310be3150ba33418e72e.woff')}}") format("woff");
            }
            .collapse-header{
                text-align:center;
            }

            #loader {
                position: absolute;
                left: 50%;
                top: 300px;
                z-index: 1;
                width: 120px;
                height: 120px;
                border: 16px solid #f3f3f3;
                margin: -76px 0 0 -76px;
                border-bottom: 16px solid #f6c23e;
                border-radius: 50%;
                border-top: 16px solid #3498db;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }

            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Add animation to "page content" */
            .animate-bottom {
                position: relative;
                -webkit-animation-name: animatebottom;
                -webkit-animation-duration: 1s;
                animation-name: animatebottom;
                animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
                from { bottom:-100px; opacity:0 } 
                to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom { 
                from{ bottom:-100px; opacity:0 } 
                to{ bottom:0; opacity:1 }
            }
        </style>
    </head>
    <body id="page-top" onload="loading()">
        <div id="loader"></div>
        <!-- Page Wrapper -->
        <div id="wrapper" style="opacity:0.5;">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}" style="height: auto;padding: 10px">
                    <div class="sidebar-brand-icon">
                        <!-- <i class="fas fa-laugh-wink"></i> -->
                        <img src="{{ asset('landing/assets/img/boedjang.png')}}" class="img img-responsive" style="height: 100px !important;margin:0px !important;">
                    </div>
                    <!-- <div class="sidebar-brand-text">Boedjang Group</div> -->
                </a>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    Karyawan
                </div>
                
                @if(Auth::user()->role !== 6)
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
                        <span>Presensi</span>
                    </a>
                </li>
                <li class="nav-item @if($page == 'slipgaji') active @endif">
                    <a class="nav-link" href="{{route('slipgaji.index')}}">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Slip Gaji</span>
                    </a>
                </li>
                

                <li class="nav-item @if($page == 'asset_list') active @endif">
                    <a class="nav-link" href="{{route('asset_list.index')}}">
                        <i class="fas fa-cubes"></i>
                        <span>Katalog Aset</span>
                    </a>
                </li>
                @endif

                <li class="nav-item @if($page == 'sop_list' || $page == 'norm_list') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        <i class="fas fa-scroll"></i>
                        <span>Prosedur Teknis</span>
                    </a>
                    <div id="collapseThree" class="collapse @if($page == 'sop_list' || $page == 'norm_list') show @endif" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                            <a class="collapse-item @if($page == 'sop_list') active @endif" href="{{route('sop_list.index')}}">SOP</a>
                            @if(Auth::user()->role !== 6)
                            <a class="collapse-item @if($page == 'norm_list') active @endif" href="{{route('norm_list.index')}}">Norm</a>
                            @endif
                        </div>
                    </div>
                </li>

                @if(Auth::user()->role !== 6)
                <li class="nav-item @if($page == 'bukusaku_list') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        <i class="fas fa-book-open"></i>
                        <span>Rule</span>
                    </a>
                    <div id="collapseFour" class="collapse @if($page == 'bukusaku_list') show @endif" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                            <a class="collapse-item @if($page == 'bukusaku_list') active @endif" href="{{route('bukusaku_list.index')}}">Buku Saku</a>
                            <a class="collapse-item @if($page == 'ketentuan_list') active @endif" href="{{route('ketentuan_list.index')}}">Ketentuan</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item @if($page == 'bukupedoman_list') active @endif">
                    <a class="nav-link" href="{{route('bukupedoman_list.index')}}">
                        <i class="fas fa-book"></i>
                        <span>Buku Pedoman</span>
                    </a>
                </li>
                
                @if(Auth::user()->role !== 6)
                <?php $karyawan = DB::table('u1127775_absensi.Absen')->where('NIP',Auth::user()->username)->first(); ?>
                @if($karyawan !== null)
                @if(Auth::user()->role !== 5 || $karyawan->Cabang == "HeadOffice")
                <li class="nav-item @if($page == 'bpm_list') active @endif">
                    <a class="nav-link" href="{{route('bpm_list.index')}}">
                        <i class="fas fa-project-diagram"></i>
                        <span>BPM</span>
                    </a>
                </li>
                @endif
                @endif
                @endif
                @endif

                

                {{-- <li class="nav-item @if($page == 'item_list') active @endif">
                    <a class="nav-link" href="{{route('item_list.index')}}">
                        <i class="fas fa-boxes"></i>
                        <span>Barang & Bahan</span>
                    </a>
                </li> --}}
                
                @if(Auth::user()->role !== 6)
                <li class="nav-item @if($page == 'formcuti') active @endif">
                    <a class="nav-link" href="{{route('formcuti.index')}}">
                        <i class="fas fa-clipboard"></i>
                        <span>Form Cuti</span>
                    </a>
                </li>
                
                
                <li class="nav-item @if($page == 'feedback') active @endif">
                    <a class="nav-link" href="{{route('feedback.index')}}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Feedback Atasan</span>
                    </a>
                </li>
                @endif

                
                <?php $user = DB::table('u1127775_boedjang.users')->where('id',Auth::user()->id)->first(); ?>
                
                {{-- @if(Auth::user()->role !== 6)
                @if($karyawan !== null)
                @if((Auth::user()->role == 1 || Auth::user()->role == 2 || $karyawan->Cabang == "HeadOffice") && $user->token !== null)
                <li class="nav-item @if($page == 'ticket' || $page == 'task_ticket' ) active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTicket" aria-expanded="true" aria-controls="collapseTicket">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Ticket Saya</span>
                    </a>
                    <div id="collapseTicket" class="collapse @if($page == 'ticket' || $page == 'task_ticket') show @endif" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item @if($page == 'ticket') active @endif" href="{{route('ticket.index')}}"> Daftar Ticket </a>
                            <a class="collapse-item @if($page == 'task_ticket') active @endif" href="{{route('task-ticket.index')}}">Tugas Ticket</a>
                        </div>
                    </div>
                </li>
                @endif
                @endif
                @endif --}}

                {{-- @if(($user->ticket_role === 1 || $user->ticket_role === 2) && $user->token !== null)
                <li class="nav-item @if($page == 'manajementicket' || $page == 'manajementicketdepart' ) active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManajemenTicket" aria-expanded="true" aria-controls="collapseManajemenTicket">
                        <i class="fas fa-book"></i>
                        <span>Ticket Department</span>
                    </a>
                    <div id="collapseManajemenTicket" class="collapse @if($page == 'manajementicket' || $page == 'manajementicketdepart') show @endif" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item @if($page == 'manajementicket') active @endif" href="{{route('manajementicket.index')}}"> Daftar Ticket </a>
                            <a class="collapse-item @if($page == 'manajementicketdepart') active @endif" href="{{route('manajementicketdepart.index')}}">Tugas Ticket </a>
                        </div>
                    </div>
                </li>
                @endif --}}
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Heading -->
                @if(Auth::user()->role == 4)
                <div class="sidebar-heading">
                    GA
                </div>
                <li class="nav-item @if($page == 'katalogasset' || $page == 'brand') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
                        <i class="fas fa-cube"></i>
                        <span>Master Aset</span>
                    </a>
                    <div id="collapseUtilities4" class="collapse @if($page == 'katalogasset' || $page == 'brand') show @endif" aria-labelledby="headingUtilities4" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            @if(Auth::user()->role == 1 || Auth::user()->role == 4)
                            <a class="collapse-item @if($page == 'brand') active @endif" href="{{route('brand.index')}}">Brand</a>
                            <a class="collapse-item @if($page == 'katalogasset') active @endif" href="{{route('asset.index')}}">Katalog Aset</a>
                            @endif
                        </div>
                    </div>
                </li>
                @elseif(Auth::user()->role == 1 || Auth::user()->role == 3)
                <div class="sidebar-heading">
                    Admin
                </div>
                <li class="nav-item @if($page == 'katalogasset' || $page == 'brand') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
                        <i class="fas fa-cube"></i>
                        <span>Master Aset</span>
                    </a>
                    <div id="collapseUtilities4" class="collapse @if($page == 'katalogasset' || $page == 'brand') show @endif" aria-labelledby="headingUtilities4" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            @if(Auth::user()->role == 1 || Auth::user()->role == 4)
                            <a class="collapse-item @if($page == 'brand') active @endif" href="{{route('brand.index')}}">Brand</a>
                            <a class="collapse-item @if($page == 'katalogasset') active @endif" href="{{route('asset.index')}}">Katalog Aset</a>
                            @endif
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Utilities Collapse Menu -->
                <li class="nav-item @if($page == 'blog' || $page == 'user' || $page == 'tag' || $page == 'bpm' || $page == 'bpmdivision' || $page == 'bukupedoman') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-cube"></i>
                        <span>Master Data</span>
                    </a>
                    <div id="collapseUtilities" class="collapse @if($page == 'blog' || $page == 'user' || $page == 'tag' || $page == 'bpm' || $page == 'bpmdivision' || $page == 'bukupedoman') show @endif" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">- Master Data -</h6>
                            @if(Auth::user()->role == 1)
                            <a class="collapse-item @if($page == 'user') active @endif" href="{{route('user.index')}}">Pengguna</a>
                            <a class="collapse-item @if($page == 'tag') active @endif" href="{{route('tag.index')}}">Tag Blog</a>
                            <a class="collapse-item @if($page == 'blog') active @endif" href="{{route('blog.index')}}">Blog</a>
                            @endif
                            <a class="collapse-item @if($page == 'bpmdivision') active @endif" href="{{route('bpmdivision.index')}}">BPM Divisi </a>
                            <a class="collapse-item @if($page == 'bpm') active @endif" href="{{route('bpm.index')}}">BPM</a>
                            <a class="collapse-item @if($page == 'bukupedoman') active @endif" href="{{route('bukupedoman.index')}}">Buku Pedoman</a>

                            {{-- <a class="collapse-item @if($page == 'item') active @endif" href="{{route('item.index')}}">Barang & Bahan</a> --}}
                        </div>
                    </div>
                </li>
                <li class="nav-item @if($page == 'sop' || $page == 'category'  || $page == 'type' || $page == 'jabatan' || $page == 'history_sop' || $page == 'norm' || $page == 'normcategory') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
                        <i class="fas fa-cube"></i>
                        <span>Master Prosedur</span>
                    </a>
                    <div id="collapseUtilities2" class="collapse @if($page == 'sop' || $page == 'category'  || $page == 'type' || $page == 'jabatan' || $page == 'history_sop' || $page == 'norm' || $page == 'normcategory') show @endif" aria-labelledby="headingUtilities2" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            @if(Auth::user()->role == 1)
                            <h6 class="collapse-header">- Master SOP -</h6>
                            <a class="collapse-item @if($page == 'history_sop') active @endif" href="{{route('sop.history')}}">History Pembaca SOP</a>
                            <a class="collapse-item @if($page == 'category') active @endif" href="{{route('category.index')}}">Kategori SOP</a>
                            <a class="collapse-item @if($page == 'type') active @endif" href="{{route('type.index')}}">Jenis SOP</a>
                            <a class="collapse-item @if($page == 'jabatan') active @endif" href="{{route('jabatan.index')}}">Jabatan SOP</a>
                            <a class="collapse-item @if($page == 'sop') active @endif" href="{{route('sop.index')}}">SOP</a>
                            <h6 class="collapse-header">- Master Norm -</h6>
                            <a class="collapse-item @if($page == 'norm') active @endif" href="{{route('norm.index')}}">Norm</a>
                            <a class="collapse-item @if($page == 'normcategory') active @endif" href="{{route('normcategory.index')}}">Kategori Norm</a>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="nav-item @if($page == 'bukupedoman' || $page == 'subbab' || $page == 'bab' || $page == 'ketentuan') active @endif">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities3" aria-expanded="true" aria-controls="collapseUtilities3">
                        <i class="fas fa-cube"></i>
                        <span>Master Rule</span>
                    </a>
                    <div id="collapseUtilities3" class="collapse @if($page == 'bukupedoman' || $page == 'subbab' || $page == 'bab' || $page == 'ketentuan') show @endif" aria-labelledby="headingUtilities3" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">- Master Ketentuan -</h6>
                            @if(Auth::user()->role == 1)
                            <a class="collapse-item @if($page == 'ketentuan') active @endif" href="{{route('ketentuan.index')}}">Ketentuan</a>
                            <h6 class="collapse-header">- Master Buku Saku -</h6>
                            <a class="collapse-item @if($page == 'bab') active @endif" href="{{route('bab.index')}}">Bab Buku Saku</a>
                            <a class="collapse-item @if($page == 'subbab') active @endif" href="{{route('subbab.index')}}">Sub Bab Buku Saku</a>
                            @endif
                        </div>
                    </div>
                </li>

                @if(Auth::user()->role == 1)
                <li class="nav-item @if($page == 'feedbacklaporan') active @endif">
                    <a class="nav-link" href="{{route('feedbacklaporan.index')}}">
                        <i class="fas fa-list-ul"></i>
                        <span>Laporan Feedback</span>
                    </a>
                </li>
                
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
                @endif
                
                <br><br><br><br>
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
                        <?php
                            $sop_notif = DB::table('sop_notification')->leftJoin('sop', 'sop.id','sop_notification.sop')->select('sop_notification.*', 'sop.slug')->orderBy('date','DESC')->limit(5)->get();
                        ?>
                        <ul class="navbar-nav ml-auto">
                            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                            
                            @if(count($sop_notif) > 0)
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter">+1</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header">
                                        SOP Notifikasi
                                    </h6>
                                    @foreach($sop_notif as $notif)
                                    <a class="dropdown-item d-flex align-items-center" href="{{url('sop-list/'.$notif->slug)}}">
                                        <div class="mr-3">
                                            @if($notif->type == 1)
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-file-alt text-white"></i>
                                            </div>
                                            @elseif($notif->type == 2)
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-pencil-alt text-white"></i>
                                            </div>
                                            @elseif($notif->type == 3)
                                            <div class="icon-circle bg-danger">
                                                <i class="fas fa-trash text-white"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <?php $datesop = date_create($notif->date); ?>
                                            <div class="small text-gray-500">{{date_format($datesop, 'D, d-m-Y H:i:s')}}</div>
                                            <span class="font-weight-bold">{{$notif->keterangan}}</span>
                                        </div>
                                    </a>
                                    @endforeach
                                    <a class="dropdown-item text-center small text-gray-500" href="{{route('sop.notification')}}">Lihat Semua</a>
                                </div>
                            </li>
                            @endif
                            
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{Auth::user()->name}}</span>
                                    @if(Auth::user()->gambar == null)
                                    <img style="border: none;zoom:150%;object-fit: cover;" class="img-profile rounded-circle shadow" src="{{ asset('admin/img/default.png')}}">
                                    @else
                                    <img style="border: none;zoom:150%;object-fit: cover;" class="img-profile rounded-circle shadow" src="{{ asset('images/profile/'.Auth::user()->gambar)}}">
                                    @endif
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
        <script src="{{ asset('admin/js/views.min.js') }}"></script>


        <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('admin/vendor/datatables/dataTables.fixedHeader.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/jszip.min.js') }}"></script>
        {{-- <script src="{{ asset('admin/vendor/datatables/pdfmake.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('admin/vendor/datatables/vfs_fonts.js') }}"></script> --}}
        <script src="{{ asset('admin/vendor/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/datatables/buttons.colVis.min.js') }}"></script>

        {{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script> --}}
        <script src="{{ asset('admin/vendor/select2/dist/js/select2.min.js')}}"></script>
        <!-- include summernote css/js -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> -->
        <script type="text/javascript">
            // $( ".datepicker" ).datepicker({
            //         dateFormat: 'dd MM yy',
            //         changeMonth: true,
            //         changeYear: true,
            //         widgetPositioning:{
            //         horizontal: 'auto',
            //         vertical: 'bottom'
            //     }
            // });

            $('.select2').select2();

            var views = [],
                triggers = document.querySelectorAll('.image-trigger');
            [].forEach.call(triggers, function(element, index) {
                views[index] = new Views(element, {
                    defaultTheme: true,
                    prefix: 'light',
                    loader: 'Loading...',
                    anywhereToClose: true,
                    openAnimationDuration: 0,
                    closeAnimationDuration: 0
                });
            });

            $(document).ready(function () {
                window.scrollTo(0, 0);
                disableScroll();
                if ($(window).width() < 480){
                    $("body").addClass("sidebar-toggled");
                    $(".sidebar").addClass("toggled");
                    $('.sidebar .collapse').collapse('hide');
                }
            });

            function loading() {
                myVar = setTimeout(showPage, 500);
            }

            function showPage() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("wrapper").style.opacity = "1";
                enableScroll();
            }

            // left: 37, up: 38, right: 39, down: 40,
            // spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
            var keys = {37: 1, 38: 1, 39: 1, 40: 1};

            function preventDefault(e) {
                e.preventDefault();
            }

            function preventDefaultForScrollKeys(e) {
            if (keys[e.keyCode]) {
                preventDefault(e);
                return false;
            }
            }

            // modern Chrome requires { passive: false } when adding event
            var supportsPassive = false;
            try {
            window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
                get: function () { supportsPassive = true; } 
            }));
            } catch(e) {}

            var wheelOpt = supportsPassive ? { passive: false } : false;
            var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

            // call this to Disable
            function disableScroll() {
            window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
            window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
            window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
            window.addEventListener('keydown', preventDefaultForScrollKeys, false);
            }

            // call this to Enable
            function enableScroll() {
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
            window.removeEventListener(wheelEvent, preventDefault, wheelOpt); 
            window.removeEventListener('touchmove', preventDefault, wheelOpt);
            window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
            }
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