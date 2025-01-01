<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AXMY - Management System</title>

    <!-- Custom fonts for this template-->
    {{-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> --}}
    <link href="{{ asset('assets/start_admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    {{-- bootstrap 4 --}}
    {{-- <link href="css/sb-admin-2.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('assets/start_admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('dashboard.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Navbar -->
                 @include('dashboard.navbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    {{-- @include('dashboard.body') --}}
                    @yield('content')
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
                @include('dashboard.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Download Loading Modal --}}
    <div class="modal fade" id="loading_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <!-- Menambahkan CSS di sini -->
                    <style>
                        .modal-content {
                            background: transparent; /* Hilangkan background */
                            border: none; /* Hilangkan border */
                            box-shadow: none; /* Hilangkan bayangan */
                        }
                        .modal-body {
                            background: transparent; /* Pastikan body juga transparan */
                            color: #fff; /* Ubah warna teks jika diperlukan */
                        }
                        .modal-body p {
                            color: #fff; /* Warna teks */
                        }
                    </style>
                    <!-- Gambar loading -->
                    <img src="{{ asset('assets/gambar/loading3.gif') }}" alt="Loading..." class="img-fluid">
                    <p class="mt-3">Memproses PO...</p>
                </div>
            </div>
        </div>
    </div>
    {{-- End Of Download Loading Modal --}}

    {{-- <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Bootstrap core JavaScript-->
    {{-- <script src="vendor/jquery/jquery.min.js"></script> --}}
    {{--  jQuery v3.6.0 --}}
    <script src="{{ asset('assets/start_admin/vendor/jquery/jquery.min.js') }}"></script>

    {{-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('assets/start_admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <!-- Core plugin JavaScript-->
    {{-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> --}}
    <script src="{{ asset('assets/start_admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    {{-- <script src="js/sb-admin-2.min.js"></script> --}}
    <script src="{{ asset('assets/start_admin/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    {{-- <script src="vendor/chart.js/Chart.min.js"></script> --}}
    <script src="{{ asset('assets/start_admin/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    {{-- <script src="js/demo/chart-area-demo.js"></script> --}}
    <script src="{{ asset('assets/start_admin/js/demo/chart-area-demo.js') }}"></script>
    {{-- <script src="js/demo/chart-pie-demo.js"></script> --}}
    <script src="{{ asset('assets/start_admin/js/demo/chart-pie-demo.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('footer')
</body>

</html>
{{-- Fungsi Sidebar Auto Close Ketika Klik Body --}}
<script>
    $(document).ready(function() {
        // Handle click inside the nav link to toggle collapse for any nav-link
        $('.nav-link').on('click', function(event) {
            event.preventDefault();  // Prevent default link behavior
            var target = $(this).data('target');

            // Collapse the target element
            $(target).collapse('toggle');

            // Toggle aria-expanded attribute
            var expanded = $(this).attr('aria-expanded') === 'true';
            $(this).attr('aria-expanded', !expanded);

            // Close other opened collapses
            $('.collapse').not(target).collapse('hide');
            $('.nav-link').not(this).attr('aria-expanded', 'false');
        });

        // Close the collapse if click outside the sidebar (on body)
        $(document).on('click', function(event) {
            var target = $(event.target);

            // Check if the click is outside of both the nav-link and collapse items
            if (!target.closest('.nav-item').length) {
                $('.collapse').collapse('hide');
                $('.nav-link').attr('aria-expanded', 'false');
            }
        });
    });
    </script>
