<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UD Sentosa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <style>
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',sans-serif;background:#f4f7fb;color:#0f172a}
        .sidebar{position:fixed;inset:0 auto 0 0;width:270px;background:#0f172a;color:#fff;overflow-y:auto;box-shadow:4px 0 20px rgba(0,0,0,.15);transition:width .25s ease,transform .25s ease;z-index:1040}
        .sidebar::-webkit-scrollbar{width:6px}.sidebar::-webkit-scrollbar-thumb{background:#334155;border-radius:20px}
        .sidebar-header{padding:22px;border-bottom:1px solid rgba(255,255,255,.08)}
        .brand{display:flex;align-items:flex-start;gap:14px}.brand-icon{width:46px;height:46px;border-radius:12px;background:#2563eb;display:flex;align-items:center;justify-content:center;font-size:22px;flex:0 0 auto}
        .brand-text h4{margin:0;font-size:18px;font-weight:700;color:#fff}.brand-text small{color:#94a3b8;line-height:1.4}
        .sidebar-menu{padding:15px 10px 25px}.menu-title{color:#64748b;font-size:11px;font-weight:700;margin:18px 12px 8px;text-transform:uppercase;letter-spacing:.7px}
        .menu-item{display:flex;align-items:center;gap:12px;text-decoration:none;color:#dbe4f0;padding:11px 15px;border-radius:10px;margin-bottom:5px;transition:.2s;font-size:14px;white-space:nowrap}
        .menu-item i{width:20px;text-align:center;font-size:15px}.menu-item:hover{background:#1e293b;color:#fff}.menu-item.active{background:#2563eb;color:#fff;font-weight:600;box-shadow:0 5px 15px rgba(37,99,235,.4)}
        .topbar{position:fixed;top:0;left:270px;right:0;height:68px;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;padding:0 28px;z-index:1030;transition:left .25s ease}
        .sidebar-toggle{width:42px;height:42px;border:1px solid #d8dee9;border-radius:10px;background:#fff;color:#0f172a;display:inline-flex;align-items:center;justify-content:center;transition:.2s}.sidebar-toggle:hover{background:#f1f5f9;border-color:#cbd5e1}
        .account-toggle{border:1px solid #e2e8f0;background:#fff;border-radius:12px;padding:7px 10px 7px 8px;display:flex;align-items:center;gap:10px;color:#0f172a}.account-toggle:hover,.account-toggle:focus{background:#f8fafc;border-color:#cbd5e1}
        .avatar-letter{width:34px;height:34px;border-radius:50%;background:#2563eb;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;text-transform:uppercase}.account-name{max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:14px;font-weight:600}
        .content{margin-left:270px;padding:98px 30px 30px;transition:margin-left .25s ease}.card{border:none;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,.06)}
        .dataTables_wrapper .dataTables_filter input,.dataTables_wrapper .dataTables_length select{border:1px solid #d1d5db;border-radius:8px;padding:6px 10px}.dataTables_wrapper .dataTables_length select{min-width:78px;padding-right:32px}.dataTables_wrapper .pagination .page-link{border-radius:8px;margin:0 2px}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:1035}
        .sidebar-collapsed .sidebar{width:84px}.sidebar-collapsed .topbar{left:84px}.sidebar-collapsed .content{margin-left:84px}.sidebar-collapsed .sidebar-header{padding:18px}.sidebar-collapsed .brand{justify-content:center}.sidebar-collapsed .brand-text,.sidebar-collapsed .menu-title{display:none}.sidebar-collapsed .sidebar-menu{padding-top:18px}.sidebar-collapsed .menu-item{justify-content:center;gap:0;padding:13px 0;font-size:0}.sidebar-collapsed .menu-item i{width:auto;font-size:17px}
        @media(max-width:768px){.sidebar{width:270px;transform:translateX(-100%)}.sidebar-collapsed .sidebar{width:270px}.sidebar-open .sidebar{transform:translateX(0)}.sidebar-open .sidebar-overlay{display:block}.topbar,.sidebar-collapsed .topbar{left:0;padding:0 16px}.content,.sidebar-collapsed .content{margin-left:0;padding:92px 16px 24px}.account-name{display:none}}
    </style>
</head>
<body>
@php
    $currentUser = auth()->user();
    $displayName = $currentUser?->name ?? 'Akun';
    $displayEmail = $currentUser?->email ?? '-';
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
    $role = $currentUser?->role;
    $isAdmin = $role === 'admin';
    $isOperasional = $role === 'pengguna_operasional';
    $canOperate = $isAdmin || $isOperasional;
@endphp

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-bus"></i></div>
            <div class="brand-text">
                <h4>UD SENTOSA</h4>
                <small>Sistem Pengelolaan<br>Kendaraan Operasional</small>
            </div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-title">Menu Utama</div>
        <a href="{{ url('/dashboard') }}" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>

        @if($canOperate || $isAdmin)
        <div class="menu-title">Master Data</div>
            @if($canOperate)
                <a href="{{ url('/kendaraan') }}" class="menu-item {{ request()->is('kendaraan*') ? 'active' : '' }}"><i class="fa-solid fa-car"></i><span>Kendaraan</span></a>
                <a href="{{ url('/sopir') }}" class="menu-item {{ request()->is('sopir*') ? 'active' : '' }}"><i class="fa-regular fa-user"></i><span>Sopir</span></a>
                <a href="{{ url('/sparepart') }}" class="menu-item {{ request()->is('sparepart*') ? 'active' : '' }}"><i class="fa-solid fa-gears"></i><span>Sparepart</span></a>
                <a href="{{ url('/pemegang') }}" class="menu-item {{ request()->is('pemegang*') ? 'active' : '' }}"><i class="fa-solid fa-address-card"></i><span>Pemegang</span></a>
            @endif
            @if($isAdmin)
                <a href="{{ url('/pengguna') }}" class="menu-item {{ request()->is('pengguna*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i><span>Pengguna</span></a>
            @endif
        @endif

        <div class="menu-title">Transaksi</div>
        @if($canOperate)
            <a href="{{ url('/data-pemakai') }}" class="menu-item {{ request()->is('data-pemakai*') || request()->is('peminjaman-kendaraan*') || request()->is('pengembalian*') ? 'active' : '' }}"><i class="fa-regular fa-calendar"></i><span>Data Pemakai</span></a>
            <a href="{{ url('/servis') }}" class="menu-item {{ request()->is('servis') ? 'active' : '' }}"><i class="fa-solid fa-screwdriver-wrench"></i><span>Servis Kendaraan</span></a>
            <a href="{{ url('/penggantian-sparepart') }}" class="menu-item {{ request()->is('penggantian-sparepart*') ? 'active' : '' }}"><i class="fa-solid fa-toolbox"></i><span>Penggantian Sparepart</span></a>
        @endif
        <a href="{{ url('/riwayat-servis') }}" class="menu-item {{ request()->is('riwayat-servis*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i><span>Riwayat Servis</span></a>

        <div class="menu-title">Klasifikasi</div>
        <a href="{{ url('/prediksi-naive-bayes') }}" class="menu-item {{ request()->is('prediksi-naive-bayes*') ? 'active' : '' }}"><i class="fa-solid fa-brain"></i><span>Prediksi Naive Bayes</span></a>
        <a href="{{ url('/prediksi-decision-tree') }}" class="menu-item {{ request()->is('prediksi-decision-tree*') ? 'active' : '' }}"><i class="fa-solid fa-code-branch"></i><span>Prediksi Decision Tree</span></a>
        <a href="{{ url('/perbandingan-hasil') }}" class="menu-item {{ request()->is('perbandingan-hasil*') ? 'active' : '' }}"><i class="fa-solid fa-scale-balanced"></i><span>Perbandingan Hasil</span></a>

        <div class="menu-title">Laporan</div>
        <a href="{{ url('/laporan-kendaraan') }}" class="menu-item {{ request()->is('laporan-kendaraan*') ? 'active' : '' }}"><i class="fa-regular fa-file-lines"></i><span>Laporan Kendaraan</span></a>
        <a href="{{ url('/laporan-servis') }}" class="menu-item {{ request()->is('laporan-servis*') ? 'active' : '' }}"><i class="fa-regular fa-file"></i><span>Laporan Servis</span></a>
        <a href="{{ url('/laporan-prediksi') }}" class="menu-item {{ request()->is('laporan-prediksi*') ? 'active' : '' }}"><i class="fa-solid fa-file-export"></i><span>Laporan Prediksi</span></a>

        @if($isAdmin)
        <div class="menu-title">Pengaturan</div>
        <a href="{{ url('/pengaturan') }}" class="menu-item {{ request()->is('pengaturan*') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a>
        @endif
    </nav>
</aside>

<header class="topbar">
    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar"><i class="fa-solid fa-bars"></i></button>
    <div class="dropdown">
        <button class="account-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar-letter">{{ $avatarLetter }}</span>
            <span class="account-name">{{ $displayName }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li class="px-3 py-2">
                <div class="fw-semibold">{{ $displayName }}</div>
                <small class="text-muted">{{ $displayEmail }}</small>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                </form>
            </li>
        </ul>
    </div>
</header>

<main class="content">
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        $('.datatable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Tidak ada data',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });

        const $body = $('body');
        const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

        $('#sidebarToggle').on('click', function () {
            if (isMobile()) {
                $body.toggleClass('sidebar-open');
                return;
            }

            $body.toggleClass('sidebar-collapsed');
            localStorage.setItem('udSentosaSidebarCollapsed', $body.hasClass('sidebar-collapsed') ? '1' : '0');
        });

        $('#sidebarOverlay, .sidebar .menu-item').on('click', function () {
            if (isMobile()) {
                $body.removeClass('sidebar-open');
            }
        });

        if (!isMobile() && localStorage.getItem('udSentosaSidebarCollapsed') === '1') {
            $body.addClass('sidebar-collapsed');
        }

        $(window).on('resize', function () {
            if (!isMobile()) {
                $body.removeClass('sidebar-open');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
