<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <img
          src="{{ asset('assets/img/kaiadmin/smknsembilan.png') }}"
          alt="navbar brand"
          class="navbar-brand"
          height="40"
        />
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
    <!-- End Logo Header -->
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard.index') }}">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Stock Barang -->
        <li class="nav-item {{ request()->is('stok*') ? 'active' : '' }}">
          <a href="{{ route('stok.index') }}">
            <i class="fas fa-th-list"></i>
            <p>Stok Barang</p>
          </a>
        </li>

        <!-- Transaksi -->
        <li class="nav-item {{ request()->is('transaksi') ? 'active' : '' }}">
          <a href="{{ route('transaksi.index')}}">
            <i class="fas fa-credit-card"></i>
            <p>Transaksi</p>
          </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item {{ request()->is('laporan*') ? 'active' : '' }}">
        <a href="{{ route('laporan.index') }}">
        <i class="fas fa-pen-square"></i>
        <p>Laporan</p>
        </a>
        </li>
      </ul>
    </div>
  </div>
</div>
