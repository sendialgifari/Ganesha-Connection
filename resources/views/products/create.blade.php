@seoTitle(__('Product Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('products.store') }}" :default="['is_selected' => '0']">
                <x-splade-select name="product_category_id" label="Kategori produk" :options="$product_categories" />
                @if(auth()->user()->getRoleNames()[0] == "admin" || auth()->user()->getRoleNames()[0] == "superadmin")
                <x-splade-radios name="is_selected" label="Produk pilihan" :options="$is_selected" />
                @endif
                @if(auth()->user()->getRoleNames()[0] == "superadmin")
                <x-splade-radios name="admin_category_id" label="Kategori admin" :options="$admin_categories" />
                @endif
                <x-splade-input name="name" label="Nama" />
                <x-splade-textarea name="short_description" label="Deskripsi singkat" />
                <x-splade-wysiwyg name="description" label="Deskripsi" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-input name="fake_price" label="Harga sebelum diskon (tidak perlu diisi kalau tidak ada diskon)" />
                <x-splade-input name="price" label="Harga" />
                <x-splade-file name="image" label="Gambar utama" :show-filename="false" filepond preview min-size="10KB" max-size="2MB" />
                <x-splade-file name="images[]" label="Gambar lainnya (max 5 photo)" multiple filepond preview min-size="10KB" max-size="2MB" />
                {{-- <img src="{{ url('storage/products/'.$product->image) }}" alt="" title="" /> --}}
                <x-splade-select name="work_units[]" label="Unit kerja" :options="$work_units" multiple relation choices />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>

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
