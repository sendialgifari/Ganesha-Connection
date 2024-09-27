@seoTitle(__('Daftar Partner'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Partner') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (Auth::user()->partner_approval == 0)
                <x-splade-form method="put" :action="route('partner_registration_update')" :default="auth()->user()"
                    @success="$splade.emit('profile-information-updated')">
                    <x-form-section dusk="update-profile-information-form">
                        <x-slot:title>
                            {{ __('') }}
                        </x-slot>

                        <x-slot:description>
                            {{ __('Silahkan Daftar Partner untuk mengaktifkan fitur produk dan jasa milik anda sendiri.') }}
                        </x-slot>

                        <x-slot:form>

                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-input id="name" name="name" :label="__('Name')" autocomplete="name"
                                    required />
                            </div>

                            <!-- Email -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-input id="email" name="email" type="email" :label="__('Email')"
                                    autocomplete="name" required />
                                <div id="verify-email" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-select name="gender" :options="$gender" :label="__('Gender')" required />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-select name="province_id" :options="$provinces" :label="__('Select Province')" required />
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-select name="city_id" remote-url="`/api/cities/${form.province_id}`"
                                    option-label="kabupaten_kota" option-value="id" :label="__('Select City')" required />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-textarea name="address" :label="__('Address')" autosize required />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-input id="phone_number" name="phone_number" :label="__('Phone Number')"
                                    autocomplete="phone_number" required />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-splade-wysiwyg name="description" label="Description" :jodit="['showXPathInStatusbar' => true]" />
                            </div>


                        </x-slot>

                        <x-slot:actions>
                            <x-action-message v-if="form.recentlySuccessful" class="mr-3">
                                {{ __('Saved.') }}
                            </x-action-message>

                            <x-splade-submit :label="__('Daftar sekarang')" />
                        </x-slot>
                    </x-form-section>
                </x-splade-form>
            @else
                
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Submit Berhasil !</span> Tim kami sedang memvalidasi data anda
                    </div>
                </div>
                
            @endif
        </div>
    </div>
</x-app-layout>
