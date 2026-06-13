@extends('layouts.app')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">{{ $user->name }}</h1>
            <p class="text-slate-500 mt-1">Detail informasi pengguna</p>
        </div>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header with Avatar -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 flex items-center gap-4">
                @if ($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                        class="w-20 h-20 rounded-2xl object-cover border-4 border-white">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center">
                        <i class="fas fa-user text-orange-600 text-3xl"></i>
                    </div>
                @endif
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-orange-100">{{ $user->username }}</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <p class="text-slate-900 break-all">{{ $user->email }}</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                        <p class="text-slate-900">{{ $user->username }}</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $user->is_admin ? 'Administrator' : 'Karyawan' }}
                        </span>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <!-- Created -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Dibuat</label>
                        <p class="text-slate-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Updated -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Diperbarui</label>
                        <p class="text-slate-900">
                            {{ $user->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    @if (auth()->id() !== $user->id)
                        <a href="{{ route('users.edit', $user->hash_id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        <button onclick="deleteUser('{{ $user->hash_id }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    @else
                        <p class="text-sm text-slate-500 italic">Ini adalah akun Anda</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
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
                                        window.location.href = '{{ route('users.index') }}';
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
