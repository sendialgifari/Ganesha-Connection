@seoTitle(__('Kelola Kategori Webinar'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Kategori Webinar') }}
            </h2>
            <x-splade-link href="{{ route('webinar_categories.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Tambah
                Kategori Webinar
            </x-splade-link>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-table :for="$webinar_categories" pagination-scroll="preserve" striped>
                @cell('is_selected', $webinar_category)
                    {{ $webinar_category->is_selected == 0 ? 'No' : 'Yes' }}
                @endcell
                @cell('action', $webinar_category)
                    <x-splade-link href="{{ route('webinar_categories.edit', $webinar_category) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('webinar_categories.destroy', $webinar_category) }}" method="delete"
                        confirm="Delete Webinar Category" confirm-text="Are you sure you want to delete webinar category?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                @endcell
            </x-splade-table>
        </div>
    </div>
</x-app-layout>
