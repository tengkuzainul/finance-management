@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name ?? 'User' }}! 👋</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center py-16">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 max-w-md text-center">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user-slash text-orange-500 text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Data Karyawan Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-6">
                Akun Anda belum terhubung dengan data karyawan. Silakan hubungi administrator untuk mengatur profil karyawan
                Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
@endsection
