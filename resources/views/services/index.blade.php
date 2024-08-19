@seoTitle(__('Service Management'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Service Management') }}
            </h2>
            <x-splade-link href="{{ route('services.create') }}"
                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md">Create
                Service
            </x-splade-link>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$services">
                    @cell('is_selected', $service)
                    {{ $service->is_selected == 0 ? 'No' : 'Yes' }}
                    @endcell
                    @cell('image', $service)
                    <img src="{{ asset($service->image) }}" style="height: 50px;" class="rounded-md w-1/3" />
                    @endcell
                    @cell('action', $service)
                    <x-splade-link href="{{ route('services.edit', $service) }}">
                        <x-splade-button
                            class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Edit</x-splade-button>
                    </x-splade-link>
                    <x-splade-form action="{{ route('services.destroy', $service) }}" method="delete"
                        confirm="Delete Service" confirm-text="Are you sure you want to delete service?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button
                            class="font-bold bg-red-500 hover:bg-red-700 text-white">Delete</x-splade-button>
                    </x-splade-form>
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>