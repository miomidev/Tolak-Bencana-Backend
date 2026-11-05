@extends('layouts.admin')

@section('title', 'Tambah Level Siaga')
@section('header_title', 'Tambah Level Siaga')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.alert-levels.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 max-w-3xl">
    <h3 class="text-lg font-extrabold text-[#1F2937] mb-6"><i class="fa-solid fa-gauge-high text-[#15803D] mr-2"></i> Tambah Level Siaga</h3>
    <form action="{{ route('admin.alert-levels.store') }}" method="POST">
        @csrf
        @include('admin.alert-levels._form')
    </form>
</div>
@endsection
