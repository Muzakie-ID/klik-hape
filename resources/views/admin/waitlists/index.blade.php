<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Antrean & Prospek') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between items-center">
                <p class="text-sm text-gray-600">
                    Ini adalah daftar orang-orang yang mengklik "Ingatkan Saya". Sistem akan kirim WA saat stok ready, dan jika stok habis lalu ready lagi, sistem akan kirim notifikasi ulang secara otomatis.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Tanggal Antre</th>
                                    <th scope="col" class="px-6 py-3">Pembeli</th>
                                    <th scope="col" class="px-6 py-3">HP yang Ditunggu</th>
                                    <th scope="col" class="px-6 py-3">Status Broadcast</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($waitlists as $waitlist)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $waitlist->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $waitlist->customer_name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $waitlist->wa_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-indigo-600">
                                            {{ $waitlist->product->name ?? 'Produk Dihapus' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($waitlist->is_notified)
                                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Sudah Dikabari
                                                </span>
                                                <div class="text-[10px] text-gray-400 mt-1">{{ $waitlist->notified_at->format('d/m/Y H:i') }}</div>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    Menunggu Restock
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada data antrean.<br>
                                            <span class="text-xs text-gray-400">Share link toko Anda untuk mendapatkan prospek pertama!</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $waitlists->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
