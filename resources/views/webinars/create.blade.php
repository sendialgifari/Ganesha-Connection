@seoTitle(__('Tambah Webinar'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Webinar') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('webinars.store') }}" :default="['is_public' => '1', 'admin_category_id' => '0', 'admin_promotion_category_id' => '0', 'is_selected' => '0', 'price_type' => '0']">
                <x-splade-select name="webinar_category_id" label="Kategori webinar" :options="$webinar_categories" />
                @if (auth()->user()->getRoleNames()[0] == 'admin' || auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="is_selected" label="Webinar pilihan" :options="$is_selected" />
                @endif
                @if (auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="admin_category_id" label="Kategori admin" :options="$admin_categories" />
                    <x-splade-radios name="admin_promotion_category_id" label="Kategori admin 2" :options="$admin_promotion_categories" />
                @endif
                <x-splade-input name="name" label="Nama" />
                <x-splade-input name="datetime" label="Tanggal" type="datetime-local" />
                <x-splade-input name="duration" label="Durasi (menit)" type="number" />
                <x-splade-textarea name="short_description" label="Deskripsi singkat" />
                <x-splade-wysiwyg name="description" label="Deskripsi" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-input name="external_link" label="Link Eksternal" />
                <x-splade-radios name="price_type" label="Tipe Harga" :options="$price_type" />
                <x-splade-checkbox class="view_price" name="is_fake_price" value="yes" false-value="no"
                    label="Pasang Harga Sebelum Diskon" />
                <x-splade-input class="view_fake_price" name="fake_price"
                    label="Harga sebelum diskon (kosongkan jika tidak ada diskon)" />
                <x-splade-input class="view_price" name="price"
                    label="Harga (kosongkan jika tipe harga adalah Hubungi Kami)" />
                <x-splade-file name="image" label="Gambar utama" :show-filename="false" filepond preview min-size="10KB"
                    max-size="2MB" />
                <x-splade-file name="images[]" label="Gambar lainnya (max 5 photo)" multiple filepond preview
                    min-size="10KB" max-size="2MB" />
                <x-splade-select name="work_units[]" label="Unit kerja" :options="$work_units" multiple relation choices />
                <x-splade-radios name="is_public" label="Tampilkan Webinar" :options="$is_public" />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".view_fake_price").css("display", "none");
        });

        $(document).on('click', 'input[name="price_type"]', function() {
            var price_type = $(this).val();
            if (price_type == "0") {
                $(".view_price").css("display", "block");
            } else if (price_type == "1") {
                $(".view_price").css("display", "none");
                $(".view_fake_price").css("display", "none");
                $('input[name="is_fake_price"]').prop('checked', false);
            }
        });
        $(document).on('click', 'input[name="is_fake_price"]', function() {
            if ($('input[name="is_fake_price"]').is(':checked')) {
                $(".view_fake_price").css("display", "block");
            } else {
                $(".view_fake_price").css("display", "none");
            }
        });
    </script>
</x-app-layout> 