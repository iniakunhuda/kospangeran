<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Kos Pangeran')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin')}}/images/favicon.png">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="{{asset('admin')}}/css/style.css" rel="stylesheet">
    @stack('styles')
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{route('home')}}" class="brand-logo">
                <img class="brand-title" style="max-width: 100px" src="{{ asset('icon/logo_kos.png') }}" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            {{-- <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div> --}}
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{asset('admin')}}/app-profile.html" class="dropdown-item">
                                        <i class="icon-user"></i>
                                        <span class="ml-2">Profile </span>
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Logout</span>
                                    </a>


                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="{{ route('home') }}" aria-expanded="false"><i class="icon icon-home-minimal"></i><span class="nav-text">Dashboard</span></a></li>

                    <li class="nav-label">Manajemen Kos</li>
                    <li><a href="{{ route('area.index') }}" aria-expanded="false"><i class="icon icon-app-store"></i><span class="nav-text">Area Bangunan</span></a></li>

                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-app-store"></i><span class="nav-text">Manajemen Kamar</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('tipe_kamar.index') }}">Tipe Kamar</a></li>
                            <li><a href="{{ route('kamar.index') }}">Semua Kamar</a></li>
                            <li><a href="{{ route('riwayat_kamar.index') }}">Riwayat Kamar</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-app-store"></i><span class="nav-text">Penyewa</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('penyewa.create') }}">Tambah Penyewa Baru</a></li>
                            <li><a href="{{ route('penyewa.index')  }}">Semua Penyewa</a></li>
                        </ul>
                    </li>


                    <li class="nav-label">Pembayaran</li>

                    <li><a href="{{ route('tagihan.belumbayar.index') }}" aria-expanded="false"><i class="icon icon-house-pricing"></i><span class="nav-text">Belum Bayar</span></a></li>
                    <li><a href="{{ route('tagihan.bayar.create') }}" aria-expanded="false"><i class="ti-money"></i><span class="nav-text">Catat Pembayaran Baru</span></a></li>
                    <li><a href="{{ route('tagihan.riwayat.index') }}" aria-expanded="false"><i class="ti-bar-chart"></i><span class="nav-text">Riwayat Pembayaran</span></a></li>

                    <li class="nav-label">Data Master</li>

                    <li><a href="{{ route('master.rekening.index') }}" aria-expanded="false"><i class="ti-wallet"></i><span class="nav-text">Rekening</span></a></li>

                </ul>
            </div>


        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        @yield('content')
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Huda</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('admin')}}/vendor/global/global.min.js"></script>
    <script src="{{asset('admin')}}/js/quixnav-init.js"></script>
    <script src="{{asset('admin')}}/js/custom.min.js"></script>


    <!-- Datatable -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script>
        function previewUploadedImage(input, target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+target).attr('src', e.target.result);
                    $('#'+target).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('.image-upload').change(function(){
            previewUploadedImage(this, $(this).attr('target-preview'));
        });
    </script>

    @stack('scripts')

</body>
</html>
