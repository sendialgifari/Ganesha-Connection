@seoTitle(__('Kelola Informasi Webinar'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Informasi Webinar') }}
            </h2>
            <a href="{{ route('webinars.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Tambah
                Informasi Webinar
            </a>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$webinars">
                    @cell('is_selected', $webinar)
                    {{ $webinar->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('image', $webinar)
                    <img src="{{ asset($webinar->image) }}" style="height: 50px;" class="rounded-md w-1/3" />
                    @endcell
                    @cell('action', $webinar)
                    <a href="{{ route('webinars.edit', $webinar) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </a>
                    <x-splade-form action="{{ route('webinars.destroy', $webinar) }}" method="delete"
                        confirm="Delete Webinar" confirm-text="Are you sure you want to delete webinar?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>