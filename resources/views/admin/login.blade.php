@extends('layouts.app')

@section('title', 'Admin Login - Pemilihan OSIS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Admin Portal</h1>
                <p class="text-slate-600">Masuk untuk mengelola pemilihan</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        required
                        value="{{ old('username') }}"
                        class="@apply w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        placeholder="Masukkan username"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="@apply w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        placeholder="Masukkan password"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="@apply w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors mt-6"
                >
                    Login
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <p class="text-center text-sm text-slate-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Kembali ke voter login</a>
                </p>
            </div>
        </div>

        <!-- Credentials Info -->
        <div class="mt-6 text-center text-slate-600 text-sm">
            <p>Test Credentials:</p>
            <p class="font-mono text-xs mt-2">Username: <strong>admin</strong></p>
            <p class="font-mono text-xs">Password: <strong>admin123</strong></p>
        </div>
    </div>
</div>
@endsection
