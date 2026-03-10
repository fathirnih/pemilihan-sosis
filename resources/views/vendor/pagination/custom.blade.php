@if ($paginator->hasPages())
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Teks Info Data --}}
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>-{{ $paginator->lastItem() }} dari <span class="font-medium text-slate-700">{{ $paginator->total() }}</span> data
        </p>

        {{-- Tombol Navigasi --}}
        <div class="flex items-center gap-2">
            
            {{-- Tombol Previous (Kiri) --}}
            @if ($paginator->onFirstPage())
                <button class="p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-400 cursor-not-allowed opacity-50" disabled>
                    <i class="w-4 h-4" data-lucide="chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="p-2 border border-slate-200 bg-white rounded-lg hover:bg-slate-50 text-slate-600 transition-all shadow-sm">
                    <i class="w-4 h-4" data-lucide="chevron-left"></i>
                </a>
            @endif

            {{-- Nomor Halaman (1, 2, 3, ...) --}}
            @foreach ($elements as $element)
                {{-- Pemisah "Titik-titik" (...) --}}
                @if (is_string($element))
                    <span class="px-3 py-1 text-sm font-medium text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Link Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{-- Halaman Aktif --}}
                            <button class="px-3 py-1 border border-indigo-200 bg-white rounded-lg text-sm font-semibold text-indigo-600 shadow-sm cursor-default">
                                {{ $page }}
                            </button>
                        @else
                            {{-- Halaman Tidak Aktif --}}
                            <a href="{{ $url }}" class="px-3 py-1 border border-transparent rounded-lg text-sm font-medium text-slate-600 hover:bg-white hover:border-slate-200 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next (Kanan) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="p-2 border border-slate-200 bg-white rounded-lg hover:bg-slate-50 text-slate-600 transition-all shadow-sm">
                    <i class="w-4 h-4" data-lucide="chevron-right"></i>
                </a>
            @else
                <button class="p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-400 cursor-not-allowed opacity-50" disabled>
                    <i class="w-4 h-4" data-lucide="chevron-right"></i>
                </button>
            @endif

        </div>
    </div>
@else
    {{-- Jika data kurang dari 10 (tidak ada paginasi), tetap tampilkan info jumlah --}}
    @if($paginator->count() > 0)
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-medium text-slate-700">{{ $paginator->count() }}</span> data
        </p>
    </div>
    @endif
@endif