<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk Baru') }}
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

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 p-4 rounded-md">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama HP -->
                            <div>
                                <x-input-label for="name" value="Nama HP" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required placeholder="Contoh: iPhone 11 64GB Hitam Mulus" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Harga -->
                            <div>
                                <x-input-label for="price" value="Harga (Rp)" />
                                <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" :value="old('price')" required placeholder="Contoh: 4500000" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Stok -->
                            <div>
                                <x-input-label for="stock" value="Jumlah Stok" />
                                <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', 0)" required min="0" />
                                <p class="text-xs text-gray-500 mt-1">Isi 0 jika barang sedang kosong (masuk ke mode Pre-Order/Waitlist).</p>
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" value="Status Tampil" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Tampil di Web)</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan Dulu)</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Link Shopee -->
                            <div class="md:col-span-2">
                                <x-input-label for="shopee_link" value="Link Produk Shopee (Opsional)" />
                                <x-text-input id="shopee_link" name="shopee_link" type="url" class="mt-1 block w-full" :value="old('shopee_link')" placeholder="https://shopee.co.id/..." />
                                <x-input-error :messages="$errors->get('shopee_link')" class="mt-2" />
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Deskripsi & Kondisi Produk" />
                                <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Jelaskan kondisi fisik, garansi, kelengkapan, dll...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Upload Foto -->
                            <div class="md:col-span-2">
                                <x-input-label for="images" value="Foto Produk (Bisa pilih lebih dari satu)" />
                                <input type="file" id="images" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP. Maks 100MB per foto. Foto pertama yang dipilih akan jadi foto utama (Cover).</p>

                                @if($errors->has('images'))
                                    <p class="text-sm text-red-600 mt-2">{{ is_array($errors->get('images')) ? implode(', ', $errors->get('images')) : $errors->get('images') }}</p>
                                @endif

                                @foreach($errors->get('images.*') as $message)
                                    <p class="text-sm text-red-600 mt-1">{{ is_array($message) ? implode(', ', $message) : $message }}</p>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Produk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
