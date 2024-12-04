@seoTitle(__('Kelola Produk'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Produk') }}
            </h2>
            <a href="{{ route('products.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Tambah
                Produk
            </a>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$products">
                    @cell('is_selected', $product)
                    {{ $product->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('image', $product)
                    <img src="{{ asset($product->image) }}" style="height: 50px;" class="rounded-md w-1/3" />
                    @endcell
                    @cell('action', $product)
                    <a href="{{ route('products.edit', $product) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </a>
                    <x-splade-form action="{{ route('products.destroy', $product) }}" method="delete"
                        confirm="Delete Product" confirm-text="Are you sure you want to delete product?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>