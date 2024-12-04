@seoTitle(__('Kelola Komentar Jasa'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Komentar Jasa') }}
            </h2>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



                <x-splade-table :for="$service_comments">
                    @cell('action', $service_comment)
                    <x-splade-link href="{{ route('service_comments.edit', $service_comment) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('service_comments.destroy', $service_comment) }}" method="delete"
                        confirm="Delete Service Comment" confirm-text="Are you sure you want to delete service comment?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>

            </div>
        </div>
</x-app-layout>