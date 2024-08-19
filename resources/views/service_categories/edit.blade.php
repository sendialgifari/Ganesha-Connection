@seoTitle(__('Service Categories Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service Categories') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('service_categories.update', $service_category) }}" method="put" :default="$service_category">
                <x-splade-input name="name" label="Name" placeholder="Your Name" />
                <x-splade-radios name="is_selected" label="Is Selected" :options="$is_selected" />
                <x-splade-file name="image" label="Image" :show-filename="false" filepond preview />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
