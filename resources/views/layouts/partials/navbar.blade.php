<!-- Top Navigation Bar -->
<header class="fixed top-0 right-0 left-0 lg:left-64 z-30 h-16 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="h-full px-4 md:px-6 flex items-center justify-between">
        <!-- Left Side - Menu Toggle & Search -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 text-slate-600">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex items-center">
                <div class="relative" id="search-container">
                    <input type="text" id="global-search" placeholder="Cari transaksi, laporan..."
                        class="w-80 pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200"
                        autocomplete="off" onkeyup="handleSearch(event)" onfocus="showSearchResults()">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <kbd
                        class="hidden lg:inline-flex absolute right-3 top-1/2 -translate-y-1/2 px-2 py-0.5 text-[10px] font-medium text-slate-400 bg-white rounded border border-slate-200">
                        ⌘K
                    </kbd>

                    <!-- Search Results Dropdown -->
                    <div id="search-results"
                        class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 max-h-96 overflow-y-auto z-50">
                        <div id="search-results-content"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Actions & Profile -->
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Mobile Search Button -->
            <button onclick="openMobileSearch()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600">
                <i class="fas fa-search"></i>
            </button>

            <!-- Notifications -->
            <div class="relative" id="notification-container">
                <button onclick="toggleNotifications()"
                    class="relative p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    @php $unreadCount = auth()->user()?->unread_notifications_count ?? 0; @endphp
                    <span id="notification-badge"
                        class="absolute top-1 right-1 min-w-[8px] h-2 bg-orange-500 rounded-full ring-2 ring-white {{ $unreadCount > 0 ? '' : 'hidden' }}"></span>
                    <span id="notification-count"
                        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 rounded-full ring-2 ring-white text-[10px] text-white font-bold flex items-center justify-center {{ $unreadCount > 0 ? '' : 'hidden' }}">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notification-dropdown"
                    class="hidden absolute right-0 mt-2 w-80 md:w-96 bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 animate-fade-in overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <h3 class="font-semibold text-slate-700">Notifikasi</h3>
                        <button onclick="markAllAsRead()"
                            class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                            Tandai semua dibaca
                        </button>
                    </div>
                    <div id="notification-list" class="max-h-96 overflow-y-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memuat...
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-slate-100 bg-slate-50 text-center">
                        <a href="{{ route('notifications.all') }}"
                            class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Add Button - Different for Admin vs Karyawan -->
            @if (auth()->user()?->is_admin)
                <!-- Admin: Dropdown with Pemasukan & Pengeluaran -->
                <div class="relative hidden md:block" id="quick-add-container">
                    <button onclick="toggleQuickAdd()"
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/30 transition-all duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Transaksi Baru</span>
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>

                    <!-- Quick Add Dropdown -->
                    <div id="quick-add-dropdown"
                        class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 py-2 animate-fade-in">
                        <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-green-50 transition-colors">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-arrow-down text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-700">Pemasukan</p>
                                <p class="text-xs text-slate-400">Catat pendapatan baru</p>
                            </div>
                        </a>
                        <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(2)]) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-red-50 transition-colors">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-arrow-up text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-red-700">Pengeluaran</p>
                                <p class="text-xs text-slate-400">Catat pengeluaran baru</p>
                            </div>
                        </a>
                    </div>
                </div>
            @else
                <!-- Karyawan: Direct link to Pemasukan -->
                <a href="{{ route('karyawan.pemasukan.create') }}"
                    class="hidden md:flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-500/30 transition-all duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Input Pemasukan</span>
                </a>
            @endif

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profile-button"
                    onclick="document.getElementById('profile-dropdown').classList.toggle('hidden')"
                    class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <div
                        class="w-9 h-9 bg-linear-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-blue-500/30">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-slate-700 leading-tight">
                            {{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()?->is_admin ? 'Administrator' : 'Karyawan' }}
                        </p>
                    </div>
                    <i class="hidden md:block fas fa-chevron-down text-slate-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profile-dropdown"
                    class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 py-2 animate-fade-in">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-sm font-semibold text-slate-700">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ url('/profile') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-user-circle w-4 text-slate-400"></i>
                            <span>Profile Saya</span>
                        </a>
                        <a href="{{ url('/settings') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-cog w-4 text-slate-400"></i>
                            <span>Pengaturan</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-question-circle w-4 text-slate-400"></i>
                            <span>Bantuan</span>
                        </a>
                    </div>
                    <div class="border-t border-slate-100 pt-1">
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" onclick="confirmLogout()"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-4"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function confirmLogout() {
        Swal.fire({
            icon: 'question',
            title: 'Logout?',
            text: 'Apakah Anda yakin ingin keluar dari sistem?',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Notification Functions
    let notificationDropdownOpen = false;

    function toggleNotifications() {
        const dropdown = document.getElementById('notification-dropdown');
        notificationDropdownOpen = !notificationDropdownOpen;

        if (notificationDropdownOpen) {
            dropdown.classList.remove('hidden');
            loadNotifications();
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function loadNotifications() {
        const listEl = document.getElementById('notification-list');

        fetch('{{ route('notifications.index') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.notifications.length === 0) {
                    listEl.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-slate-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell-slash text-slate-400 text-xl"></i>
                        </div>
                        <p class="text-slate-500 text-sm">Belum ada notifikasi</p>
                    </div>
                `;
                    return;
                }

                listEl.innerHTML = data.notifications.map(notif => `
                <a href="${notif.link || '#'}" onclick="handleNotificationClick(event, ${notif.id}, '${notif.link || ''}')"
                   class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 ${notif.read_at ? 'opacity-60' : ''}">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full ${notif.bg_color || 'bg-slate-100'} flex items-center justify-center shrink-0">
                            <i class="fas ${notif.icon || 'fa-bell text-slate-500'}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 ${notif.read_at ? '' : 'font-semibold'}">${notif.title}</p>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">${notif.message}</p>
                            <p class="text-xs text-slate-400 mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatTimeAgo(notif.created_at)}
                            </p>
                        </div>
                        ${!notif.read_at ? '<span class="w-2 h-2 bg-orange-500 rounded-full shrink-0 mt-2"></span>' : ''}
                    </div>
                </a>
            `).join('');
            })
            .catch(error => {
                listEl.innerHTML = `
                <div class="p-4 text-center text-red-500 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> Gagal memuat notifikasi
                </div>
            `;
            });
    }

    function handleNotificationClick(event, id, link) {
        // Mark as read in background
        fetch(`{{ url('notifications') }}/${id}/read`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            updateNotificationCount();
        });

        // If there's a link, navigate to it
        if (link && link !== '' && link !== '#') {
            // Let the default <a> behavior handle navigation
            return true;
        } else {
            // Prevent navigation if no link
            event.preventDefault();
        }
    }

    function markAsRead(id) {
        fetch(`{{ url('notifications') }}/${id}/read`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            updateNotificationCount();
        });
    }

    function markAllAsRead() {
        fetch('{{ route('notifications.read-all') }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            loadNotifications();
            updateNotificationCount();
        });
    }

    function updateNotificationCount() {
        fetch('{{ route('notifications.unread-count') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                const countEl = document.getElementById('notification-count');

                if (data.count > 0) {
                    badge.classList.remove('hidden');
                    countEl.classList.remove('hidden');
                    countEl.textContent = data.count > 9 ? '9+' : data.count;
                } else {
                    badge.classList.add('hidden');
                    countEl.classList.add('hidden');
                }
            });
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'Baru saja';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';

        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('notification-container');
        if (container && !container.contains(event.target)) {
            document.getElementById('notification-dropdown').classList.add('hidden');
            notificationDropdownOpen = false;
        }
    });

    // Refresh notification count every 30 seconds
    setInterval(updateNotificationCount, 30000);

    // ============= QUICK ADD DROPDOWN (ADMIN ONLY) =============
    let quickAddOpen = false;

    function toggleQuickAdd() {
        const dropdown = document.getElementById('quick-add-dropdown');
        if (!dropdown) return;

        quickAddOpen = !quickAddOpen;
        dropdown.classList.toggle('hidden', !quickAddOpen);
    }

    // Close quick add dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('quick-add-container');
        const dropdown = document.getElementById('quick-add-dropdown');
        if (container && dropdown && !container.contains(event.target)) {
            dropdown.classList.add('hidden');
            quickAddOpen = false;
        }
    });

    // ============= GLOBAL SEARCH FUNCTIONALITY =============
    const isAdmin = {{ auth()->user()?->is_admin ? 'true' : 'false' }};

    // Define searchable menu items based on role
    const searchableItems = isAdmin ? [
        // Dashboard
        {
            name: 'Dashboard',
            url: '{{ route('dashboard') }}',
            icon: 'fa-home',
            category: 'Dashboard',
            keywords: ['beranda', 'home', 'statistik']
        },

        // Master Data - Cabang
        {
            name: 'Daftar Cabang',
            url: '{{ route('master-data.cabang.index') }}',
            icon: 'fa-store',
            category: 'Master Data',
            keywords: ['cabang', 'branch', 'toko', 'outlet']
        },
        {
            name: 'Tambah Cabang Baru',
            url: '{{ route('master-data.cabang.create') }}',
            icon: 'fa-plus',
            category: 'Master Data',
            keywords: ['tambah cabang', 'buat cabang', 'new branch']
        },

        // Master Data - Karyawan
        {
            name: 'Daftar Karyawan',
            url: '{{ route('master-data.karyawan.index') }}',
            icon: 'fa-users',
            category: 'Master Data',
            keywords: ['karyawan', 'pegawai', 'staff', 'employee']
        },
        {
            name: 'Tambah Karyawan Baru',
            url: '{{ route('master-data.karyawan.create') }}',
            icon: 'fa-user-plus',
            category: 'Master Data',
            keywords: ['tambah karyawan', 'buat karyawan', 'new employee']
        },

        // Laporan Keuangan
        {
            name: 'Daftar Laporan Keuangan',
            url: '{{ route('master-data.laporan-keuangan.index') }}',
            icon: 'fa-file-invoice-dollar',
            category: 'Laporan',
            keywords: ['laporan', 'keuangan', 'transaksi', 'report', 'finance']
        },
        {
            name: 'Input Pemasukan',
            url: '{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]) }}',
            icon: 'fa-arrow-down',
            category: 'Transaksi',
            keywords: ['pemasukan', 'income', 'pendapatan', 'masuk']
        },
        {
            name: 'Input Pengeluaran',
            url: '{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(2)]) }}',
            icon: 'fa-arrow-up',
            category: 'Transaksi',
            keywords: ['pengeluaran', 'expense', 'biaya', 'keluar']
        },
        {
            name: 'Laporan Harian',
            url: '{{ route('master-data.laporan-keuangan.index') }}?periode=harian',
            icon: 'fa-calendar-day',
            category: 'Laporan',
            keywords: ['harian', 'daily', 'hari ini']
        },
        {
            name: 'Laporan Bulanan',
            url: '{{ route('master-data.laporan-keuangan.index') }}?periode=bulanan',
            icon: 'fa-calendar-alt',
            category: 'Laporan',
            keywords: ['bulanan', 'monthly', 'bulan']
        },

        // Gaji
        {
            name: 'Daftar Gaji',
            url: '{{ route('gaji.index') }}',
            icon: 'fa-money-bill-wave',
            category: 'Gaji',
            keywords: ['gaji', 'salary', 'payroll', 'upah']
        },

        // Informasi (in Settings)
        {
            name: 'Pengaturan & Informasi',
            url: '{{ route('settings.index') }}',
            icon: 'fa-cog',
            category: 'Pengaturan',
            keywords: ['pengaturan', 'settings', 'informasi', 'info', 'pengumuman']
        },

        // Profile
        {
            name: 'Profile Saya',
            url: '{{ route('profile.index') }}',
            icon: 'fa-user-circle',
            category: 'Profile',
            keywords: ['profile', 'profil', 'akun saya']
        },

        // Notifikasi
        {
            name: 'Semua Notifikasi',
            url: '{{ route('notifications.all') }}',
            icon: 'fa-bell',
            category: 'Notifikasi',
            keywords: ['notifikasi', 'notification', 'pemberitahuan']
        },
    ] : [
        // Karyawan menu items
        {
            name: 'Dashboard',
            url: '{{ route('dashboard') }}',
            icon: 'fa-home',
            category: 'Dashboard',
            keywords: ['beranda', 'home', 'statistik']
        },
        {
            name: 'Pemasukan Saya',
            url: '{{ route('karyawan.pemasukan.index') }}',
            icon: 'fa-arrow-down',
            category: 'Transaksi',
            keywords: ['pemasukan', 'income', 'pendapatan', 'transaksi']
        },
        {
            name: 'Input Pemasukan',
            url: '{{ route('karyawan.pemasukan.create') }}',
            icon: 'fa-plus-circle',
            category: 'Transaksi',
            keywords: ['input', 'tambah', 'catat pemasukan']
        },
        {
            name: 'Gaji Saya',
            url: '{{ route('karyawan.gaji.index') }}',
            icon: 'fa-money-bill-wave',
            category: 'Gaji',
            keywords: ['gaji', 'salary', 'upah', 'slip gaji']
        },
        {
            name: 'Informasi Manajemen',
            url: '{{ route('karyawan.informasi.index') }}',
            icon: 'fa-info-circle',
            category: 'Informasi',
            keywords: ['informasi', 'pengumuman', 'info']
        },
        {
            name: 'Profile Saya',
            url: '{{ route('profile.index') }}',
            icon: 'fa-user-circle',
            category: 'Profile',
            keywords: ['profile', 'profil', 'akun']
        },
        {
            name: 'Semua Notifikasi',
            url: '{{ route('notifications.all') }}',
            icon: 'fa-bell',
            category: 'Notifikasi',
            keywords: ['notifikasi', 'notification']
        },
    ];

    let searchResultsVisible = false;

    function handleSearch(event) {
        const query = event.target.value.toLowerCase().trim();
        const resultsContainer = document.getElementById('search-results-content');

        if (query.length === 0) {
            hideSearchResults();
            return;
        }

        // Filter items based on query
        const results = searchableItems.filter(item => {
            return item.name.toLowerCase().includes(query) ||
                item.category.toLowerCase().includes(query) ||
                item.keywords.some(k => k.toLowerCase().includes(query));
        });

        if (results.length === 0) {
            resultsContainer.innerHTML = `
                <div class="p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 bg-slate-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-slate-400 text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-sm">Tidak ditemukan hasil untuk "${query}"</p>
                </div>
            `;
        } else {
            // Group results by category
            const grouped = {};
            results.forEach(item => {
                if (!grouped[item.category]) {
                    grouped[item.category] = [];
                }
                grouped[item.category].push(item);
            });

            let html = '';
            for (const [category, items] of Object.entries(grouped)) {
                html +=
                    `<div class="px-3 py-2 bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider">${category}</div>`;
                items.forEach(item => {
                    html += `
                        <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 transition-colors border-b border-slate-100 last:border-0">
                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas ${item.icon} text-slate-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">${highlightMatch(item.name, query)}</p>
                            </div>
                        </a>
                    `;
                });
            }
            resultsContainer.innerHTML = html;
        }

        showSearchResults();

        // Handle Enter key - navigate to first result
        if (event.key === 'Enter' && results.length > 0) {
            window.location.href = results[0].url;
        }
    }

    function highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="bg-yellow-200 text-yellow-800 rounded px-0.5">$1</span>');
    }

    function showSearchResults() {
        const dropdown = document.getElementById('search-results');
        if (dropdown) {
            dropdown.classList.remove('hidden');
            searchResultsVisible = true;
        }
    }

    function hideSearchResults() {
        const dropdown = document.getElementById('search-results');
        if (dropdown) {
            dropdown.classList.add('hidden');
            searchResultsVisible = false;
        }
    }

    // Close search results when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('search-container');
        if (container && !container.contains(event.target)) {
            hideSearchResults();
        }
    });

    // Keyboard shortcut (Ctrl+K / Cmd+K) to focus search
    document.addEventListener('keydown', function(event) {
        if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
            event.preventDefault();
            const searchInput = document.getElementById('global-search');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }

        // Close search on Escape
        if (event.key === 'Escape') {
            hideSearchResults();
            const searchInput = document.getElementById('global-search');
            if (searchInput) {
                searchInput.blur();
            }
        }
    });

    // Mobile search modal
    function openMobileSearch() {
        Swal.fire({
            title: 'Cari',
            input: 'text',
            inputPlaceholder: 'Cari menu, laporan...',
            showCancelButton: true,
            confirmButtonText: 'Cari',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f97316',
            preConfirm: (query) => {
                if (!query) return false;

                const results = searchableItems.filter(item => {
                    return item.name.toLowerCase().includes(query.toLowerCase()) ||
                        item.category.toLowerCase().includes(query.toLowerCase()) ||
                        item.keywords.some(k => k.toLowerCase().includes(query.toLowerCase()));
                });

                if (results.length > 0) {
                    window.location.href = results[0].url;
                } else {
                    Swal.showValidationMessage('Tidak ditemukan hasil');
                    return false;
                }
            }
        });
    }
</script>
