<aside class="main-sidebar sidebar-dark-orange elevation-4">
    <a href="/" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Menu Home -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
<x-nav-item title="Transaksi" icon="fas fa-cash-register" 
    :routes="['transaksi.index', 'transaksi.create', 'transaksi.show']" />
                <!-- Menu Produk -->
                <x-nav-item title="Produk" icon="fas fa-box-open" :routes="['produk.index', 'produk.create', 'produk.edit']" />

                <!-- Menu Stok -->
                <x-nav-item title="Stok" icon="fas fa-pallet" :routes="['stok.index', 'stok.create']" />

                <!-- Menu Kategori -->
                <x-nav-item title="Kategori" icon="fas fa-list" :routes="['kategori.index','kategori.create','kategori.edit']" />

                <!-- Menu Pelanggan -->
                <li class="nav-item">
                    <a href="{{ route('pelanggan.index') }}" class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pelanggan</p>
                    </a>
                </li>

                <!-- Menu User -->
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>User</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>