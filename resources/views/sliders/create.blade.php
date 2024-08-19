@seoTitle(__('Slider Management'))

<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Slider') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form class="space-y-4" action="{{ route('sliders.store') }}">
                <x-splade-input name="name" label="Name" />
                <x-splade-input name="url" label="url" />
                <x-splade-textarea name="description" label="description" />
                <x-splade-file name="image" label="Image" :show-filename="false" filepond preview />
                <x-splade-submit label="Save" />
            </x-splade-form>
        </div>
    </div>

    {{-- <x-splade-script>
            document.addEventListener('DOMContentLoaded', function () {
            const editorInstance = new FroalaEditor('#description', {
            enter: FroalaEditor.ENTER_P,
            placeholderText: null,
            key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            attribution: false,
            events: {
            'image.beforeUpload': function (files) {
            const editor = this
            if (files.length) {
            var reader = new FileReader()
            reader.onload = function (e) {
            var result = e.target.result
            editor.image.insert(result, null, null, editor.image.get())
            }
            reader.readAsDataURL(files[0])
            }
            return false
            }
            }
            }, function () {
            editorInstance.html.set(``);
            })
            });
        </x-splade-script> --}}
</x-app-layout>
