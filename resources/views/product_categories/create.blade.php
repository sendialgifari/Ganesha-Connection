@seoTitle(__('Tambah Kategori Produk'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori Produk') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('product_categories.store') }}" :default="['is_selected' => '0']">
                <x-splade-input name="name" label="Nama" placeholder="Nama" />
                <x-splade-radios name="is_selected" label="Pilihan" :options="$is_selected" />
                <x-splade-file name="image" label="Gambar" :show-filename="false" filepond preview />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
