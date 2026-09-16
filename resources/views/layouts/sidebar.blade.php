<div class="sidebar">

    <!-- ==========================
            LOGO
    =========================== -->

    <div class="sidebar-header">

        <div class="brand">

            <div class="brand-icon">
                <i class="fas fa-bus"></i>
            </div>

            <div class="brand-text">
                <h4>UD SENTOSA</h4>
                <small>
                    Sistem Pengelolaan<br>
                    Kendaraan Operasional
                </small>
            </div>

        </div>

    </div>


    <!-- ==========================
            MENU
    =========================== -->

    <div class="sidebar-menu">

        <!-- MENU UTAMA -->

        <div class="menu-title">
            MENU UTAMA
        </div>

        <a href="{{ url('/dashboard') }}"
            class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">

            <i class="fas fa-house"></i>

            <span>Dashboard</span>

        </a>


        <!-- MASTER DATA -->

        <div class="menu-title">
            MASTER DATA
        </div>

        <a href="{{ url('/kendaraan') }}"
            class="menu-item {{ request()->is('kendaraan*') ? 'active' : '' }}">

            <i class="fas fa-car"></i>

            <span>Kendaraan</span>

        </a>

        <a href="{{ url('/sopir') }}"
            class="menu-item {{ request()->is('sopir*') ? 'active' : '' }}">

            <i class="fas fa-user"></i>

            <span>Sopir</span>

        </a>

        <a href="{{ url('/pengguna') }}"
            class="menu-item {{ request()->is('pengguna*') ? 'active' : '' }}">

            <i class="fas fa-users"></i>

            <span>Pengguna</span>

        </a>

        <a href="{{ url('/pemegang') }}"
            class="menu-item {{ request()->is('pemegang*') ? 'active' : '' }}">

            <i class="fas fa-address-card"></i>

            <span>Pemegang</span>

        </a>


        <!-- TRANSAKSI -->

        <div class="menu-title">
            TRANSAKSI
        </div>

        <a href="{{ url('/data-pemakai') }}"
            class="menu-item {{ request()->is('data-pemakai*') || request()->is('peminjaman-kendaraan*') || request()->is('pengembalian*') ? 'active' : '' }}">

            <i class="fas fa-calendar-plus"></i>

            <span>Data Pemakai</span>

        </a>

        <a href="{{ url('/servis') }}"
            class="menu-item {{ request()->is('servis*') ? 'active' : '' }}">

            <i class="fas fa-screwdriver-wrench"></i>

            <span>Servis Kendaraan</span>

        </a>

        <a href="{{ url('/riwayat-servis') }}"
            class="menu-item {{ request()->is('riwayat-servis*') ? 'active' : '' }}">

            <i class="fas fa-clock-rotate-left"></i>

            <span>Riwayat Servis</span>

        </a>


        <!-- KLASIFIKASI -->

        <div class="menu-title">
            KLASIFIKASI
        </div>

        <a href="{{ url('/prediksi-naive-bayes') }}"
            class="menu-item {{ request()->is('prediksi-naive-bayes*') ? 'active' : '' }}">

            <i class="fas fa-brain"></i>

            <span>Prediksi Naive Bayes</span>

        </a>

        <a href="{{ url('/prediksi-decision-tree') }}"
            class="menu-item {{ request()->is('prediksi-decision-tree*') ? 'active' : '' }}">

            <i class="fas fa-code-branch"></i>

            <span>Prediksi Decision Tree</span>

        </a>

        <a href="{{ url('/perbandingan-hasil') }}"
            class="menu-item {{ request()->is('perbandingan-hasil*') ? 'active' : '' }}">

            <i class="fas fa-chart-line"></i>

            <span>Perbandingan Hasil</span>

        </a>


        <!-- LAPORAN -->

        <div class="menu-title">
            LAPORAN
        </div>

        <a href="{{ url('/laporan-kendaraan') }}"
            class="menu-item {{ request()->is('laporan-kendaraan*') ? 'active' : '' }}">

            <i class="fas fa-file"></i>

            <span>Laporan Kendaraan</span>

        </a>

        <a href="{{ url('/laporan-servis') }}"
            class="menu-item {{ request()->is('laporan-servis*') ? 'active' : '' }}">

            <i class="fas fa-file-lines"></i>

            <span>Laporan Servis</span>

        </a>

        <a href="{{ url('/laporan-prediksi') }}"
            class="menu-item {{ request()->is('laporan-prediksi*') ? 'active' : '' }}">

            <i class="fas fa-file-export"></i>

            <span>Laporan Prediksi</span>

        </a>


        <!-- PENGATURAN -->

        <div class="menu-title">
            PENGATURAN
        </div>

        <a href="{{ url('/pengaturan') }}"
            class="menu-item {{ request()->is('pengaturan*') ? 'active' : '' }}">

            <i class="fas fa-gear"></i>

            <span>Pengaturan</span>

        </a>

    </div>

</div>
