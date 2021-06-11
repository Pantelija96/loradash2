<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lora Iot Billing Dashboard">
    <meta name="author" content="TERI ENG">

    <title>@yield('title')</title>


    <link href="{{ asset('/') }}vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <link href="{{ asset('/') }}css/lora.css" rel="stylesheet">

    @yield('css')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="container">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <div class="sidebar-brand d-flex align-items-center justify-content-center bg-white">
                <img class="img-fluid" src="{{ asset('/') }}images/LOGO-EMAIL.jpg" alt="">
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <div class="sidebar-heading">
                Usluge
            </div>

            <li class="nav-item">
                <a class="nav-link" href="dodavanje-usluge-korisniku.html">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Dodavanje usluge korisniku</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <div class="sidebar-heading">
                Podešavanja
            </div>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/allusers') }}">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Podrška - nalozi</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{ url('/adduser') }}">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    <span>Dodavanje naloga podrške</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/addsensor') }}">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    <span>Dodavanje senzora</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/allsensors') }}">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Svi senzori</span></a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">


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


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><strong>{{ session()->get('korisnik')->naziv }}</strong> | {{ session()->get('korisnik')->ime.' '.session()->get('korisnik')->prezime }}</span>
                                <i class="fas fa-user-circle fa-2x"></i>
                            </a>
                            <!-- Dropdown - logout -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Izlaz
                                </a>
                                <a class="dropdown-item" href="{{ url('/changepassword') }}">
                                    <i class="fas fa-tools mr-2 text-gray-400"></i>
                                    Promena lozinke
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Teri Engineering 2021</span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Izlaz?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Kliknite na izlaz kako bi napustili portal.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                    <a class="btn btn-danger" href="{{ url('/logout') }}">Izlaz</a>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('/') }}vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('/') }}vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="{{ asset('/') }}vendor/datatables/jquery.dataTables.js"></script>
    <script src="{{ asset('/') }}vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="{{ asset('/') }}js/demo/datatables-demo.js"></script>
    <script src="{{ asset('/') }}js/script.js"></script>


</body>

</html>
