@seoTitle(__('Kelola Komentar Donasi'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Komentar Donasi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-table :for="$donation_comments" pagination-scroll="head">
                @cell('action', $donation_comment)
                <x-splade-link href="{{ route('donation_comments.edit', $donation_comment) }}">
                    <x-splade-button
                        class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                </x-splade-link>
                <x-splade-form action="{{ route('donation_comments.destroy', $donation_comment) }}" method="delete"
                    confirm="Delete Donation Comment" confirm-text="Are you sure you want to delete donation comment?"
                    confirm-button="Yes" cancel-button="No">
                    <x-splade-button
                        class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                </x-splade-form>
                @endcell
            </x-splade-table>
        </div>
    </div>
</x-app-layout> 