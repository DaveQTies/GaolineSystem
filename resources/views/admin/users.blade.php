@extends('layouts.app', ['active' => 'admin'])

@section('title', 'User Management')
@section('header_title', 'User Management')
@section('header_subtitle', 'Manage system users and permissions')

@section('content')
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
        <h3 class="text-xl font-black text-slate-800 mb-2">User Management</h3>
        <p class="text-sm text-slate-500 mb-6">User tools are available on the admin dashboard.</p>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl text-xs font-black transition-all">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
@endsection
