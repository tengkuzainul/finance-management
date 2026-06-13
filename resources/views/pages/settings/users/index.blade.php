@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-slate-500 mt-1">Kelola data pengguna sistem</p>
        </div>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
            <i class="fas fa-plus"></i>
            Tambah Pengguna
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500">Total Pengguna</p>
                        <p class="text-2xl font-bold text-slate-900">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-100">
                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500">Admin</p>
                        <p class="text-2xl font-bold text-slate-900">
                            {{ \App\Models\User::where('is_admin', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500">Pengguna Aktif</p>
                        <p class="text-2xl font-bold text-slate-900">
                            {{ \App\Models\User::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, username, atau email..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div class="sm:w-40">
                    <select name="role"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                </div>
                <div class="sm:w-40">
                    <select name="status"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                @if (request('search') || request('role') || request('status'))
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition-colors text-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                                <i class="fas fa-user text-orange-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600">{{ $user->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $user->is_admin ? 'Admin' : 'Karyawan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus('{{ $user->hash_id }}')"
                                        class="status-badge-{{ $user->id }} inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors
                                    {{ $user->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                        <span class="status-text">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.edit', $user->hash_id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-blue-100 text-blue-600 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (auth()->id() !== $user->id)
                                            <button onclick="deleteUser('{{ $user->hash_id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-red-100 text-red-600 transition-colors"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-inbox text-slate-300 text-4xl"></i>
                                        <p class="text-slate-500 font-medium">Tidak ada data pengguna</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleStatus(hashId) {
                SwalHelper.confirm('Yakin ingin mengubah status pengguna?').then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/users/${hashId}/toggle-status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    SwalHelper.success(data.message || 'Status pengguna berhasil diubah!');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    SwalHelper.error(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                SwalHelper.error('Terjadi kesalahan saat mengubah status!');
                            });
                    }
                });
            }

            function deleteUser(hashId) {
                SwalHelper.confirmDelete('pengguna ini').then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/users/${hashId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    SwalHelper.success(data.message || 'Pengguna berhasil dihapus!');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    SwalHelper.error(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                SwalHelper.error('Terjadi kesalahan saat menghapus pengguna!');
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection
