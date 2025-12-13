@extends('layouts.app')

@section('title', 'Informasi Manajemen')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Informasi Manajemen</h1>
                <p class="text-gray-600 mt-1">Informasi dan pengumuman dari manajemen</p>
            </div>
        </div>

        <!-- Informasi List -->
        @if ($informasiList->count() > 0)
            <div class="space-y-4">
                @foreach ($informasiList as $informasi)
                    <a href="{{ route('karyawan.informasi.show', $informasi->hash_id) }}"
                        class="block bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg hover:border-blue-300 transition-all duration-200 group">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-bullhorn text-blue-600 text-lg"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-lg font-semibold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $informasi->judul }}
                                </h3>
                                <p class="text-slate-600 text-sm mt-1 line-clamp-2">
                                    {{ Str::limit(strip_tags($informasi->deskripsi), 150) }}
                                </p>
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                        <i class="fas fa-calendar"></i>
                                        {{ $informasi->created_at->format('d M Y') }}
                                    </span>
                                    @if ($informasi->lampiran)
                                        <span class="inline-flex items-center gap-1.5 text-xs text-blue-500">
                                            <i class="fas fa-paperclip"></i>
                                            Ada Lampiran
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Arrow -->
                            <div
                                class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition-colors">
                                <i class="fas fa-chevron-right text-slate-400 group-hover:text-blue-600 text-sm"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $informasiList->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Belum ada informasi</h3>
                <p class="text-slate-500">Tidak ada informasi atau pengumuman dari manajemen saat ini.</p>
            </div>
        @endif
    </div>
@endsection
