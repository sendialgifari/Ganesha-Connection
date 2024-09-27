@seoTitle(__('Product Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('products.update', $product) }}" method="put"
                :default="$product">
                <x-splade-select name="product_category_id" label="Product Category" :options="$product_categories" />
                <x-splade-radios name="is_selected" label="Is Selected" :options="$is_selected" />
                <x-splade-input name="name" label="Name" />
                <x-splade-textarea name="short_description" label="Short Description" />
                <x-splade-wysiwyg name="description" label="Description" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-input name="fake_price" label="Fake Price" />
                <x-splade-input name="price" label="Price" />
                <x-splade-file name="image" label="Image" :show-filename="false" filepond preview />
                {{-- <img src="{{ url('storage/products/'.$product->image) }}" alt="" title="" /> --}}
                <x-splade-select name="work_units[]" label="Work Units" :options="$work_units" multiple relation choices />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
