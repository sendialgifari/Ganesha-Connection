@seoTitle(__('Role Management'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Role Management') }}
            </h2>
            <x-splade-link href="{{ route('roles.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Create
                Role
            </x-splade-link>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$roles">
                    @cell('is_selected', $role)
                    {{ $role->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('image', $role)
                    <img src="{{ asset($role->image) }}" style="height: 50px;" class="rounded-md w-1/3" />
                    @endcell
                    @cell('action', $role)
                    @if($role->id != 1 && $role->id != 5 && $role->id != 6 && $role->id != 10)
                    <x-splade-link href="{{ route('roles.edit', $role) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('roles.destroy', $role) }}" method="delete"
                        confirm="Delete Role" confirm-text="Are you sure you want to delete role?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Delete</x-splade-button>
                    </x-splade-form>
                    @endif
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>