@extends('layouts.app')

@section('title', 'Detail Informasi - ' . $informasi->judul)

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                @if (auth()->user()->is_admin)
                    <a href="{{ route('settings.index', ['tab' => 'informasi']) }}"
                        class="hover:text-orange-600 transition-colors">Pengaturan</a>
                @else
                    <a href="{{ route('notifications.all') }}" class="hover:text-orange-600 transition-colors">Notifikasi</a>
                @endif
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-700">Detail Informasi</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Detail Informasi</h1>
        </div>
        @if (auth()->user()->is_admin)
            <a href="{{ route('settings.index', ['tab' => 'informasi']) }}"
                class="bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors px-4 py-2 shadow-sm shadow-slate-200 gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div class="max-w-full mx-auto">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header with gradient -->
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-amber-50">
                <div class="flex items-start gap-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 shrink-0">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-orange-600 font-mono mb-1">{{ $informasi->kode_informasi }}</p>
                        <h2 class="text-xl font-bold text-slate-800">{{ $informasi->judul }}</h2>
                        <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-user"></i>
                                {{ $informasi->creator->name ?? 'Admin' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar"></i>
                                {{ $informasi->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Deskripsi</h3>
                <div class="prose prose-slate max-w-none">
                    <p class="text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $informasi->deskripsi }}</p>
                </div>

                @if ($informasi->lampiran)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Lampiran</h3>
                        @php
                            $extension = strtolower(pathinfo($informasi->lampiran, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $isPdf = $extension === 'pdf';
                            $fileUrl = Storage::url($informasi->lampiran);
                            $icon = match ($extension) {
                                'pdf' => 'fa-file-pdf text-red-500',
                                'doc', 'docx' => 'fa-file-word text-blue-500',
                                'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                'jpg', 'jpeg', 'png', 'gif', 'webp' => 'fa-file-image text-purple-500',
                                default => 'fa-file text-slate-500',
                            };
                        @endphp

                        {{-- File Info Card --}}
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl mb-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas {{ $icon }} text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800">{{ basename($informasi->lampiran) }}</p>
                                <p class="text-sm text-slate-500 uppercase">{{ $extension }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($isPdf)
                                    <a href="{{ $fileUrl }}" target="_blank"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors shadow-sm">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Buka Tab Baru
                                    </a>
                                @endif
                                @if ($isImage)
                                    <button onclick="previewImage('{{ $fileUrl }}')"
                                        class="px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-slate-100 transition-colors shadow-sm">
                                        <i class="fas fa-eye mr-2"></i>
                                        Preview
                                    </button>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank" download
                                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors shadow-sm shadow-orange-500/30">
                                    <i class="fas fa-download mr-2"></i>
                                    Download
                                </a>
                            </div>
                        </div>

                        {{-- Inline Preview --}}
                        @if ($isPdf)
                            {{-- PDF Preview --}}
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                                <div
                                    class="px-4 py-3 bg-slate-100 border-b border-slate-200 flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">
                                        <i class="fas fa-file-pdf text-red-500 mr-2"></i>Preview Dokumen PDF
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button onclick="toggleFullscreen()"
                                            class="text-sm text-slate-500 hover:text-slate-700">
                                            <i class="fas fa-expand mr-1"></i>Fullscreen
                                        </button>
                                    </div>
                                </div>
                                <div id="pdfContainer" class="relative" style="height: 700px;">
                                    <iframe id="pdfViewer"
                                        src="{{ $fileUrl }}#toolbar=1&navpanes=0&scrollbar=1&view=FitH"
                                        class="w-full h-full border-0" type="application/pdf" title="Preview PDF">
                                        <p class="text-center py-8 text-slate-500">
                                            Browser Anda tidak mendukung preview PDF.
                                            <a href="{{ $fileUrl }}" target="_blank"
                                                class="text-orange-500 hover:underline">Klik di sini</a> untuk membuka.
                                        </p>
                                    </iframe>
                                </div>
                            </div>
                        @elseif ($isImage)
                            {{-- Image Preview --}}
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white p-4">
                                <img src="{{ $fileUrl }}" alt="Preview Lampiran"
                                    class="max-w-full max-h-[600px] mx-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                    onclick="previewImage('{{ $fileUrl }}')">
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @if (auth()->user()->is_admin)
                <!-- Admin Actions -->
                <div class="p-6 border-t border-slate-100 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-500">
                            <span>Terakhir diperbarui: {{ $informasi->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="openEditModal()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors shadow-sm shadow-blue-500/30">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </button>
                            <form action="{{ route('settings.informasi.destroy', $informasi->hashid) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors shadow-sm shadow-red-500/30">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="relative max-w-4xl max-h-[90vh] p-4">
            <button onclick="closeImagePreview()"
                class="absolute -top-2 -right-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-slate-600 hover:text-slate-800">
                <i class="fas fa-times"></i>
            </button>
            <img id="previewImage" src="" alt="Preview"
                class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain">
        </div>
    </div>

    @if (auth()->user()->is_admin)
        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800">Edit Informasi</h3>
                        <button onclick="closeEditModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('settings.informasi.update', $informasi->hashid) }}" method="POST"
                    enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Judul -->
                    <div>
                        <label for="edit_judul" class="block text-sm font-medium text-slate-700 mb-2">
                            Judul Informasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="edit_judul" value="{{ $informasi->judul }}"
                            class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200"
                            required>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-medium text-slate-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="6"
                            class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200 resize-none"
                            required>{{ $informasi->deskripsi }}</textarea>
                    </div>

                    <!-- Current Lampiran -->
                    @if ($informasi->lampiran)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Lampiran Saat Ini</label>
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                                <i class="fas fa-paperclip text-slate-400"></i>
                                <span class="text-sm text-slate-600 flex-1">{{ basename($informasi->lampiran) }}</span>
                                <button type="button" onclick="removeLampiran()"
                                    class="text-red-500 hover:text-red-600 text-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- New Lampiran -->
                    <div>
                        <label for="edit_lampiran" class="block text-sm font-medium text-slate-700 mb-2">
                            {{ $informasi->lampiran ? 'Ganti Lampiran' : 'Lampiran' }}
                            <span class="text-slate-400">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="lampiran" id="edit_lampiran"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden"
                                onchange="updateEditFileName(this)">
                            <label for="edit_lampiran"
                                class="flex items-center gap-3 px-4 py-3 bg-slate-100 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 hover:border-orange-400 transition-all duration-200">
                                <i class="fas fa-cloud-upload-alt text-slate-400"></i>
                                <span id="edit-file-name" class="text-sm text-slate-500">Pilih file baru</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/30 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function previewImage(url) {
            document.getElementById('previewImage').src = url;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
            document.getElementById('imagePreviewModal').classList.add('flex');
        }

        function closeImagePreview() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
            document.getElementById('imagePreviewModal').classList.remove('flex');
        }

        // PDF Fullscreen toggle
        let isFullscreen = false;

        function toggleFullscreen() {
            const container = document.getElementById('pdfContainer');
            const iframe = document.getElementById('pdfViewer');

            if (!container) return;

            if (!isFullscreen) {
                // Enter fullscreen
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
                isFullscreen = true;
            } else {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                isFullscreen = false;
            }
        }

        // Handle fullscreen change
        document.addEventListener('fullscreenchange', function() {
            isFullscreen = !!document.fullscreenElement;
        });

        @if (auth()->user()->is_admin)

            function openEditModal() {
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
            }

            function updateEditFileName(input) {
                const fileName = input.files[0]?.name || 'Pilih file baru';
                document.getElementById('edit-file-name').textContent = fileName;
            }

            function removeLampiran() {
                if (confirm('Yakin ingin menghapus lampiran ini?')) {
                    fetch('{{ route('settings.informasi.lampiran.remove', $informasi->hashid) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            }

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });

            // Close modal on background click
            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        @endif

        // Close image preview on escape or click
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImagePreview();
            }
        });
        document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });
    </script>
@endpush
