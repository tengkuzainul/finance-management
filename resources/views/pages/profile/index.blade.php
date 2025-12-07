@extends('layouts.app')

@section('title', 'Profile')

@section('page-header')
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Profile</h1>
        <p class="text-slate-500 mt-1">Kelola informasi akun Anda</p>
    </div>
@endsection

@section('content')
    <div class="max-w-full">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-orange-500 to-blue-500"></div>
                    <div class="px-6 pb-6">
                        <div class="-mt-12 mb-4 relative">
                            <!-- Avatar Container -->
                            <div id="avatarContainer" class="relative inline-block">
                                @if (auth()->user()->avatar)
                                    <img id="avatarImage" src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="Avatar"
                                        class="w-24 h-24 rounded-2xl object-cover shadow-xl border-4 border-white">
                                @else
                                    <div id="avatarInitial"
                                        class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-xl shadow-blue-500/30 border-4 border-white">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <!-- Edit overlay on hover -->
                                <button type="button" onclick="openPhotoModal()"
                                    class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer border-4 border-transparent">
                                    <i class="fas fa-camera text-white text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name ?? 'User' }}</h2>
                        <p class="text-slate-500 text-sm">{{ auth()->user()->is_admin ? 'Administrator' : 'Karyawan' }}</p>
                        <p class="text-slate-500 text-sm mt-1">{{ auth()->user()->email ?? 'user@example.com' }}</p>

                        <div class="mt-6 pt-6 border-t border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 text-sm">
                                <i class="fas fa-calendar-alt text-slate-400 w-5"></i>
                                <span class="text-slate-600">Bergabung
                                    {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <i class="fas fa-shield-alt text-green-500 w-5"></i>
                                <span class="text-slate-600">Akun
                                    {{ Auth::user()->is_active ? 'Terverifikasi' : 'Belum Terverifikasi' }}</span>
                            </div>
                        </div>

                        <button type="button" onclick="openPhotoModal()"
                            class="w-full mt-6 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-camera"></i>
                            <span>Ubah Foto</span>
                        </button>
                    </div>
                </div>

                <!-- Info Cabang Card (hanya untuk karyawan) -->
                @if (!auth()->user()->is_admin && auth()->user()->karyawan)
                    @php $karyawan = auth()->user()->karyawan; @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
                        <div class="h-3 bg-gradient-to-r from-orange-500 to-orange-600"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-store text-orange-500"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">Informasi Cabang</h3>
                                    <p class="text-xs text-slate-500">Data penempatan kerja</p>
                                </div>
                            </div>

                            @if ($karyawan->cabang)
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-building text-slate-400 w-5 mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-500">Nama Cabang</p>
                                            <p class="font-semibold text-slate-800">{{ $karyawan->cabang->nama_cabang }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-hashtag text-slate-400 w-5 mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-500">Kode Cabang</p>
                                            <p class="font-semibold text-slate-800">{{ $karyawan->cabang->kode_cabang }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-map-marker-alt text-slate-400 w-5 mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-500">Alamat</p>
                                            <p class="font-medium text-slate-700 text-sm">
                                                {{ $karyawan->cabang->alamat_lengkap ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-phone text-slate-400 w-5 mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-500">Telepon Cabang</p>
                                            <p class="font-medium text-slate-700">
                                                {{ $karyawan->cabang->no_telepon ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-circle text-slate-400 w-5 mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-500">Status Cabang</p>
                                            @if ($karyawan->cabang->is_active)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                    <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Karyawan -->
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <h4 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                        <i class="fas fa-id-card text-orange-500"></i>
                                        Data Karyawan
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-slate-500">NIK</p>
                                            <p class="font-medium text-slate-700">{{ $karyawan->nik ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Status</p>
                                            @if ($karyawan->is_active)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tidak
                                                    Aktif</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Jenis Kelamin</p>
                                            <p class="font-medium text-slate-700">{{ $karyawan->jenis_kelamin ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">No. Telepon</p>
                                            <p class="font-medium text-slate-700">{{ $karyawan->no_telepon ?? '-' }}</p>
                                        </div>
                                    </div>
                                    @if ($karyawan->alamat)
                                        <div class="mt-4">
                                            <p class="text-xs text-slate-500">Alamat</p>
                                            <p class="font-medium text-slate-700 text-sm">{{ $karyawan->alamat }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-exclamation-triangle text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 text-sm">Belum ditempatkan di cabang</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">Informasi Personal</h3>
                        <p class="text-sm text-slate-500">Update informasi profil Anda</p>
                    </div>
                    <form id="formProfile" class="p-6 space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ auth()->user()->name ?? 'User' }}"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" name="email"
                                    value="{{ auth()->user()->email ?? 'user@example.com' }}"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nomor Telepon</label>
                                <input type="tel" name="phone" placeholder="+62 xxx xxxx xxxx"
                                    value="{{ Auth::user()->karyawan?->no_telepon ?? '' }}"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                                <input type="text" value="{{ Auth::user()->is_admin ? 'Administrator' : 'Karyawan' }}"
                                    disabled
                                    class="w-full px-4 py-3 bg-slate-200 border border-slate-400 rounded-xl text-slate-500 cursor-not-allowed">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="btnSaveProfile"
                                class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/30 transition-all duration-200 inline-flex items-center gap-2">
                                <span class="icon"><i class="fas fa-save"></i></span>
                                <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                                <span class="text">Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">Ubah Password</h3>
                        <p class="text-sm text-slate-500">Pastikan akun Anda menggunakan password yang kuat</p>
                    </div>
                    <form id="formPassword" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="btnUpdatePassword"
                                class="px-6 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-900 transition-colors inline-flex items-center gap-2">
                                <span class="icon"><i class="fas fa-key"></i></span>
                                <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                                <span class="text">Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tanda Tangan Digital -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Tanda Tangan Digital</h3>
                                <p class="text-sm text-slate-500">Tanda tangan untuk dokumen resmi (Slip Gaji, Laporan,
                                    dll)</p>
                            </div>
                            <button type="button" onclick="openSignatureModal()"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                <i class="fas fa-pen-fancy mr-2"></i>
                                {{ auth()->user()->ttd ? 'Ubah TTD' : 'Buat TTD' }}
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        @if (auth()->user()->ttd)
                            <div class="flex items-center gap-6">
                                <div class="flex-1">
                                    <p class="text-sm text-slate-500 mb-3">Tanda tangan Anda saat ini:</p>
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                        <img src="{{ auth()->user()->ttd }}" alt="Tanda Tangan"
                                            class="max-h-24 mx-auto">
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <button type="button" onclick="openSignatureModal()"
                                        class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-xl hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Ubah
                                    </button>
                                    <button type="button" onclick="removeSignature()"
                                        class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-xl hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-signature text-slate-400 text-2xl"></i>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada tanda tangan</p>
                                <p class="text-sm text-slate-400 mt-1">Buat tanda tangan digital Anda untuk dokumen resmi
                                </p>
                                <button type="button" onclick="openSignatureModal()"
                                    class="mt-4 px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                    <i class="fas fa-pen-fancy mr-2"></i>
                                    Buat Tanda Tangan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Modal (Off-canvas) -->
    <div id="signatureModal" class="fixed inset-0 z-50" style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60" onclick="closeSignatureModal()"></div>

        <!-- Off-canvas Content -->
        <div id="signatureCanvas"
            class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl transform translate-x-full transition-transform duration-300">
            <!-- Header -->
            <div
                class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="text-white">
                    <h3 class="font-bold text-lg">{{ auth()->user()->ttd ? 'Ubah Tanda Tangan' : 'Buat Tanda Tangan' }}
                    </h3>
                    <p class="text-blue-100 text-sm">Gambar tanda tangan Anda di area canvas</p>
                </div>
                <button onclick="closeSignatureModal()" class="text-white/80 hover:text-white p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Canvas Area -->
            <div class="p-6">
                <div class="bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 p-4 mb-4">
                    <canvas id="ttdCanvas" class="w-full bg-white rounded-lg border border-slate-200 cursor-crosshair"
                        style="height: 200px; touch-action: none;"></canvas>
                </div>

                <!-- Color & Tools -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500">Warna:</span>
                        <div class="flex gap-2">
                            <button type="button" onclick="setSignatureColor('#000000')"
                                class="w-8 h-8 rounded-full bg-black border-2 border-slate-300 hover:scale-110 transition-transform signature-color"
                                data-color="#000000"></button>
                            <button type="button" onclick="setSignatureColor('#1e40af')"
                                class="w-8 h-8 rounded-full bg-blue-800 border-2 border-slate-300 hover:scale-110 transition-transform signature-color ring-2 ring-offset-2 ring-blue-500"
                                data-color="#1e40af"></button>
                            <button type="button" onclick="setSignatureColor('#166534')"
                                class="w-8 h-8 rounded-full bg-green-800 border-2 border-slate-300 hover:scale-110 transition-transform signature-color"
                                data-color="#166534"></button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">Ketebalan:</span>
                        <input type="range" id="strokeWidth" min="1" max="5" value="3"
                            class="w-20" onchange="setStrokeWidth(this.value)">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="clearSignatureCanvas()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                        <i class="fas fa-eraser mr-2"></i>
                        Hapus
                    </button>
                    <button type="button" onclick="saveSignature()" id="btnSaveSignature"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        <span class="text">Simpan TTD</span>
                    </button>
                </div>

                <!-- Tips -->
                <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-amber-500 mt-0.5"></i>
                        <div class="text-sm text-amber-700">
                            <p class="font-medium">Tips:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Gunakan mouse atau sentuh layar untuk menggambar</li>
                                <li>Tanda tangan akan digunakan di slip gaji dan laporan</li>
                                <li>Pastikan tanda tangan jelas dan mudah dibaca</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Upload Modal -->
    <div id="photoModal" class="fixed inset-0 z-50 items-center justify-center p-4" style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60" onclick="closePhotoModal()"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Ubah Foto Profil</h3>
                <button onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Preview Area -->
                <div id="previewArea" class="hidden mb-6">
                    <div class="relative">
                        <img id="photoPreview" class="w-full max-h-64 object-contain rounded-xl border border-slate-200">
                        <button type="button" onclick="clearPreview()"
                            class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Camera View -->
                <div id="cameraView" class="hidden mb-6">
                    <div class="relative">
                        <video id="cameraStream" autoplay playsinline
                            class="w-full rounded-xl border border-slate-200"></video>
                        <canvas id="cameraCanvas" class="hidden"></canvas>
                    </div>
                    <div class="flex justify-center gap-3 mt-4">
                        <button type="button" onclick="capturePhoto()"
                            class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 inline-flex items-center gap-2">
                            <i class="fas fa-camera"></i>
                            <span>Ambil Foto</span>
                        </button>
                        <button type="button" onclick="stopCamera()"
                            class="px-6 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 inline-flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </button>
                    </div>
                </div>

                <!-- Options (shown when no preview/camera) -->
                <div id="optionsArea">
                    <p class="text-sm text-slate-500 text-center mb-6">Pilih sumber foto</p>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- File Explorer -->
                        <button type="button" onclick="document.getElementById('fileInput').click()"
                            class="p-6 border-2 border-dashed border-slate-200 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all group">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-200">
                                    <i class="fas fa-folder-open text-orange-500 text-xl"></i>
                                </div>
                                <p class="font-medium text-slate-700">File</p>
                                <p class="text-xs text-slate-400 mt-1">Pilih dari perangkat</p>
                            </div>
                        </button>

                        <!-- Camera -->
                        <button type="button" onclick="startCamera()"
                            class="p-6 border-2 border-dashed border-slate-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all group">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200">
                                    <i class="fas fa-camera text-blue-500 text-xl"></i>
                                </div>
                                <p class="font-medium text-slate-700">Kamera</p>
                                <p class="text-xs text-slate-400 mt-1">Ambil foto baru</p>
                            </div>
                        </button>
                    </div>

                    <input type="file" id="fileInput" accept="image/*" class="hidden"
                        onchange="handleFileSelect(this)">
                </div>

                <!-- Upload Button -->
                <div id="uploadArea" class="hidden mt-6">
                    <button type="button" onclick="uploadPhoto()" id="btnUpload"
                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 inline-flex items-center justify-center gap-2">
                        <span class="icon"><i class="fas fa-upload"></i></span>
                        <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="text">Upload Foto</span>
                    </button>
                </div>

                <!-- Remove Photo Option -->
                @if (auth()->user()->avatar)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <button type="button" onclick="removePhoto()"
                            class="w-full py-2.5 text-red-500 text-sm font-medium hover:bg-red-50 rounded-xl transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Foto Profil
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedFile = null;
        let cameraStream = null;

        // Open photo modal
        function openPhotoModal() {
            document.getElementById('photoModal').style.display = 'flex';
            resetModal();
        }

        // Close photo modal
        function closePhotoModal() {
            document.getElementById('photoModal').style.display = 'none';
            stopCamera();
            resetModal();
        }

        // Reset modal to initial state
        function resetModal() {
            document.getElementById('previewArea').classList.add('hidden');
            document.getElementById('cameraView').classList.add('hidden');
            document.getElementById('optionsArea').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
            selectedFile = null;
        }

        // Handle file selection
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    Swal.fire('Error', 'Pilih file gambar yang valid', 'error');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'Ukuran file maksimal 2MB', 'error');
                    return;
                }

                selectedFile = file;
                showPreview(file);
            }
        }

        // Show image preview
        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                document.getElementById('previewArea').classList.remove('hidden');
                document.getElementById('optionsArea').classList.add('hidden');
                document.getElementById('uploadArea').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        // Clear preview
        function clearPreview() {
            resetModal();
            document.getElementById('fileInput').value = '';
        }

        // Start camera
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        }
                    }
                });

                cameraStream = stream;
                const video = document.getElementById('cameraStream');
                video.srcObject = stream;

                document.getElementById('optionsArea').classList.add('hidden');
                document.getElementById('cameraView').classList.remove('hidden');
            } catch (error) {
                console.error('Camera error:', error);
                Swal.fire('Error', 'Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.', 'error');
            }
        }

        // Stop camera
        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            document.getElementById('cameraView').classList.add('hidden');
            document.getElementById('optionsArea').classList.remove('hidden');
        }

        // Capture photo from camera
        function capturePhoto() {
            const video = document.getElementById('cameraStream');
            const canvas = document.getElementById('cameraCanvas');
            const ctx = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            // Convert canvas to blob
            canvas.toBlob(function(blob) {
                selectedFile = new File([blob], 'camera-photo.jpg', {
                    type: 'image/jpeg'
                });

                // Show preview
                document.getElementById('photoPreview').src = canvas.toDataURL('image/jpeg');
                document.getElementById('previewArea').classList.remove('hidden');
                document.getElementById('cameraView').classList.add('hidden');
                document.getElementById('uploadArea').classList.remove('hidden');

                // Stop camera
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }
            }, 'image/jpeg', 0.9);
        }

        // Upload photo
        async function uploadPhoto() {
            if (!selectedFile) {
                Swal.fire('Error', 'Pilih foto terlebih dahulu', 'error');
                return;
            }

            const btn = document.getElementById('btnUpload');
            const spinner = btn.querySelector('.spinner');
            const icon = btn.querySelector('.icon');
            const text = btn.querySelector('.text');

            // Show loading
            btn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            text.textContent = 'Mengupload...';

            const formData = new FormData();
            formData.append('avatar', selectedFile);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('profile.avatar.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (error) {
                console.error('Upload error:', error);
                Swal.fire('Gagal', 'Terjadi kesalahan saat mengupload foto', 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                icon.classList.remove('hidden');
                text.textContent = 'Upload Foto';
            }
        }

        // Remove photo
        async function removePhoto() {
            const result = await Swal.fire({
                title: 'Hapus Foto Profil?',
                text: 'Foto profil akan dihapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route('profile.avatar.remove') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (error) {
                    console.error('Remove error:', error);
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus foto', 'error');
                }
            }
        }

        // Profile form submit
        document.getElementById('formProfile').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnSaveProfile');
            const spinner = btn.querySelector('.spinner');
            const icon = btn.querySelector('.icon');
            const text = btn.querySelector('.text');

            btn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            text.textContent = 'Menyimpan...';

            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch('{{ route('profile.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                icon.classList.remove('hidden');
                text.textContent = 'Simpan Perubahan';
            }
        });

        // Password form submit
        document.getElementById('formPassword').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnUpdatePassword');
            const spinner = btn.querySelector('.spinner');
            const icon = btn.querySelector('.icon');
            const text = btn.querySelector('.text');

            btn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            text.textContent = 'Memperbarui...';

            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch('{{ route('profile.password.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.reset();
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                icon.classList.remove('hidden');
                text.textContent = 'Update Password';
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhotoModal();
                closeSignatureModal();
            }
        });

        // =====================================================
        // SIGNATURE CANVAS FUNCTIONALITY WITH SMOOTH LINES
        // =====================================================
        let signatureCanvas, signatureCtx;
        let isDrawing = false;
        let points = [];
        let strokeColor = '#1e40af'; // Default blue
        let strokeWidth = 3;

        // Initialize signature canvas
        function initSignatureCanvas() {
            signatureCanvas = document.getElementById('ttdCanvas');
            if (!signatureCanvas) return;

            signatureCtx = signatureCanvas.getContext('2d');

            // Set canvas size
            const container = signatureCanvas.parentElement;
            signatureCanvas.width = container.clientWidth - 32;
            signatureCanvas.height = 200;

            // Clear and set background
            signatureCtx.fillStyle = '#ffffff';
            signatureCtx.fillRect(0, 0, signatureCanvas.width, signatureCanvas.height);

            // Remove old event listeners if any
            signatureCanvas.removeEventListener('mousedown', startDrawing);
            signatureCanvas.removeEventListener('mousemove', draw);
            signatureCanvas.removeEventListener('mouseup', stopDrawing);
            signatureCanvas.removeEventListener('mouseout', stopDrawing);
            signatureCanvas.removeEventListener('touchstart', handleTouchStart);
            signatureCanvas.removeEventListener('touchmove', handleTouchMove);
            signatureCanvas.removeEventListener('touchend', stopDrawing);

            // Mouse events
            signatureCanvas.addEventListener('mousedown', startDrawing);
            signatureCanvas.addEventListener('mousemove', draw);
            signatureCanvas.addEventListener('mouseup', stopDrawing);
            signatureCanvas.addEventListener('mouseout', stopDrawing);

            // Touch events for mobile
            signatureCanvas.addEventListener('touchstart', handleTouchStart);
            signatureCanvas.addEventListener('touchmove', handleTouchMove);
            signatureCanvas.addEventListener('touchend', stopDrawing);
        }

        function getPosition(e) {
            const rect = signatureCanvas.getBoundingClientRect();
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            points = [];
            const pos = getPosition(e);
            points.push(pos);
        }

        function draw(e) {
            if (!isDrawing) return;

            const pos = getPosition(e);
            points.push(pos);

            // Draw smooth curve using quadratic bezier
            signatureCtx.beginPath();
            signatureCtx.strokeStyle = strokeColor;
            signatureCtx.lineWidth = strokeWidth;
            signatureCtx.lineCap = 'round';
            signatureCtx.lineJoin = 'round';

            if (points.length < 3) {
                // Not enough points, draw a line
                const firstPoint = points[0];
                const lastPoint = points[points.length - 1];
                signatureCtx.moveTo(firstPoint.x, firstPoint.y);
                signatureCtx.lineTo(lastPoint.x, lastPoint.y);
            } else {
                // Draw smooth curve
                signatureCtx.moveTo(points[0].x, points[0].y);

                for (let i = 1; i < points.length - 2; i++) {
                    const xc = (points[i].x + points[i + 1].x) / 2;
                    const yc = (points[i].y + points[i + 1].y) / 2;
                    signatureCtx.quadraticCurveTo(points[i].x, points[i].y, xc, yc);
                }

                // Last 2 points
                const lastIdx = points.length - 1;
                signatureCtx.quadraticCurveTo(
                    points[lastIdx - 1].x,
                    points[lastIdx - 1].y,
                    points[lastIdx].x,
                    points[lastIdx].y
                );
            }

            signatureCtx.stroke();
        }

        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                points = [];
            }
        }

        function handleTouchStart(e) {
            e.preventDefault();
            const touch = e.touches[0];
            isDrawing = true;
            points = [];
            const rect = signatureCanvas.getBoundingClientRect();
            points.push({
                x: touch.clientX - rect.left,
                y: touch.clientY - rect.top
            });
        }

        function handleTouchMove(e) {
            e.preventDefault();
            if (!isDrawing) return;

            const touch = e.touches[0];
            const rect = signatureCanvas.getBoundingClientRect();
            const pos = {
                x: touch.clientX - rect.left,
                y: touch.clientY - rect.top
            };
            points.push(pos);

            // Draw smooth curve
            signatureCtx.beginPath();
            signatureCtx.strokeStyle = strokeColor;
            signatureCtx.lineWidth = strokeWidth;
            signatureCtx.lineCap = 'round';
            signatureCtx.lineJoin = 'round';

            if (points.length < 3) {
                const firstPoint = points[0];
                const lastPoint = points[points.length - 1];
                signatureCtx.moveTo(firstPoint.x, firstPoint.y);
                signatureCtx.lineTo(lastPoint.x, lastPoint.y);
            } else {
                signatureCtx.moveTo(points[0].x, points[0].y);

                for (let i = 1; i < points.length - 2; i++) {
                    const xc = (points[i].x + points[i + 1].x) / 2;
                    const yc = (points[i].y + points[i + 1].y) / 2;
                    signatureCtx.quadraticCurveTo(points[i].x, points[i].y, xc, yc);
                }

                const lastIdx = points.length - 1;
                signatureCtx.quadraticCurveTo(
                    points[lastIdx - 1].x,
                    points[lastIdx - 1].y,
                    points[lastIdx].x,
                    points[lastIdx].y
                );
            }

            signatureCtx.stroke();
        }

        function setSignatureColor(color) {
            strokeColor = color;
            // Update active state
            document.querySelectorAll('.signature-color').forEach(btn => {
                btn.classList.remove('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');
                if (btn.dataset.color === color) {
                    btn.classList.add('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');
                }
            });
        }

        function setStrokeWidth(width) {
            strokeWidth = parseInt(width);
        }

        // Initialize default color on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default color to blue
            const defaultColorBtn = document.querySelector('.signature-color[data-color="#1e40af"]');
            if (defaultColorBtn) {
                defaultColorBtn.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
            }
        });

        function clearSignatureCanvas() {
            if (signatureCtx) {
                signatureCtx.fillStyle = '#ffffff';
                signatureCtx.fillRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            }
        }

        function openSignatureModal() {
            const modal = document.getElementById('signatureModal');
            const canvas = document.getElementById('signatureCanvas');
            modal.style.display = 'block';

            setTimeout(() => {
                canvas.classList.remove('translate-x-full');
                initSignatureCanvas();
            }, 10);
        }

        function closeSignatureModal() {
            const modal = document.getElementById('signatureModal');
            const canvas = document.getElementById('signatureCanvas');
            canvas.classList.add('translate-x-full');

            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        async function saveSignature() {
            if (!signatureCanvas) return;

            // Check if canvas is empty
            const ctx = signatureCanvas.getContext('2d');
            const imageData = ctx.getImageData(0, 0, signatureCanvas.width, signatureCanvas.height);
            const data = imageData.data;
            let isEmpty = true;

            for (let i = 0; i < data.length; i += 4) {
                // Check if pixel is not white
                if (data[i] !== 255 || data[i + 1] !== 255 || data[i + 2] !== 255) {
                    isEmpty = false;
                    break;
                }
            }

            if (isEmpty) {
                Swal.fire('Error', 'Silakan gambar tanda tangan Anda terlebih dahulu', 'error');
                return;
            }

            const btn = document.getElementById('btnSaveSignature');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';

            try {
                const signatureData = signatureCanvas.toDataURL('image/png');

                const response = await fetch('{{ route('profile.signature.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        signature: signatureData
                    })
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal', result.message, 'error');
                }
            } catch (error) {
                console.error('Error saving signature:', error);
                Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan tanda tangan', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save mr-2"></i> <span class="text">Simpan TTD</span>';
            }
        }

        async function removeSignature() {
            const result = await Swal.fire({
                title: 'Hapus Tanda Tangan?',
                text: 'Tanda tangan akan dihapus secara permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route('profile.signature.remove') }}', {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus tanda tangan', 'error');
                }
            }
        }
    </script>
@endpush
