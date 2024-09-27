@seoTitle(__('Work Units Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Work Units') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('work_units.store') }}">
                <x-splade-input name="name" label="Name" placeholder="Name" />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
