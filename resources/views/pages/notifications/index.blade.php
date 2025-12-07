@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
                <p class="text-gray-600 mt-1">Semua notifikasi Anda</p>
            </div>
            <div class="flex gap-2">
                <button onclick="markAllAsRead()"
                    class="px-4 py-2 bg-brand-orange text-white rounded-lg hover:bg-orange-600 flex items-center gap-2">
                    <i class="fas fa-check-double"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b border-gray-100">
                <nav class="flex -mb-px">
                    <button onclick="filterNotifications('all')" id="tab-all"
                        class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-brand-orange text-brand-orange">
                        Semua
                        <span id="count-all" class="ml-1 px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs"></span>
                    </button>
                    <button onclick="filterNotifications('unread')" id="tab-unread"
                        class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Belum Dibaca
                        <span id="count-unread"
                            class="ml-1 px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs"></span>
                    </button>
                    <button onclick="filterNotifications('read')" id="tab-read"
                        class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Sudah Dibaca
                        <span id="count-read"
                            class="ml-1 px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-xs"></span>
                    </button>
                </nav>
            </div>

            <!-- Notifications List -->
            <div id="notifications-container" class="divide-y divide-gray-100">
                <!-- Loading -->
                <div id="loading-state" class="p-8 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-orange mx-auto"></div>
                    <p class="text-gray-500 mt-2">Memuat notifikasi...</p>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="p-12 text-center hidden">
                    <div class="p-4 rounded-full bg-gray-100 inline-flex mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada notifikasi</p>
                    <p class="text-gray-400 text-sm mt-1">Anda belum memiliki notifikasi</p>
                </div>

                <!-- Notifications will be rendered here -->
                <div id="notifications-list"></div>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-100 hidden">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Menampilkan <span id="showing-from">0</span> - <span id="showing-to">0</span> dari <span
                            id="total-count">0</span> notifikasi
                    </p>
                    <div class="flex gap-2">
                        <button onclick="loadPage('prev')" id="btn-prev"
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="loadPage('next')" id="btn-next"
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentFilter = 'all';
            let currentPage = 1;
            let totalPages = 1;
            let allNotifications = [];

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                loadNotifications();
            });

            // Load notifications
            async function loadNotifications() {
                try {
                    document.getElementById('loading-state').classList.remove('hidden');
                    document.getElementById('empty-state').classList.add('hidden');
                    document.getElementById('notifications-list').innerHTML = '';

                    const response = await fetch('{{ route('notifications.index') }}?page=' + currentPage);
                    const data = await response.json();

                    if (data.success) {
                        allNotifications = data.notifications;
                        updateCounts(data);
                        renderNotifications();
                        updatePagination(data);
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                } finally {
                    document.getElementById('loading-state').classList.add('hidden');
                }
            }

            // Update counts
            function updateCounts(data) {
                document.getElementById('count-all').textContent = data.total || 0;
                document.getElementById('count-unread').textContent = data.unread_count || 0;
                document.getElementById('count-read').textContent = (data.total - data.unread_count) || 0;
            }

            // Filter notifications
            function filterNotifications(filter) {
                currentFilter = filter;
                currentPage = 1;

                // Update tabs UI
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('border-brand-orange', 'text-brand-orange');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                document.getElementById('tab-' + filter).classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('tab-' + filter).classList.add('border-brand-orange', 'text-brand-orange');

                renderNotifications();
            }

            // Render notifications
            function renderNotifications() {
                let filtered = allNotifications;

                if (currentFilter === 'unread') {
                    filtered = allNotifications.filter(n => !n.read_at);
                } else if (currentFilter === 'read') {
                    filtered = allNotifications.filter(n => n.read_at);
                }

                const container = document.getElementById('notifications-list');

                if (filtered.length === 0) {
                    container.innerHTML = '';
                    document.getElementById('empty-state').classList.remove('hidden');
                    return;
                }

                document.getElementById('empty-state').classList.add('hidden');

                container.innerHTML = filtered.map(notification => renderNotificationItem(notification)).join('');
            }

            // Render single notification item
            function renderNotificationItem(notification) {
                const isUnread = !notification.read_at;
                const iconClass = getIconClass(notification.type);
                const bgClass = getBgClass(notification.type);
                const timeAgo = formatTimeAgo(notification.created_at);

                return `
                    <div class="notification-item p-4 hover:bg-gray-50 transition-colors ${isUnread ? 'bg-orange-50/50' : ''}" data-id="${notification.id}">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full ${bgClass} flex items-center justify-center">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 ${isUnread ? 'font-semibold' : ''}">
                                            ${notification.title}
                                            ${isUnread ? '<span class="inline-block w-2 h-2 bg-red-500 rounded-full ml-2"></span>' : ''}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-clock mr-1"></i>${timeAgo}
                                            </span>
                                            ${notification.from_user ? `
                                                                <span class="text-xs text-gray-400">
                                                                    <i class="fas fa-user mr-1"></i>${notification.from_user.name}
                                                                </span>
                                                            ` : ''}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        ${notification.link ? `
                                                            <a href="${notification.link}" onclick="markAsRead(${notification.id})"
                                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        ` : ''}
                                        ${isUnread ? `
                                                            <button onclick="markAsRead(${notification.id})"
                                                                class="text-gray-400 hover:text-green-600 text-sm" title="Tandai dibaca">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        ` : ''}
                                        <button onclick="deleteNotification(${notification.id})"
                                            class="text-gray-400 hover:text-red-600 text-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Get icon class based on type
            function getIconClass(type) {
                const icons = {
                    'laporan_baru': 'fa-file-invoice-dollar text-blue-500',
                    'laporan_pending': 'fa-file-invoice-dollar text-blue-500',
                    'laporan_approved': 'fa-check-circle text-green-500',
                    'laporan_rejected': 'fa-times-circle text-red-500',
                    'informasi': 'fa-bullhorn text-orange-500',
                };
                return icons[type] || 'fa-bell text-gray-500';
            }

            // Get background class based on type
            function getBgClass(type) {
                const colors = {
                    'laporan_baru': 'bg-blue-100',
                    'laporan_pending': 'bg-blue-100',
                    'laporan_approved': 'bg-green-100',
                    'laporan_rejected': 'bg-red-100',
                    'informasi': 'bg-orange-100',
                };
                return colors[type] || 'bg-gray-100';
            }

            // Format time ago
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = Math.floor((now - date) / 1000);

                if (diff < 60) return 'Baru saja';
                if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
                if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
                if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';

                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Mark as read
            async function markAsRead(id) {
                try {
                    const response = await fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        // Update local data
                        const notification = allNotifications.find(n => n.id === id);
                        if (notification) {
                            notification.read_at = new Date().toISOString();
                        }
                        renderNotifications();
                        updateHeaderNotificationCount();
                    }
                } catch (error) {
                    console.error('Error marking as read:', error);
                }
            }

            // Mark all as read
            async function markAllAsRead() {
                try {
                    const response = await fetch('{{ route('notifications.read-all') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        // Update local data
                        allNotifications.forEach(n => {
                            if (!n.read_at) n.read_at = new Date().toISOString();
                        });
                        document.getElementById('count-unread').textContent = '0';
                        document.getElementById('count-read').textContent = allNotifications.length;
                        renderNotifications();
                        updateHeaderNotificationCount();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Semua notifikasi telah ditandai dibaca',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            }

            // Delete notification
            async function deleteNotification(id) {
                const result = await Swal.fire({
                    title: 'Hapus Notifikasi?',
                    text: 'Notifikasi ini akan dihapus permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/notifications/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            // Remove from local data
                            allNotifications = allNotifications.filter(n => n.id !== id);
                            renderNotifications();
                            updateHeaderNotificationCount();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Notifikasi telah dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    } catch (error) {
                        console.error('Error deleting notification:', error);
                    }
                }
            }

            // Update pagination
            function updatePagination(data) {
                if (data.total > data.per_page) {
                    document.getElementById('pagination-container').classList.remove('hidden');
                    document.getElementById('showing-from').textContent = ((data.current_page - 1) * data.per_page) + 1;
                    document.getElementById('showing-to').textContent = Math.min(data.current_page * data.per_page, data
                        .total);
                    document.getElementById('total-count').textContent = data.total;

                    document.getElementById('btn-prev').disabled = data.current_page <= 1;
                    document.getElementById('btn-next').disabled = data.current_page >= data.last_page;

                    totalPages = data.last_page;
                } else {
                    document.getElementById('pagination-container').classList.add('hidden');
                }
            }

            // Load page
            function loadPage(direction) {
                if (direction === 'prev' && currentPage > 1) {
                    currentPage--;
                } else if (direction === 'next' && currentPage < totalPages) {
                    currentPage++;
                }
                loadNotifications();
            }

            // Update header notification count
            function updateHeaderNotificationCount() {
                const unreadCount = allNotifications.filter(n => !n.read_at).length;
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
        </script>
    @endpush
@endsection
