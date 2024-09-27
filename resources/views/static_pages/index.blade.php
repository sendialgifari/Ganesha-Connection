@seoTitle(__('Static Page Management'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Static Page Management') }}
            </h2>
            <x-splade-link href="{{ route('static_pages.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Create
                Static Page
            </x-splade-link>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-table :for="$static_pages">
                @cell('image', $static_page)
                    <img src="{{ asset($static_page->image) }}" style="height: 50px;" class="rounded-md w-1/3" />
                @endcell
                @cell('action', $static_page)
                    <x-splade-link href="{{ route('static_pages.edit', $static_page) }}">
                        <x-splade-button class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('static_pages.destroy', $static_page) }}" method="delete"
                        confirm="Delete Static Page" confirm-text="Are you sure you want to delete static page?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button class="font-bold bg-red-500 hover:bg-red-700 text-white">Delete</x-splade-button>
                    </x-splade-form>
                @endcell
            </x-splade-table>
        </div>
    </div>
</x-app-layout>
