@seoTitle(__('Kelola Kategori Jasa'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Kategori Jasa') }}
            </h2>
            <x-splade-link href="{{ route('service_categories.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Tambah Kategori Jasa
            </x-splade-link>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$service_categories" pagination-scroll="preserve" striped>
                    @cell('is_selected', $service_category)
                    {{ $service_category->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('action', $service_category)
                    <x-splade-link href="{{ route('service_categories.edit', $service_category) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('service_categories.destroy', $service_category)}}" method="delete"
                        confirm="Delete Service Category"
                        confirm-text="Are you sure you want to delete service category?" confirm-button="Yes"
                        cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>