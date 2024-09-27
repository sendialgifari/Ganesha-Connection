@seoTitle(__('Partner Approval'))

<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Partner Approval Management') }}
            </h2>
        </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-splade-table :for="$users" pagination-scroll="preserve" striped>
                    @cell('action', $user)
                    <x-splade-form action="{{ route('partner_approval_update', ['id' => $user->id])}}" method="put" confirm="Approve"
                        confirm-text="Are you sure you want to approve user?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white">Approve</x-splade-button>
                    </x-splade-form>
                    {{-- <x-splade-form action="{{ route('partner_decline_update', ['id' => $user->id])}}" method="put" confirm="Decline"
                        confirm-text="Are you sure you want to decline user?"
                        confirm-button="Yes" cancel-button="No">
                        <x-splade-button class="font-bold bg-red-500 hover:bg-red-700 text-white">Decline</x-splade-button>
                    </x-splade-form> --}}
                    @endcell
                </x-splade-table>
            </div>
        </div>
</x-app-layout>