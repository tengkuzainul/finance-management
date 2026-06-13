<!-- Sidebar -->
<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-white border-r border-slate-200">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200">
        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                <img src="{{ URL::asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-6 h-6 object-contain">
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-tight">Kebab Ikhwan</h1>
                <p class="text-[10px] text-slate-500 -mt-0.5">Finance Management</p>
            </div>
        </a>
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
        <!-- Dashboard -->
        <a href="{{ url('/dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('dashboard') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-home w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <!-- Manajemen Menu (Admin Only) -->
        @auth
            @if (auth()->user()->is_admin)
                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Manajemen</p>
                </div>

                <!-- Master Data Dropdown -->
                <div>
                    <button onclick="toggleDropdown('master-data-menu')"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('master-data.*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                        <div class="flex items-center gap-3">
                            <i
                                class="fas fa-database w-5 text-center {{ request()->routeIs('master-data.*') ? '' : 'text-teal-500' }}"></i>
                            <span>Master Data</span>
                        </div>
                        <i id="master-data-menu-icon"
                            class="fas fa-chevron-down text-xs transition-transform duration-200 {{ request()->routeIs('master-data.*') ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="master-data-menu"
                        class="{{ request()->routeIs('master-data.*') ? '' : 'hidden' }} mt-1 ml-6 space-y-1">
                        <a href="{{ route('master-data.cabang.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('master-data.cabang.*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                            <i
                                class="fas fa-store w-4 text-center {{ request()->routeIs('master-data.cabang.*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                            <span>Cabang</span>
                        </a>
                        <a href="{{ route('master-data.karyawan.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('master-data.karyawan.*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                            <i
                                class="fas fa-users w-4 text-center {{ request()->routeIs('master-data.karyawan.*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                            <span>Karyawan</span>
                        </a>
                        <a href="{{ route('master-data.laporan-keuangan.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('master-data.laporan-keuangan.*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                            <i
                                class="fas fa-file-invoice-dollar w-4 text-center {{ request()->routeIs('master-data.laporan-keuangan.*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Keuangan Menu -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Keuangan</p>
        </div>

        <!-- Pendapatan -->
        <a href="{{ auth()->user()?->is_admin ? url('/Pendapatan') : route('karyawan.Pendapatan.create') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('Pendapatan*') || request()->routeIs('karyawan.Pendapatan.*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
            <i
                class="fas fa-arrow-down w-5 text-center {{ request()->is('Pendapatan*') || request()->routeIs('karyawan.Pendapatan.*') ? '' : 'text-green-500' }}"></i>
            <span>{{ auth()->user()?->is_admin ? 'Pendapatan' : 'Input Pendapatan' }}</span>
        </a>

        @if (auth()->user()?->is_admin)
            <!-- Pengeluaran (Admin Only) -->
            <a href="{{ url('/pengeluaran') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('pengeluaran*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                <i
                    class="fas fa-arrow-up w-5 text-center {{ request()->is('pengeluaran*') ? '' : 'text-red-500' }}"></i>
                <span>Pengeluaran</span>
            </a>
        @endif

        <!-- Laporan Dropdown -->
        <div>
            <button onclick="toggleDropdown('laporan-menu')"
                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('laporan*') || request()->routeIs('karyawan.laporan.*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                <div class="flex items-center gap-3">
                    <i
                        class="fas fa-chart-bar w-5 text-center {{ request()->is('laporan*') || request()->routeIs('karyawan.laporan.*') ? '' : 'text-blue-500' }}"></i>
                    <span>Laporan</span>
                </div>
                <i id="laporan-menu-icon"
                    class="fas fa-chevron-down text-xs transition-transform duration-200 {{ request()->is('laporan*') || request()->routeIs('karyawan.laporan.*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="laporan-menu"
                class="{{ request()->is('laporan*') || request()->routeIs('karyawan.laporan.*') ? '' : 'hidden' }} mt-1 ml-6 space-y-1">
                @if (auth()->user()?->is_admin)
                    <a href="{{ url('/laporan/harian') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->is('laporan/harian*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-day w-4 text-center {{ request()->is('laporan/harian*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Harian</span>
                    </a>
                    <a href="{{ url('/laporan/mingguan') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->is('laporan/mingguan*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-week w-4 text-center {{ request()->is('laporan/mingguan*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Mingguan</span>
                    </a>
                    <a href="{{ url('/laporan/bulanan') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->is('laporan/bulanan*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-alt w-4 text-center {{ request()->is('laporan/bulanan*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Bulanan</span>
                    </a>
                @else
                    <a href="{{ route('karyawan.laporan.harian') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('karyawan.laporan.harian') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-day w-4 text-center {{ request()->routeIs('karyawan.laporan.harian') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Harian</span>
                    </a>
                    <a href="{{ route('karyawan.laporan.mingguan') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('karyawan.laporan.mingguan') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-week w-4 text-center {{ request()->routeIs('karyawan.laporan.mingguan') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Mingguan</span>
                    </a>
                    <a href="{{ route('karyawan.laporan.bulanan') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('karyawan.laporan.bulanan') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-calendar-alt w-4 text-center {{ request()->routeIs('karyawan.laporan.bulanan') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Laporan Bulanan</span>
                    </a>
                    <a href="{{ route('karyawan.laporan.riwayat') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('karyawan.laporan.riwayat') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-history w-4 text-center {{ request()->routeIs('karyawan.laporan.riwayat') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Riwayat Laporan</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Penggajian -->
        <a href="{{ auth()->user()?->is_admin ? route('gaji.index') : route('karyawan.gaji.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('gaji*') || request()->routeIs('karyawan.gaji.*') || request()->routeIs('gaji.*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
            <i
                class="fas fa-money-bill-wave w-5 text-center {{ request()->is('gaji*') || request()->routeIs('karyawan.gaji.*') || request()->routeIs('gaji.*') ? '' : 'text-emerald-500' }}"></i>
            <span>{{ auth()->user()?->is_admin ? 'Data Penggajian' : 'Gaji Saya' }}</span>
        </a>

        <!-- Settings Menu -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pengaturan</p>
        </div>

        <!-- Profile -->
        <a href="{{ url('/profile') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('profile*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fas fa-user-circle w-5 text-center {{ request()->is('profile*') ? '' : 'text-slate-500' }}"></i>
            <span>Profile</span>
        </a>

        <!-- Settings (Admin Only) -->
        @if (auth()->user()?->is_admin)
            <div>
                <button onclick="toggleDropdown('pengaturan-menu')"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('settings*') || request()->is('users*') ? 'bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <div class="flex items-center gap-3">
                        <i
                            class="fas fa-cog w-5 text-center {{ request()->is('settings*') || request()->is('users*') ? '' : 'text-slate-500' }}"></i>
                        <span>Pengaturan</span>
                    </div>
                    <i id="pengaturan-menu-icon"
                        class="fas fa-chevron-down text-xs transition-transform duration-200 {{ request()->is('settings*') || request()->is('users*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="pengaturan-menu"
                    class="{{ request()->is('settings*') || request()->is('users*') ? '' : 'hidden' }} mt-1 ml-6 space-y-1">
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->is('settings') || request()->routeIs('settings.index') || (request()->routeIs('settings.*') && !request()->is('users*')) ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-sliders-h w-4 text-center {{ request()->is('settings') || request()->routeIs('settings.index') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Konfigurasi Sistem</span>
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors {{ request()->is('users*') ? 'bg-orange-50 text-orange-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-users w-4 text-center {{ request()->is('users*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>
</aside>
