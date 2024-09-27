@seoTitle(__('Static Page Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Static Page') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('static_pages.update', $static_page) }}" method="put"
                :default="$static_page">
                <x-splade-input name="name" label="Title" />
                <x-splade-wysiwyg name="description" label="Content" :jodit="['showXPathInStatusbar' => true]" />
                <x-splade-submit label="Update" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
