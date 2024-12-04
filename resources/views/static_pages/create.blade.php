@seoTitle(__('Tambah Halaman Statis'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Halaman Statis') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('static_pages.store') }}">
                <x-splade-input name="name" label="Judul" />
                <x-splade-wysiwyg name="description" label="Konten" :jodit="['showXPathInStatusbar' => true]" />
                {{-- <x-splade-file name="image" label="Image" :show-filename="false" filepond preview /> --}}
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
