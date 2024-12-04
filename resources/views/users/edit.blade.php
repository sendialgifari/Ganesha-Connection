@seoTitle(__('Edit User'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-form class="space-y-4" action="{{ route('users.update', $user) }}" method="put"
                    :default="$user">
                    <x-splade-radios name="is_selected" label="Pilihan" :options="$is_selected" />
                    <x-splade-input name="name" label="Nama" placeholder="Nama" required />
                    <x-splade-input name="email" type="email" placeholder="Email" label="Email" required />
                    <x-splade-input name="password" type="password" placeholder="Password" label="Password" />
                    <x-splade-select name="roles" label="Role" :options="$roles" required relation choices />
                    <x-splade-submit label="Save" :spinner="false" />
                </x-splade-form>
            </div>
        </div>
</x-app-layout>