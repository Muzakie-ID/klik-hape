<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Produk') }}: {{ $product->name }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Alert Success -->
                    @if (session('success'))
                        <div class="mb-4 bg-green-50 p-4 rounded-md">
                            <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 p-4 rounded-md">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <!-- BAGIAN KIRI: Form Data Produk -->
                        <div class="lg:col-span-2">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama HP -->
                                    <div>
                                        <x-input-label for="name" value="Nama HP" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- Harga -->
                                    <div>
                                        <x-input-label for="price" value="Harga (Rp)" />
                                        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                    </div>

                                    <!-- Stok -->
                                    <div>
                                        <x-input-label for="stock" value="Jumlah Stok" />
                                        <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $product->stock)" required min="0" />
                                        <p class="text-xs text-gray-500 mt-1">Isi 0 jika barang sedang kosong (masuk ke mode Pre-Order/Waitlist).</p>
                                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <x-input-label for="status" value="Status Tampil" />
                                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published (Tampil di Web)</option>
                                            <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan Dulu)</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                    </div>

                                    <!-- Link Shopee -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="shopee_link" value="Link Produk Shopee (Opsional)" />
                                        <x-text-input id="shopee_link" name="shopee_link" type="url" class="mt-1 block w-full" :value="old('shopee_link', $product->shopee_link)" placeholder="https://shopee.co.id/..." />
                                        <x-input-error :messages="$errors->get('shopee_link')" class="mt-2" />
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="description" value="Deskripsi & Kondisi Produk" />
                                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $product->description) }}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>

                                    <!-- Upload Tambahan Foto -->
                                    <div class="md:col-span-2 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                                        <x-input-label for="images" value="Tambah Foto Produk Baru" />
                                        <input type="file" id="images" name="images[]" multiple accept="image/*,.heic" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none p-1">
                                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin menambah foto. Maks 10MB per foto, maksimal 10 foto per upload.</p>

                                        @if($errors->has('images'))
                                            <p class="text-sm text-red-600 mt-2">{{ is_array($errors->get('images')) ? implode(', ', $errors->get('images')) : $errors->get('images') }}</p>
                                        @endif

                                        @foreach($errors->get('images.*') as $message)
                                            <p class="text-sm text-red-600 mt-1">{{ is_array($message) ? implode(', ', $message) : $message }}</p>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center justify-end mt-6">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Update Data Produk
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- BAGIAN KANAN: Manajemen Foto Saat Ini -->
                        <div class="lg:col-span-1 border-t lg:border-t-0 lg:border-l border-gray-200 pt-6 lg:pt-0 lg:pl-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Galeri Foto Saat Ini</h3>

                            @if($product->media->count() > 0)
                                <div class="space-y-4">
                                    @foreach($product->media as $media)
                                        <div class="relative group bg-gray-100 rounded-lg border border-gray-200 p-2 flex items-center gap-4">
                                            <div class="w-20 h-20 shrink-0 bg-white rounded overflow-hidden relative">
                                                <img src="{{ Storage::url($media->file_path) }}" alt="Foto Produk" class="object-cover w-full h-full">
                                                @if($media->is_primary)
                                                    <span class="absolute bottom-0 inset-x-0 bg-indigo-600/90 text-white text-[10px] text-center py-0.5 font-bold">COVER</span>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <p class="text-xs text-gray-500 truncate mb-2">Img: {{ substr($media->file_path, -15) }}</p>

                                                <form action="{{ route('admin.product-media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-800 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus Foto
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center p-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                                    <p class="text-sm text-gray-500">Belum ada foto.</p>
                                </div>
                            @endif
                        </div>

                    </div> <!-- End Grid -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
