<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            
            <img src="{{ asset('assets/img/logo1.png') }}" alt="Logo" width="75" >
            {{-- <img src="assets/img/logo.png" alt="Logo Sipemba" width="40"> --}}
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">Sipemba</span>
    </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @if (auth()->user()->id_role == 1)
            <!-- Master Data -->
            <li class="menu-item {{ $isRouteActive('tahun-ajaran.index', 'petugas.index', 'siswa.index', 'kelas.index', 'kenaikan-kelas.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-folder-open"></i>
                    {{-- <span class="fa6-solid--users-line"></span> --}}
                    <div data-i18n="Layouts">Master Data</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ Route::currentRouteName() == 'tahun-ajaran.index' ? 'active' : '' }}">
                        <a href="{{ route('tahun-ajaran.index') }}" class="menu-link">
                            <div data-i18n="Tahun Ajaran">Tahun Ajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'kelas.index' ? 'active' : '' }}">
                        <a href="{{ route('kelas.index') }}" class="menu-link">
                            <div data-i18n="Kelas">Kelas</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'siswa.index' ? 'active' : '' }}">
                        <a href="{{ route('siswa.index') }}" class="menu-link">
                            <div data-i18n="Siswa">Siswa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'petugas.index' ? 'active' : '' }}">
                        <a href="{{ route('petugas.index') }}" class="menu-link">
                            <div data-i18n="Petugas">Petugas</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'kenaikan-kelas.index' ? 'active' : '' }}">
                        <a href="{{ route('kenaikan-kelas.index') }}" class="menu-link">
                            <div data-i18n="Kenaikan Kelas">Kenaikan Kelas</div>
                        </a>
                    </li>
                     {{-- <li class="menu-item {{ Route::currentRouteName() == 'alumni' ? 'active' : '' }}">
                        <a href="{{ route('alumni') }}" class="menu-link">
                            <div data-i18n="Alumni">Alumni</div>
                        </a>
                    </li> --}}
                </ul>
            </li>
        @endif

        @if (auth()->user()->id_role == 1)
            <!-- Broadcast -->
            <li class="menu-item {{ Route::currentRouteName() == 'broadcast' ? 'active' : '' }}">
                <a href="{{ route('broadcast') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxl-whatsapp"></i>
                    <div data-i18n="Analytics">Notifikasi</div>
                </a>
            </li>
            <li class="menu-item {{ Route::currentRouteName() == 'jenis-transaksi.index' ? 'active' : '' }}">
                <a href="{{ route('jenis-transaksi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div data-i18n="Analytics">Jenis Pembayaran</div>
                </a>
            </li>
            {{-- <li class="menu-item {{ Route::currentRouteName() == 'tunggakan.index' ? 'active' : '' }}">
                <a href="{{ route('tunggakan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Tunggakan</div>
                </a>
            </li> --}}
        @endif

        @if (auth()->user()->id_role == 1 || auth()->user()->id_role == 3)
            <!-- Pembayaran -->
            <li class="menu-item {{ Route::currentRouteName() == 'transaksi.index' ? 'active' : '' }}">
                <a href="{{ route('transaksi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Transaksi Pembayaran</div>
                </a>
            </li>
        @endif

            @if (auth()->user()->id_role == 1)
            <!-- Broadcast -->
            <li class="menu-item {{ Route::currentRouteName() == 'tunggakan.index' ? 'active' : '' }}">
                <a href="{{ route('tunggakan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Tagihan Siswa</div>
                </a>
            </li>
            @endif


        {{-- @if (auth()->user()->id_role == 1)
            <!-- Master Data -->
            <li class="menu-item {{ $isRouteActive( 'nama-transaksi', 'jenis-transaksi') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div data-i18n="Keuangan">Data Pembayaran</div>
                </a>
                    <li class="menu-item {{ Route::currentRouteName() == 'jenis-transaksi.index' ? 'active' : '' }}">
                        <a href="{{ route('jenis-transaksi.index') }}" class="menu-link">
                            <div data-i18n="Jenis Pembayaran.index">Jenis Pembayaran</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif --}}


        {{-- @if (auth()->user()->id_role == 1 || auth()->user()->id_role == 2)
            <!-- Laporan -->
            <li
                class="menu-item {{ 'laporan-pembayaran', 'laporan-tarif') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-printer"></i>
                    <div data-i18n="Layouts">Laporan</div>
                </a> --}}
                @if (auth()->user()->id_role == 1 || auth()->user()->id_role == 2)
                <!-- Laporan -->
                <li class="menu-item {{ request()->is('laporan-pembayaran*') || request()->is('laporan-tarif*') || request()->is('laporan-total*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-printer"></i>
                        <span data-i18n="Layouts">Laporan</span>
                    </a>
            
    

                <ul class="menu-sub">
                    <li class="menu-item {{ Route::currentRouteName() == 'laporan-tarif.index' ? 'active' : '' }}">
                        <a href="{{ route('laporan-tarif.index') }}" class="menu-link">
                            <div data-i18n="laporan-tarif">Laporan Tarif Pembayaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'laporan-pembayaran.index' ? 'active' : '' }}">
                        <a href="{{ route('laporan-pembayaran.index') }}" class="menu-link">
                            <div data-i18n="Laporan Pembayaran">Laporan Pembayaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'laporan-total.index' ? 'active' : '' }}">
                        <a href="{{ route('laporan-total.index') }}" class="menu-link">
                            {{-- <i class="menu-icon tf-icons bx bx-printer"></i> --}}
                            <div data-i18n="Laporan Total">Laporan Total</div>
                        </a>
                    </li>
                    
                    
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'jenis-transaksi' ? 'active' : '' }}">
                        <a href="{{ route('jenis-transaksi') }}" class="menu-link">
                            <div data-i18n="Jenis Pembayaran">Jenis Pembayaran</div>
                        </a>
                    </li> --}}
                </ul>  
            </li>
        @endif

        {{-- @if (auth()->user()->id_role == 1)
        <!-- Arsip -->
        <li class="menu-item {{ Route::currentRouteName() == 'kelulusan.index' ? 'active' : '' }}">
            <a href="{{ route('kelulusan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-content"></i>
                <div data-i18n="Analytics">Arsip</div>
            </a>
        </li>
        @endif --}}

        {{-- tambahan --}}
        @if (auth()->user()->id_role == 1)
            <!-- Arsip -->
            <li
                class="menu-item {{ $isRouteActive('kelulusan.index', 'alumni.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-book-content"></i>
                    <div data-i18n="Layouts">Arsip</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ Route::currentRouteName() == 'kelulusan.index' ? 'active' : '' }}">
                        <a href="{{ route('kelulusan.index') }}" class="menu-link">
                            <div data-i18n="Kelulusan">Kelulusan</div>
                        </a>
                    </li> 
                    <li class="menu-item {{ Route::currentRouteName() == 'alumni.index' ? 'active' : '' }}">
                        <a href="{{ route('alumni.index') }}" class="menu-link">
                            <div data-i18n="Alumni">Alumni</div>
                        </a>
                    </li>
            </li>
        @endif

        {{-- @if (auth()->user()->id_role == 1)
            <!-- Settings -->
            <li
                class="menu-item {{ $isRouteActive('aplikasi', 'tagihan', 'kelulusan', 'kenaikan-kelas') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Layouts">Settings</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ Route::currentRouteName() == 'aplikasi' ? 'active' : '' }}">
                        <a href="{{ route('aplikasi') }}" class="menu-link">
                            <div data-i18n="Aplikasi">Aplikasi</div>
                        </a>
                    </li> --}}
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'tahun-ajaran' ? 'active' : '' }}">
                        <a href="{{ route('tahun-ajaran') }}" class="menu-link">
                            <div data-i18n="Tahun Ajaran">Tahun Ajaran</div>
                        </a>
                    </li> --}}
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'tagihan' ? 'active' : '' }}">
                        <a href="{{ route('tagihan') }}" class="menu-link">
                            <div data-i18n="Tagihan">Tagihan</div>
                        </a>
                    </li> --}}
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'jenis-transaksi' ? 'active' : '' }}">
                        <a href="{{ route('jenis-transaksi') }}" class="menu-link">
                            <div data-i18n="Jenis Pembayaran">Jenis Pembayaran</div>
                        </a>
                    </li> --}}
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'kelulusan' ? 'active' : '' }}">
                        <a href="{{ route('kelulusan') }}" class="menu-link">
                            <div data-i18n="Kelulusan">Kelulusan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'kenaikan-kelas' ? 'active' : '' }}">
                        <a href="{{ route('kenaikan-kelas') }}" class="menu-link">
                            <div data-i18n="Kenaikan Kelas">Kenaikan Kelas</div>
                        </a>
                    </li>
            </li>
        @endif --}}

    </ul>
</aside>
