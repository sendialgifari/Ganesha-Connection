<x-main-layout>
    {{-- <link rel="icon" href="{{ url('images/favicon.ico') }}"> --}}
    <section class="pt-8 antialiased dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <Link href="/"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                            </Link>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m9 5 7 7-7 7" />
                                </svg>
                                <span
                                    class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ms-2">{{ $detail_data->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="rounded-lg bg-white my-8 py-2 antialiased dark:bg-gray-900 md:py-2">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div style="
            padding: 25px 45px;
        ">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-6">
                    {{ $detail_data->name }}</h2>
                {!! $detail_data->description !!}
            </div>
        </div>

        {{-- <div
            style="background-image: url('{{ asset('/storage/products/img-qOSNydbAFfJj7Bywf7xyN7a9U4rLN5oATD5JTZCq.jpg') }}');" class="bg-image">
        </div> --}}
    </section>


</x-main-layout>
