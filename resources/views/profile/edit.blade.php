<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Admin') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            @if (session('status') === 'profile-updated')
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    ✅ Profil berhasil diperbarui (nama/email).
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    ✅ Password berhasil diperbarui.
                </div>
            @endif

            @if (session('status') === 'verification-link-sent')
                <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                    📩 Link verifikasi baru sudah dikirim ke email Anda.
                </div>
            @endif

            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-4 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-4 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white border border-red-200 shadow-sm rounded-2xl p-4 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
