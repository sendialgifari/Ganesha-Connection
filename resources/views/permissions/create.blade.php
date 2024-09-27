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
                <x-splade-select name="product_category_id" label="Product Category" :options="$product_categories" />
                <x-splade-radios name="is_selected" label="Is Selected" :options="$is_selected" />
                <x-splade-input name="name" label="Name" />
                <x-splade-textarea name="short_description" label="Short Description" />
                <x-splade-wysiwyg name="description" label="Description" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-input name="fake_price" label="Fake Price" />
                <x-splade-input name="price" label="Price" />
                <x-splade-file name="image" label="Image" :show-filename="false" filepond preview />
                <x-splade-select name="work_units[]" label="Work Units" :options="$work_units" multiple choices />
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
