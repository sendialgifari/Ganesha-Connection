@seoTitle(__('Edit Halaman Statis'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Halaman Statis') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('static_pages.update', $static_page) }}" method="put"
                :default="$static_page">
                <x-splade-input name="name" label="Judul" />
                <x-splade-wysiwyg name="description" label="Konten" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
