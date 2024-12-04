@seoTitle(__('Kelola User'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola User') }}
            </h2>
            <x-splade-link href="{{ route('users.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Tambah User
            </x-splade-link>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$users" pagination-scroll="preserve" striped>
                    @cell('is_selected', $user)
                    {{ $user->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('action', $user)
                    <a href="/partner/{{$user->slug}}" target="_blank">
                        <x-splade-button
                            class="font-bold bg-green-500 hover:bg-green-700 text-white">Show</x-splade-button>
                    </a>
                    <x-splade-link href="{{ route('users.edit', $user) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('users.destroy', $user)}}" method="delete" confirm="Delete profile"
                        confirm-text="Are you sure you want to delete user?" confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Hapus</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>