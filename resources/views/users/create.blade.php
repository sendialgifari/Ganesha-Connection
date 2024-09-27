@seoTitle(__('User Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('users.store') }}" :default="['roles' => 5, 'is_selected' => '0']">
            <x-splade-radios name="is_selected" label="Is Selected" :options="$is_selected" />
                <x-splade-input name="name" label="Name" placeholder="Name" required />
                <x-splade-input name="email" type="email" placeholder="Email Address" label="Email" required />
                <x-splade-input name="password" type="password" placeholder="Password" label="Password" required />
                <x-splade-select name="roles" label="Role" :options="$roles" required relation choices />
                <x-splade-submit label="Save" :spinner="false" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
