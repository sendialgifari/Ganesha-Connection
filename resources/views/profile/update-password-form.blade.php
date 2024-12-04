<x-splade-form method="put" :action="route('user-password.update')" stay>
    <x-form-section>
        <x-slot:title>
            {{ __('Edit Password') }}
        </x-slot>

        <x-slot:description>
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </x-slot>

        <x-slot:form>
            <div class="col-span-6 sm:col-span-4">
                <x-splade-input id="current_password" type="password" name="current_password" :label="__('Password Sekarang')" autocomplete="current-password" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-splade-input id="password" type="password" name="password" :label="__('Password Baru')" autocomplete="new-password" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Konfirmasi Password')" autocomplete="new-password" />
            </div>
        </x-slot>

        <x-slot:actions>
            <x-action-message v-if="form.recentlySuccessful" class="mr-3">
                {{ __('Saved.') }}
            </x-action-message>

            <x-splade-submit :label="__('Save')" />
        </x-slot>
    </x-form-section>
</x-splade-form>