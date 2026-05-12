@extends('front.layout')

@section('content')

    <!-- Banner Promo Kecil -->
    <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            <p class="text-xs font-medium text-indigo-800">Update Stok Setiap Hari!</p>
        </div>
    </div>

    <!-- Filter/Kategori Cepat (Horizontal Scroll) -->
    <div class="py-4 px-4 overflow-x-auto no-scrollbar flex gap-2 border-b border-gray-100">
        <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-1.5 {{ !request('filter') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 border border-gray-200' }} text-xs font-semibold rounded-full transition">Semua</a>
        <a href="{{ route('home', ['filter' => 'ready']) }}" class="whitespace-nowrap px-4 py-1.5 {{ request('filter') == 'ready' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 border border-gray-200' }} text-xs font-semibold rounded-full transition">Ready Stock</a>
        <a href="{{ route('home', ['filter' => 'po']) }}" class="whitespace-nowrap px-4 py-1.5 {{ request('filter') == 'po' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 border border-gray-200' }} text-xs font-semibold rounded-full transition">Pre-Order</a>
    </div>

    <!-- Info Hasil Pencarian -->
    @if(request('q'))
        <div class="px-4 pt-4 pb-1 flex justify-between items-center">
            <h2 class="text-sm font-medium text-gray-600">
                Hasil pencarian: <span class="font-bold text-gray-900">"{{ request('q') }}"</span>
            </h2>
            <a href="{{ route('home') }}" class="text-xs text-indigo-600 font-medium hover:underline">Hapus Filter</a>
        </div>
    @endif

    <!-- Daftar Katalog -->
    <div class="p-4">
        @if(!request('q'))
            <h1 class="text-lg font-bold text-gray-900 mb-4">Katalog HP Pilihan</h1>
        @endif

        @if($products->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-900">Oops, tidak ditemukan!</h3>
                <p class="text-xs text-gray-500 mt-1">
                    @if(request('q') || request('filter'))
                        Coba gunakan kata kunci atau filter lain.
                    @else
                        Admin belum menambahkan katalog HP apapun.
                    @endif
                </p>
            </div>
        @else
            <!-- Grid 2 Kolom untuk HP -->
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                @foreach($products as $product)
                    <a href="{{ route('product.show', $product->slug) }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">

                        <!-- Area Foto -->
                        <div class="aspect-[4/5] bg-gray-100 relative overflow-hidden">
                            @if($product->media->count() > 0)
                                <img src="{{ Storage::url($product->media->first()->file_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Badge Status Stok -->
                            <div class="absolute top-2 left-2">
                                @if($product->stock > 0)
                                    <span class="bg-white/90 backdrop-blur text-green-700 text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                        Tersedia ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="bg-red-500/90 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                        Habis / PO
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Area Teks -->
                        <div class="p-3">
                            <h2 class="text-xs sm:text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-1 group-hover:text-indigo-600 transition">
                                {{ $product->name }}
                            </h2>
                            <p class="text-sm sm:text-base font-bold text-indigo-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

@endsection
