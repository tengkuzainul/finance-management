@extends('layouts.app')

@section('title', $informasi->judul)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('karyawan.informasi.index') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 line-clamp-1">{{ $informasi->judul }}</h1>
                <p class="text-gray-600 mt-1">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $informasi->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bullhorn text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold text-blue-800">{{ $informasi->judul }}</h2>
                        <p class="text-sm text-blue-600">Informasi dari Manajemen</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="prose prose-slate max-w-none">
                    {!! nl2br(e($informasi->deskripsi)) !!}
                </div>
            </div>

            <!-- Lampiran -->
            @if ($informasi->lampiran)
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">
                        <i class="fas fa-paperclip mr-1"></i> Lampiran
                    </h3>

                    @php
                        $extension = pathinfo($informasi->lampiran, PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp

                    @if ($isPdf)
                        <!-- PDF Viewer -->
                        <div class="rounded-lg border border-slate-200 overflow-hidden bg-white">
                            <iframe src="{{ Storage::url($informasi->lampiran) }}" class="w-full h-[500px]"
                                frameborder="0"></iframe>
                        </div>
                        <div class="mt-3">
                            <a href="{{ Storage::url($informasi->lampiran) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Buka di Tab Baru</span>
                            </a>
                            <a href="{{ Storage::url($informasi->lampiran) }}" download
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors ml-2">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    @elseif ($isImage)
                        <!-- Image Viewer -->
                        <div class="rounded-lg border border-slate-200 overflow-hidden bg-white">
                            <img src="{{ Storage::url($informasi->lampiran) }}" alt="Lampiran"
                                class="w-full max-h-[500px] object-contain">
                        </div>
                        <div class="mt-3">
                            <a href="{{ Storage::url($informasi->lampiran) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Buka di Tab Baru</span>
                            </a>
                            <a href="{{ Storage::url($informasi->lampiran) }}" download
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors ml-2">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    @else
                        <!-- Other Files -->
                        <div class="flex items-center gap-3 p-4 bg-white rounded-lg border border-slate-200">
                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-slate-500 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-700">{{ basename($informasi->lampiran) }}</p>
                                <p class="text-sm text-slate-500">File Lampiran</p>
                            </div>
                            <a href="{{ Storage::url($informasi->lampiran) }}" download
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('karyawan.informasi.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Informasi</span>
            </a>
        </div>
    </div>
@endsection
