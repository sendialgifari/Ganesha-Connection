@seoTitle(__('Edit Kategori Webinar'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori Webinar') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('webinar_categories.update', $webinar_category) }}" method="put" :default="$webinar_category">
                <x-splade-input name="name" label="Nama" />
                <x-splade-radios name="is_selected" label="Pilihan" :options="$is_selected" />
                <x-splade-file name="image" label="Gambar" :show-filename="false" filepond preview />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout> 