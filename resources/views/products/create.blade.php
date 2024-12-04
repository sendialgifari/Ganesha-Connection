@seoTitle(__('Tambah Produk'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('products.store') }}" :default="['is_public' => '1', 'admin_category_id' => '0', 'admin_promotion_category_id' => '0', 'is_selected' => '0', 'is_readystock' => '1', 'price_type' => '0']">
                <x-splade-select name="product_category_id" label="Kategori produk" :options="$product_categories" />
                @if (auth()->user()->getRoleNames()[0] == 'admin' || auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="is_selected" label="Produk pilihan" :options="$is_selected" />
                @endif
                @if (auth()->user()->getRoleNames()[0] == 'superadmin')
                    <x-splade-radios name="admin_category_id" label="Kategori admin" :options="$admin_categories" />
                    <x-splade-radios name="admin_promotion_category_id" label="Kategori admin 2" :options="$admin_promotion_categories" />
                @endif
                <x-splade-radios name="is_readystock" label="Status Produk" :options="$is_readystock" />
                <x-splade-input name="name" label="Nama" />
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
                {{-- <img src="{{ url('storage/products/'.$product->image) }}" alt="" title="" /> --}}
                <x-splade-select name="work_units[]" label="Unit kerja" :options="$work_units" multiple relation choices />
                <x-splade-radios name="is_public" label="Tampilkan Produk" :options="$is_public" />
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

    {{-- <x-splade-script>
            document.addEventListener('DOMContentLoaded', function () {
            const editorInstance = new FroalaEditor('#description', {
            enter: FroalaEditor.ENTER_P,
            placeholderText: null,
            key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            attribution: false,
            events: {
            'image.beforeUpload': function (files) {
            const editor = this
            if (files.length) {
            var reader = new FileReader()
            reader.onload = function (e) {
            var result = e.target.result
            editor.image.insert(result, null, null, editor.image.get())
            }
            reader.readAsDataURL(files[0])
            }
            return false
            }
            }
            }, function () {
            editorInstance.html.set(``);
            })
            });
        </x-splade-script> --}}
</x-app-layout>
