@seoTitle(__('Tambah Donasi'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Donasi') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('donations.store') }}" :default="['is_public' => '1', 'admin_category_id' => '0', 'admin_promotion_category_id' => '0', 'is_selected' => '0']">
                <x-splade-select name="donation_category_id" label="Kategori donasi" :options="$donation_categories" />
                @if (auth()->user()->getRoleNames()[0] == 'admin' || auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="is_selected" label="Donasi pilihan" :options="$is_selected" />
                @endif
                @if (auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="admin_category_id" label="Kategori admin" :options="$admin_categories" />
                    <x-splade-radios name="admin_promotion_category_id" label="Kategori admin 2" :options="$admin_promotion_categories" />
                @endif
                <x-splade-input name="name" label="Nama" />
                <x-splade-input name="goal_amount" label="Target donasi terkumpul" type="number" />
                <x-splade-textarea name="short_description" label="Deskripsi singkat" />
                <x-splade-wysiwyg name="description" label="Deskripsi" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-input name="external_link" label="Link Eksternal" />
                <x-splade-file name="image" label="Gambar utama" :show-filename="false" filepond preview min-size="10KB"
                    max-size="2MB" />
                <x-splade-file name="images[]" label="Gambar lainnya (max 5 photo)" multiple filepond preview
                    min-size="10KB" max-size="2MB" />
                <x-splade-select name="work_units[]" label="Unit kerja" :options="$work_units" multiple relation choices />
                <x-splade-radios name="is_public" label="Tampilkan Donasi" :options="$is_public" />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout> 