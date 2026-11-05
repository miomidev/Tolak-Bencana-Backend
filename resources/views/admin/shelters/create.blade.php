@extends('layouts.admin')

@section('title', 'Tambah Shelter')
@section('header_title', 'Tambah Shelter')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.shelters.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
    <h3 class="text-lg font-extrabold text-[#1F2937] mb-6"><i class="fa-solid fa-house-chimney-medical text-[#15803D] mr-2"></i> Tambah Shelter Evakuasi</h3>
    <form action="{{ route('admin.shelters.store') }}" method="POST">
        @csrf
        @include('admin.shelters._form')
    </form>
</div>
@endsection
