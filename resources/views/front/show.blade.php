@extends('front.layout')

@section('content')

    <!-- Tombol Back Melayang (tetap di layar saat scroll) -->
    <a href="{{ route('home') }}" class="fixed top-[84px] left-4 z-40 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-md text-gray-800 hover:bg-white transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>

    <!-- Alert Success Waitlist (Jika ada) -->
    @if(session('waitlist_success'))
        <div x-data="{ show: true }" x-show="show" class="fixed top-[84px] left-4 right-4 z-40 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex justify-between items-start max-w-md mx-auto">
            <div>
                <strong class="font-bold text-sm">Pesan Masuk!</strong>
                <span class="block sm:inline text-sm mt-1">{{ session('waitlist_success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="fill-current h-5 w-5" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    <!-- Slider Galeri Foto (Alpine.js) -->
    <div x-data="{ activeSlide: 0 }" class="relative bg-gray-100 aspect-[4/3] w-full">
        @if($product->media->count() > 0)
            <!-- Kontainer Scroll -->
            <div class="flex overflow-x-auto snap-x snap-mandatory no-scrollbar h-full w-full"
                 @scroll="activeSlide = Math.round($event.target.scrollLeft / $event.target.clientWidth)">
                @foreach($product->media as $index => $media)
                    <div class="flex-none w-full h-full snap-center relative overflow-hidden bg-gray-100">
                        <!-- Canvas background -->
                        <img src="{{ Storage::url($media->file_path) }}" alt="{{ $product->name }} - {{ $index + 1 }} background" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl opacity-25">

                        <!-- Main image: always fully visible, no distortion -->
                        <div class="relative z-10 w-full h-full flex items-center justify-center p-2">
                            <img src="{{ Storage::url($media->file_path) }}" alt="{{ $product->name }} - {{ $index + 1 }}" class="max-w-full max-h-full object-contain">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dots Indicator -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                @foreach($product->media as $index => $media)
                    <div class="w-2 h-2 rounded-full transition-all duration-300"
                         :class="activeSlide === {{ $index }} ? 'bg-indigo-600 w-4' : 'bg-white/70'"></div>
                @endforeach
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Info Utama Produk -->
    <div class="p-4 bg-white mb-2 shadow-sm">
        <div class="flex justify-between items-start gap-4 mb-2">
            <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
            <!-- Badge Stok -->
            <div class="shrink-0 mt-1">
                @if($product->stock > 0)
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded shadow-sm border border-green-200">
                        Ready ({{ $product->stock }})
                    </span>
                @else
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded shadow-sm border border-red-200">
                        Habis / PO
                    </span>
                @endif
            </div>
        </div>

        <p class="text-2xl font-black text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    </div>

    <!-- Deskripsi Produk -->
    <div class="p-4 bg-white shadow-sm mb-2">
        <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Deskripsi Produk
        </h3>
        <div class="prose prose-sm text-gray-700 leading-relaxed max-w-none line-clamp-5">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    <!-- Alpine Component untuk Modal Waitlist & Sticky Bottom Bar -->
    <div x-data="{ modalOpen: false }">

        <!-- Sticky Bottom Action Bar -->
        <div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 p-3 flex gap-3 max-w-md mx-auto shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            @if($product->stock > 0)
                <!-- Jika Ready -->
                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20tertarik%20dengan%20{{ urlencode($product->name) }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                    </svg>
                    Tanya WA
                </a>

                @if($product->shopee_link)
                    <a href="{{ $product->shopee_link }}" target="_blank" class="flex-[1.5] flex items-center justify-center gap-2 bg-[#EE4D2D] hover:bg-[#d74326] text-white font-semibold py-3 px-4 rounded-xl transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Beli via Shopee
                    </a>
                @endif
            @else
                <!-- Jika Kosong / PO -->
                <button @click="modalOpen = true" class="flex-[1.5] flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Ingatkan Saya
                </button>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20untuk%20{{ urlencode($product->name) }}%20kapan%20ready%20lagi%20ya?" target="_blank" class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-xl transition border border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                    </svg>
                    Tanya WA
                </a>
            @endif
        </div>

        <!-- Background Overlay Modal -->
        <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>

        <!-- Modal Content (Slide up from bottom) -->
        <div x-show="modalOpen" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-3xl shadow-2xl max-w-md mx-auto">

            <div class="p-6">
                <!-- Handle / Garis penarik -->
                <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6"></div>

                <h3 class="text-xl font-bold text-gray-900 mb-1">Daftar Antrean</h3>
                <p class="text-sm text-gray-500 mb-6">Masukkan nama dan WA. Kami akan hubungi otomatis saat {{ $product->name }} ready stock!</p>

                <form action="{{ route('waitlist.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Panggilan</label>
                        <input type="text" id="customer_name" name="customer_name" required placeholder="Contoh: Budi" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3">
                    </div>

                    <div>
                        <label for="wa_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="tel" id="wa_number" name="wa_number" required placeholder="Contoh: 08123456789" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3">
                        <p class="text-[10px] text-gray-400 mt-1">*Pastikan nomor aktif. Kami tidak akan mengirim spam.</p>
                    </div>

                    <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm px-5 py-3.5 text-center transition">
                        Kirim & Ingatkan Saya
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection
