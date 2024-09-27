@seoTitle(__('Slider Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Slider') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('sliders.update', $slider) }}" method="put"
                :default="$slider">
                <x-splade-input name="name" label="Name" />
                <x-splade-input name="url" label="url" />
                <x-splade-textarea name="description" label="description" />
                <x-splade-file name="image" label="Image" :show-filename="false" filepond preview />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
