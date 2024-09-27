@seoTitle(__('Register'))

<x-authentication-card>
    <x-slot:logo>
        <x-authentication-card-logo />
    </x-slot>

    <x-splade-form class="space-y-4">
        <x-splade-input id="name" name="name" :label="__('Name')" required autofocus />
        <x-splade-input id="email" name="email" type="email" :label="__('Email')" required />
        <x-splade-input id="password" name="password" type="password" :label="__('Password')" required autocomplete="new-password" />
        <x-splade-input id="password_confirmation" name="password_confirmation" type="password" :label="__('Confirm Password')" required autocomplete="new-password" />

        @if(\Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <x-splade-checkbox name="terms">
                {!! __('Saya setuju dengan :syarat_layanan dan :kebijakan_privasi', [
                    'syarat_layanan' => '<a target="_blank" href="/static/syarat-dan-ketentuan" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Syarat Layanan').'</a>',
                    'kebijakan_privasi' => '<a target="_blank" href="/static/kebijakan-privasi" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Kebijakan Privasi').'</a>',
                ]) !!}
            </x-splade-checkbox>
        @endif

        <div class="flex items-center justify-end">
            <Link href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Sudah registrasi?') }}
            </Link>

            <x-splade-submit :label="__('Register')" class="ml-4" />
        </div>
    </x-splade-form>
</x-authentication-card>
