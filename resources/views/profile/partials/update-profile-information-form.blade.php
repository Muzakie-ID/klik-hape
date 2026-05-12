<section>
    <header>
        <h2 class="text-lg sm:text-xl font-bold text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ubah nama dan email akun admin Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-900">Nama</label>
            <input id="name" name="name" type="text" class="mt-2 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
            <input id="email" name="email" type="email" class="mt-2 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl border border-yellow-200 bg-yellow-50 px-3 py-2">
                    <p class="text-sm text-yellow-800">
                        Email Anda belum terverifikasi.
                        <button form="send-verification" class="underline font-medium hover:text-yellow-900">
                            Kirim ulang email verifikasi
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                Simpan Perubahan Profil
            </button>
        </div>
    </form>
</section>
