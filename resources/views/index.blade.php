<x-main-layout>

    <div class="carousel">
    <x-splade-lazy>
        <x-slot:placeholder> 
            <img class="rounded-lg my-4" style="width: 100%; max-height: 340px;height: calc(100vw - 72vw); object-fit: cover; background-color: #c6c6c6;" /> 
        </x-slot:placeholder>
        {{-- @if (count($sliders) === 1)
            <Carousel class="py-4">
                <x-splade-lazy>
                    <x-slot:placeholder> loading... </x-slot:placeholder>
                    @foreach ($sliders as $key => $slider)
                        <CarouselSlide key="{{ $key }}">
                            <img class="rounded-lg" style="width: 100%; max-height: 340px; object-fit: cover;"
                                src="{{ $slider->image }}">
                        </CarouselSlide>
                    @endforeach
                </x-splade-lazy>
            </Carousel>
        @else --}}
        <Carousel :autoplay="5000" :wrap-around="true" class="py-4 carousel-slider">
            @foreach ($sliders as $key => $slider)
                <CarouselSlide key="{{ $key }}">
                    <img class="rounded-lg" style=" max-height: 340px;height: calc(100vw - 72vw); object-fit: cover; background-color: #c6c6c6;"
                        src="{{ $slider->image }}" alt="ganesha connection slider">
                </CarouselSlide>
            @endforeach
            <template #addons>
                <CarouselNavigation />
                <CarouselPagination />
            </template>
        </Carousel>
        {{-- @endif --}}
    </x-splade-lazy>
    </div>

    <section class="grid gap-2 grid-cols-2 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
        <div class="rounded-lg p-4 bg-white dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                <div class="items-center justify-between flex sm:space-y-0">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white sm:text-xl">Kategori Produk
                            Pilihan</h2>
                    </div>
                </div>
                {{-- <x-splade-lazy>
                    <x-slot:placeholder>
                        <div class="grid gap-4 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                        </div>
                    </x-slot:placeholder> --}}
                    <div class="scroll-container no-scrollbar">
                        @foreach ($product_categories_selected as $idx => $item)
                            <Link href="/search?type=produk&cat={{ $item->id }}" id="{{ $idx }}-product">
                            <div class="home-category" style="white-space: initial;">
                                <div class="home-category-pic align-top">
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/images/default.png') }}'" />
                                </div>
                                <div class="home-category-name line-clamp-2 pt-1">
                                    <p
                                        class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white">
                                        {{ $item->name }}</p>
                                </div>

                            </div>
                            </Link>
                            @if ($loop->last)
                            <Link href="/search?type=produk">
                            <div class="home-category align-top" style="white-space: initial;">
                                <div class="home-category-pic">
                                    <img src="{{ asset('/images/apps.png') }}" alt="more"/>
                                </div>
                                <div class="home-category-name line-clamp-2 pt-1">
                                    <p class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white">Lebih Lengkap</p>
                                </div>

                            </div>
                            </Link>
                            @endif

                        @endforeach
                    </div>
                {{-- </x-splade-lazy> --}}
            </div>
        </div>
        <div class="rounded-lg p-4 bg-white dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                <div class="items-center justify-between flex sm:space-y-0">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white sm:text-xl">Kategori Jasa
                            Pilihan</h2>
                        
                    </div>
                </div>
                {{-- <x-splade-lazy>
                    <x-slot:placeholder>
                        <div class="grid gap-4 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" src="/images/default.png"
                                        style="width: 100%; background: #e1e1e1" alt="load" />
                                </div>
                            </div>
                        </div>
                    </x-slot:placeholder> --}}
                    <div class="scroll-container no-scrollbar">
                        @foreach ($service_categories_selected as $idx => $item)
                            <Link href="/search?type=jasa&cat={{ $item->id }}" id="{{ $idx }}-service">
                            <div class="home-category align-top" style="white-space: initial;">
                                <div class="home-category-pic">
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/images/default.png') }}'" />
                                </div>
                                <div class="home-category-name line-clamp-2 pt-1">
                                    <p
                                        class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white">
                                        {{ $item->name }}</p>
                                </div>

                            </div>
                            </Link>
                            @if ($loop->last)
                            <Link href="/search?type=jasa">
                            <div class="home-category align-top" style="white-space: initial;">
                                <div class="home-category-pic">
                                    <img src="{{ asset('/images/apps.png') }}" alt="more"/>
                                </div>
                                <div class="home-category-name line-clamp-2 pt-1">
                                    <p class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white">Lebih Lengkap</p>
                                </div>

                            </div>
                            </Link>
                            @endif
                        @endforeach
                    </div>
                {{-- </x-splade-lazy> --}}
            </div>
        </div>
    </section>

    <section class="mt-4 antialiased dark:bg-gray-900 md:py-0">
        <div class="rounded-lg mb-6 p-4 bg-white dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                {{-- <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Partner Pilihan
                        </h2>
                    </div>
                </div> --}}

                    <div class="scroll-container no-scrollbar">
                        @foreach ($admin_promotion_categories as $idx => $item)
                            <Link href="/search?adm_p_cat={{ $item->id }}">
                            <div class="partner-pilihan rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full admin_category_tag">
                                    <img class="mx-auto"
                                        src="{{ $item->image }}" alt="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/images/default.png') }}'" />
                                </div>
                                <div class="pt-1">
                                    <p
                                        class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white" style="font-size: 12px; white-space: initial;">
                                        {{ $item->name }}</p>
                                </div>
                            </div>
                            </Link>
                        @endforeach
                        @foreach ($admin_categories as $idx => $item)
                            <Link href="/search?adm_cat={{ $item->id }}">
                            <div class="partner-pilihan rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full admin_category_tag">
                                    <img class="mx-auto"
                                        src="{{ $item->image }}" alt="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/images/default.png') }}'" />
                                </div>
                                <div class="pt-1">
                                    <p
                                        class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white" style="font-size: 12px; white-space: initial;">
                                        {{ $item->name }}</p>
                                </div>
                            </div>
                            </Link>
                        @endforeach
                    </div>
            </div>
        </div>
    </section>

    <div class="w-full p-4 items-center justify-between flex sm:space-y-0">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Produk Pilihan</h2>
            <p>Beragam produk civitas ITB yang telah dikurasi</p>
        </div>
    </div>


    <section class="produk-pilihan-container">

        <div class="product-content-box dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                    <div class="scroll-container no-scrollbar">
                        @foreach ($product_selected as $idx => $item)
                            @if ($loop->first)
                                <div class="product-title-box scroll-menu" style="display: inline-block;">
                                    <div class="product-title-box-logo"></div>
                                </div>
                            @endif
                            <Link href="/produk/{{ $item->slug }}" id="{{ $idx }}-product">
                            <div class="scroll-menu rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800 product-grid">
                                <div class=" w-full" style="position: relative;">
                                            
                                    @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <div class="discount-tag hidden sm:block">
                                            <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                        </div>
                                    @endif
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" alt="admin-category-promo-tag" class="admin-category-promo-tag" style="bottom:0">
                                    @endif                                    
                                    <img class="mx-auto rounded-top h-full"
                                        src="{{ $item->image_thumb }}" style="width: 100%; max-height: 150px;height: calc(100vw - 56vw); object-fit: cover; background-color: #c6c6c6;"
                                        alt="{{ $item->name }}" />
                                    {{-- <img class="mx-auto rounded-top hidden h-full dark:block"
                                        src="{{ $item->image_thumb }}" alt="{{ $item->name }}" /> --}}
                                </div>
                                <div style="height: 180px;">
                                    @if ($item->admin_category)
                                        <div class="tag-product">{{ $item->admin_category->name }}
                                        </div>
                                    @endif
                                    <div class="pt-1 p-2" style="white-space:initial;">
                                    <p class="line-clamp-2 text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}</p>

                                        <p
                                    class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                    {{ $item->short_description }}</p>

                                    <div class="mt-1 flex items-center gap-2">


                                        @if ($item->total_comments != 0)
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 22 20">
                                                    <path
                                                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                @php
                                                    $ratings =
                                                        ($item->total_comment_star_1 * 1 +
                                                            $item->total_comment_star_2 * 2 +
                                                            $item->total_comment_star_3 * 3 +
                                                            $item->total_comment_star_4 * 4 +
                                                            $item->total_comment_star_5 * 5) /
                                                        $item->total_comments;
                                                @endphp
                                                {{ number_format($ratings, 1, '.', ',') }}</p>
                                        @endif
                                        {{-- Total komen tidak ada di migration --}}
                                        {{-- <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            ({{ $item->total_comments }} Review)</p> --}}
                                    </div>
                                    <div class="mt-1 flex items-center justify-between gap-4">
                                        <p class="text-md leading-tight text-gray-900 dark:text-white">
                                            <span class="font-extrabold">
                                                @if($item->price_type == 0)
                                                @if($item->price == 0)
                                                Gratis
                                                @else
                                                Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                @endif
                                                @else
                                                Hubungi kami
                                                @endif
                                            </span>
                                            <br>
                                                @if ($item->fake_price != 0 && $item->fake_price != null)
                                                <span style="font-weight: bold; font-size: 12px; color: #b2b2b2;"><del>Rp. {{ number_format($item->fake_price, 0, ',', '.') }}</del></span>
                                                @endif

                                            
                                        </p>
                                    </div>
                                    @if($item->user->is_verified == 1)
                                    <span
                                            class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Verified</span>
                                    @else
                                    <span
                                            class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Unverified</span>
                                    @endif
                                    @foreach ($item->work_units as $work_unit)
                                        <span
                                            class="bg-blue-100 text-blue-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">{{ $work_unit->name }}</span>
                                    @endforeach
                                    @if($item->is_readystock == 0)
                                    <span
                                            class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Preorder</span>
                                    @endif
                                    
                                    </div>
                                </div>
                            </div>
                            </Link>
                            @if ($loop->last)
                            <Link href="/search?type=produk">
                            <div class="scroll-menu product-grid">
                                <div class="pilihan-selanjutnya p-4 shadow-md rounded-lg dark:border-gray-700 dark:bg-gray-800">
                                    <div style="white-space: initial; margin-top: 130px;">Temukan Lebih Banyak Produk Pilihan</div>
                                    <div style="white-space: initial; display: block; position: relative; vertical-align: bottom; margin-top: 60px; font-size: 12px;">Lihat Selengkapnya</div>
                                </div>
                            </div>
                            </Link>
                            @endif
                        @endforeach
                    </div>
                {{-- </x-splade-lazy> --}}
            </div>
        </div>
    </section>


    <div class="w-full p-4 items-center justify-between flex sm:space-y-0">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Jasa Pilihan</h2>
            <p>Beragam penawaran jasa dan keahlian civitas ITB yang telah dikurasi</p>
        </div>
    </div>

    <section class="jasa-pilihan-container">
        <div class="product-content-box dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                    <div class="scroll-container no-scrollbar">
                        @foreach ($service_selected as $idx => $item)
                            @if ($loop->first)
                                <div class="product-title-box  scroll-menu">
                                    <div class="product-title-box-logo-jasa"></div>
                                </div>
                            @endif
                            <Link href="/jasa/{{ $item->slug }}" id="{{ $idx }}-service">
                            <div class="scroll-menu rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800 product-grid">
                                <div class=" w-full" style="position:relative;">
                                    @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <div class="discount-tag hidden sm:block">
                                            <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                        </div>
                                    @endif
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="mt-1" alt="admin-category-promo-tag" class="admin-category-promo-tag" style="bottom:0"/>
                                    @endif
                                    <img class="mx-auto rounded-top h-full"
                                        src="{{ $item->image_thumb }}" style="width: 100%; max-height: 150px;height: calc(100vw - 56vw); object-fit: cover; background-color: #c6c6c6;"
                                        alt="{{ $item->name }}" />
                                    {{-- <img class="mx-auto rounded-top hidden h-full dark:block"
                                        src="{{ $item->image_thumb }}" alt="{{ $item->name }}" /> --}}
                                </div>
                                <div style="height: 180px;">
                                    @if ($item->admin_category)
                                        <div class="tag-product">{{ $item->admin_category->name }}
                                        </div>
                                    @endif
                                    <div class="pt-1 p-2" style="white-space:initial;">
                                    <p
                                        class="text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}</p>

                                        <p
                                    class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                    {{ $item->short_description }}</p>

                                    <div class="mt-1 flex items-center gap-2">


                                        @if ($item->total_comments != 0)
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 22 20">
                                                    <path
                                                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                @php
                                                    $ratings =
                                                        ($item->total_comment_star_1 * 1 +
                                                            $item->total_comment_star_2 * 2 +
                                                            $item->total_comment_star_3 * 3 +
                                                            $item->total_comment_star_4 * 4 +
                                                            $item->total_comment_star_5 * 5) /
                                                        $item->total_comments;
                                                @endphp
                                                {{ number_format($ratings, 1, '.', ',') }}</p>
                                        @endif
                                        {{-- Total komen tidak ada di migration --}}
                                        {{-- <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            ({{ $item->total_comments }} Review)</p> --}}
                                    </div>
                                    <div class="mt-1 flex items-center justify-between gap-4">
                                        <p class="text-md leading-tight text-gray-900 dark:text-white">
                                            <span class="font-extrabold">
                                                @if($item->price_type == 0)
                                                @if($item->price == 0)
                                                Gratis
                                                @else
                                                Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                @endif
                                                @else
                                                Hubungi kami
                                                @endif
                                            </span>
                                            <br>
                                            @if ($item->fake_price != 0 && $item->fake_price != null)
                                            <span style="font-weight: bold; font-size: 12px; color: #b2b2b2;"><del>Rp. {{ number_format($item->fake_price, 0, ',', '.') }}</del></span>
                                            @endif

                                        </p>
                                    </div>
                                    @if($item->user->is_verified == 1)
                                    <span
                                            class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Verified</span>
                                    @else
                                    <span
                                            class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Unverified</span>
                                    @endif
                                    @foreach ($item->work_units as $work_unit)
                                        <span
                                            class="bg-blue-100 text-blue-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">{{ $work_unit->name }}</span>
                                    @endforeach
                                    @if($item->is_readystock == 0)
                                    <span
                                            class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Preorder</span>
                                    @endif
                                    

                                    </div>
                                </div>
                            </div>
                            </Link>
                            @if ($loop->last)
                            <Link href="/search?type=jasa">
                            <div class="scroll-menu product-grid">
                                <div class="pilihan-selanjutnya p-4 shadow-md rounded-lg dark:border-gray-700 dark:bg-gray-800">
                                    <div style="white-space: initial; margin-top: 130px;">Temukan Lebih Banyak Produk Pilihan</div>
                                    <div style="white-space: initial; display: block; position: relative; vertical-align: bottom; margin-top: 60px; font-size: 12px;">Lihat Selengkapnya</div>
                                </div>
                            </div>
                            </Link>
                            @endif
                        @endforeach
                    </div>
                {{-- </x-splade-lazy> --}}

            </div>
        </div>


    </section>


    <section class="pt-4 antialiased dark:bg-gray-900 md:py-0">
        <div class="rounded-lg mb-6 p-4 bg-white dark:bg-gray-900 antialiased">
            <div class="mx-auto max-w-screen-xl">
                <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Partner Pilihan
                        </h2>
                    </div>
                </div>

                {{-- <x-splade-lazy>
                    <x-slot:placeholder>
                        <div class="grid gap-4 grid-cols-2 sm:grid-cols-2 lg:grid-cols-6 xl:grid-cols-8"
                            style="grid-template-columns: repeat(8, minmax(0, 1fr));">
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                            <div class="rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full" style="height: 135px;"
                                        src="{{ asset('/images/default.png') }}" />
                                </div>
                            </div>
                        </div>
                    </x-slot:placeholder> --}}

                    <div class="scroll-container no-scrollbar">
                        @foreach ($partner_selected as $idx => $item)
                            <Link href="/partner/{{ $item->slug }}">
                            <div class="partner-pilihan rounded-full shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class=" w-full">
                                    <img class="mx-auto rounded-full h-full"
                                        src="/storage/{{ $item->profile_photo_path }}"
                                        style="width: 100%; background: #e1e1e1" alt="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/images/default.png') }}'" />
                                </div>
                                <div class="pt-1">
                                    <p
                                        class="text-md text-center leading-tight text-gray-900 hover:underline dark:text-white" style="font-size: 12px; white-space:initial;">
                                        {{ $item->name }}</p>
                                </div>
                            </div>
                            </Link>
                        @endforeach
                    </div>
                {{-- </x-splade-lazy> --}}

            </div>
        </div>
    </section>


    <section class="pt-4 antialiased dark:bg-gray-900 md:py-0">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Produk</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <Link href="/search?type=produk"><button type="button"
                        class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                        Lihat produk selengkapnya
                    </button></Link>
                </div>
            </div>
            {{-- <x-splade-lazy>
                <x-slot:placeholder>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-6 xl:grid-cols-6">
                        <div class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class=" w-full">
                                <img class="mx-auto rounded-top h-full dark:hidden" src="/images/default.png"
                                    style="width: 100%;" />
                            </div>
                            <div style="height: 160px;">
                            </div>
                        </div>
                        <div class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class=" w-full">
                                <img class="mx-auto rounded-top h-full dark:hidden" src="/images/default.png"
                                    style="width: 100%;" />
                            </div>
                            <div style="height: 160px;">
                            </div>
                        </div>
                    </div>
                </x-slot:placeholder> --}}
                <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-6 xl:grid-cols-6">
                    @foreach ($products as $idx => $item)
                        <Link href="/produk/{{ $item->slug }}" id="{{ $idx }}-product">
                        <div class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class=" w-full">
                                @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <div class="discount-tag">
                                            <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                        </div>
                                @endif
                                <img class="mx-auto rounded-top h-full" src="{{ $item->image_thumb }}"
                                style="width: 100%; max-height: 200px;height: calc(100vw - 56vw); object-fit: cover; background-color: #c6c6c6;" alt="{{ $item->name }}" />
                                {{-- <img class="mx-auto rounded-top hidden h-full dark:block"
                                    src="{{ $item->image_thumb }}" alt="{{ $item->name }}" /> --}}
                            </div>
                            <div style="height: 180px;">
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" alt="admin-category-promo-tag" class="admin-category-promo-tag" />
                                    @endif
                                    @if ($item->admin_category)
                                        <div class="tag-product">{{ $item->admin_category->name }}
                                        </div>
                                    @endif
                                    <div class="pt-1 p-2">
                                    <p
                                        class="text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }} </p>

                                        <p
                                    class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                    {{ $item->short_description }}</p>

                                    <div class="mt-1 flex items-center gap-2">


                                        @if ($item->total_comments != 0)
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 22 20">
                                                    <path
                                                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                @php
                                                    $ratings =
                                                        ($item->total_comment_star_1 * 1 +
                                                            $item->total_comment_star_2 * 2 +
                                                            $item->total_comment_star_3 * 3 +
                                                            $item->total_comment_star_4 * 4 +
                                                            $item->total_comment_star_5 * 5) /
                                                        $item->total_comments;
                                                @endphp
                                                {{ number_format($ratings, 1, '.', ',') }}</p>
                                        @endif
                                        {{-- Total komen tidak ada di migration --}}
                                        {{-- <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            ({{ $item->total_comments }} Review)</p> --}}
                                    </div>
                                    <div class="mt-1 flex items-center justify-between gap-4">
                                        <p class="text-md leading-tight text-gray-900 dark:text-white">
                                            <span class="font-extrabold">
                                                @if($item->price_type == 0)
                                                @if($item->price == 0)
                                                Gratis
                                                @else
                                                Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                @endif
                                                @else
                                                Hubungi kami
                                                @endif
                                            </span>
                                            @if ($item->fake_price != 0 && $item->fake_price != null)
                                            <span style="font-weight: bold; font-size: 12px; color: #b2b2b2;"><del>Rp. {{ number_format($item->fake_price, 0, ',', '.') }}</del></span>
                                            @endif

                                        </p>
                                    </div>
                                    @if($item->user->is_verified == 1)
                                    <span
                                            class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Verified</span>
                                    @else
                                    <span
                                            class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Unverified</span>
                                    @endif
                                    @foreach ($item->work_units as $work_unit)
                                        <span
                                            class="bg-blue-100 text-blue-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">{{ $work_unit->name }}</span>
                                    @endforeach
                                    @if($item->is_readystock == 0)
                                    <span
                                            class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Preorder</span>
                                    @endif
                                    

                                </div>
                            </div>
                        </div>
                        </Link>
                    @endforeach
                </div>
            {{-- </x-splade-lazy> --}}
        </div>
    </section>

    <section class="pt-0 pb-4 antialiased dark:bg-gray-900 md:pb-4">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Jasa</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <Link href="/search?type=jasa"><button type="button"
                        class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                        Lihat jasa selengkapnya
                    </button></Link>
                </div>
            </div>

            {{-- <x-splade-lazy>
                <x-slot:placeholder>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-6">
                        <div class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class=" w-full">
                                <img class="mx-auto rounded-top h-full dark:hidden" src="/images/default.png"
                                    style="width: 100%;" />
                            </div>
                            <div style="height: 160px;">
                            </div>
                        </div>
                    </div>
                </x-slot:placeholder> --}}
                <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-6 xl:grid-cols-6">
                    @foreach ($services as $idx => $item)
                        <Link href="/jasa/{{ $item->slug }}" id="{{ $idx }}-service">
                        <div class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800"
                            style="height : 380px;">
                            <div class=" w-full">
                                @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <div class="discount-tag">
                                            <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                        </div>
                                @endif
                                <img class="mx-auto rounded-top h-full" src="{{ $item->image_thumb }}"
                                    alt="{{ $item->name }}" style="width: 100%; max-height: 200px;height: calc(100vw - 56vw); object-fit: cover; background-color: #c6c6c6;" />
                                {{-- <img class="mx-auto rounded-top hidden h-full dark:block"
                                    src="{{ $item->image_thumb }}" alt="{{ $item->name }}" /> --}}
                            </div>
                            <div style="height : 180px">
                                @if ($item->admin_promotion_category)
                                    <img src="{{ $item->admin_promotion_category->image }}" alt="admin-category-promo-tag" class="admin-category-promo-tag">
                                @endif
                                @if ($item->admin_category)
                                    <div class="tag-product">{{ $item->admin_category->name }}
                                    </div>
                                @endif
                                <div class="pt-1 p-2">
                                <p
                                    class="text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                    {{ $item->name }}</p>

                                    <p
                                    class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                    {{ $item->short_description }}</p>

                                <div class="mt-1 flex items-center gap-2">

                                    @if ($item->total_comments != 0)
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 22 20">
                                                <path
                                                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            @php
                                                $ratings =
                                                    ($item->total_comment_star_1 * 1 +
                                                        $item->total_comment_star_2 * 2 +
                                                        $item->total_comment_star_3 * 3 +
                                                        $item->total_comment_star_4 * 4 +
                                                        $item->total_comment_star_5 * 5) /
                                                    $item->total_comments;
                                            @endphp
                                            {{ number_format($ratings, 1, '.', ',') }}</p>
                                    @endif
                                    {{-- Total komen tidak ada di migration --}}
                                    {{-- <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        ({{ $item->total_comments }} Review)</p> --}}
                                </div>
                                <div class="mt-1 flex items-center justify-between gap-4">
                                    <p class="text-md leading-tight text-gray-900 dark:text-white">
                                        <span class="font-extrabold">
                                            @if($item->price_type == 0)
                                            @if($item->price == 0)
                                            Gratis
                                            @else
                                            Rp. {{ number_format($item->price, 0, ',', '.') }}
                                            @endif
                                                @else
                                                Hubungi kami
                                                @endif
                                        </span>
                                        @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <span style="font-weight: bold; font-size: 12px; color: #b2b2b2;"><del>Rp. {{ number_format($item->fake_price, 0, ',', '.') }}</del></span>
                                        @endif

                                    </p>
                                </div>
                                @if($item->user->is_verified == 1)
                                    <span
                                            class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Verified</span>
                                    @else
                                    <span
                                            class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Unverified</span>
                                @endif
                                @foreach ($item->work_units as $work_unit)
                                    <span
                                        class="bg-blue-100 text-blue-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                        style="white-space: nowrap;">{{ $work_unit->name }}</span>
                                @endforeach
                                @if($item->is_readystock == 0)
                                    <span
                                            class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Preorder</span>
                                @endif
                                

                                </div>
                            </div>
                        </div>
                        </Link>
                    @endforeach
                </div>
            {{-- </x-splade-lazy> --}}
        </div>
    </section>
</x-main-layout>
