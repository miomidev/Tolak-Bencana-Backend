@extends('layouts.admin')

@section('title', 'Tambah Pemberitahuan')
@section('header_title', 'Tambah Pemberitahuan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.announcements.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 max-w-3xl">
    <h3 class="text-lg font-extrabold text-[#1F2937] mb-6"><i class="fa-solid fa-bullhorn text-[#0F767E] mr-2"></i> Tambah Pemberitahuan</h3>
    <form action="{{ route('admin.announcements.store') }}" method="POST">
        @csrf
        @include('admin.announcements._form')
    </form>
</div>
@endsection
