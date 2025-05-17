 <!-- Sidebar -->
 @php
    $user = Auth::user();
@endphp
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion main-sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
            <img src="{{ asset('assets/gambar/xait.svg') }}" alt="Heart" style="width: 50px; height: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">
            AXMY
            <div class="sidebar-brand-subtext" style="font-size: 8px;">Axcelerate Management System</div>
        </div>
    </a>


      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item d-none d-md-block">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-shopping-cart"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Transaksi:</h6>
                @if ($user->roles !== 'customer')
                <a class="collapse-item" id="main_transaksi_link" href="#">Input PO</a>
                <a class="collapse-item" id="edit_transaksi_link" href="#">edit PO</a>
                @endif
                <a class="collapse-item" id="approved_transaksi_link" href="#">PO Disetujui</a>
                <a class="collapse-item" id="success_transaksi_link" href="#">PO Sukses</a>
            </div>
        </div>
    </li>

    <li class="nav-item d-block d-md-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="true" aria-controls="collapseFour">
            <i class="fa fa-mobile"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Transaksi:</h6>
                @if ($user->roles !== 'customer')
                <a class="collapse-item" id="main_transaksi_link_mobile" href="#">Input PO</a>
                <a class="collapse-item" id="edit_transaksi_link_mobile" href="#">edit PO</a>
                @endif
                <a class="collapse-item" id="approved_transaksi_link_mobile" href="#">PO Disetujui</a>
                <a class="collapse-item" id="success_transaksi_link_mobile" href="#">PO Sukses</a>
            </div>
        </div>
    </li>

    @php
        $user = Auth::user();
        $allowedRoles = ['programmer', 'admin', 'staff'];
    @endphp
    @if (in_array($user->roles, $allowedRoles))
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="true" aria-controls="collapseThree">
            <i class="fas fa-tags"></i>
            <span>List Harga</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">List Harga:</h6>
                <a class="collapse-item" id="list_harga_link" href="#">List Harga</a>
            </div>
        </div>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
            aria-expanded="true" aria-controls="collapseFive">
            <i class="fa fa-qrcode"></i>
            <span>Qris</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Qris:</h6>
                <a class="collapse-item" id="qris_link" href="#">Qr Code</a>
            </div>
        </div>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThee"
            aria-expanded="true" aria-controls="collapseThee">
            <i class="fas fa-fw fa-cog"></i>
            <span>Approval</span>
        </a>
        <div id="collapseThee" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Transaksi:</h6>
                <a class="collapse-item" id="main_transaksi_link" href="#">Input PO</a>
                <a class="collapse-item" id="edit_transaksi_link" href="#">edit PO</a>
            </div>
        </div>
    </li> --}}

     <!-- Nav Item - Utilities Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li> --}}

</ul>
<!-- End of Sidebar -->
