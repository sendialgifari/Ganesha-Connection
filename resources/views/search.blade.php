<x-main-layout>

    <section class="py-8 antialiased dark:bg-gray-900 md:py-8">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <!-- Heading & Filters -->
            <div class="mb-2 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-2">
                <div>
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
                                        class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ms-2">{{ $title }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="gap-3 py-2 sm:flex sm:items-start">
                <div class="shrink-0 space-y-2 hidden sm:block">
                    <div class="flex items-center justify-center">
                        <div class="z-10 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                            <h6 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white sm:text-xl">Filter</h6>
                            <x-splade-form name="filter" :default="[
                                'q' => request('q'),
                                'type' => request('type'),
                                'pmin' => request('pmin'),
                                'pmax' => request('pmax'),
                                'rt' => request('rt'),
                                'cat' => request('cat'),
                                'adm_cat' => request('adm_cat'),
                                'adm_p_cat' => request('adm_p_cat'),
                                'unit' => request('unit'),
                                'ob' => request('ob'),
                                'user' => request('user'),
                            ]" method="GET" action="/search"
                                :submit-on-change="['rt', 'cat', 'adm_cat', 'adm_p_cat', 'unit', 'type']">
                                @if ($title != 'Produk' && $title != 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Tipe
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="produk" name="type" value="produk"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Produk
                                            </label>
                                        </li>
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="jasa" name="type" value="jasa"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Jasa
                                            </label>
                                        </li>
                                    </ul>
                                @endif
                                @if ($title == 'Produk' || $title == 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Kategori
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        @foreach ($categories as $idx => $item)
                                            <li class="flex items-center">
                                                <x-splade-checkbox id="{{ $item->id }}" name="cat"
                                                    value="{{ $item->id }}"
                                                    class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                <label for="apple"
                                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->name }}
                                                    {{-- (56) --}}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Jenis Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Departemen Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_promotion_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_p_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_p_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Harga
                                </h6>
                                <x-splade-input name="pmin" placeholder="Min" class="mb-2" />
                                <x-splade-input name="pmax" placeholder="Max" class="mb-2" />
                                

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Rating
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    <li class="flex items-center">
                                        <!-- <x-splade-checkbox @change="$splade.shout" label="Allocate this amount" name="rt" v-model="form.rt" value="1" /> -->
                                        <x-splade-checkbox id="4+" name="rt" value="4+"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="rt"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div> 4 keatas
                                            </div>
                                        </label>
                                    </li>
                                </ul>


                                {{-- @if(request('type')) --}}
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Unit Kerja
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($work_units as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="unit"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- @endif --}}


                                <x-splade-submit style="width: 100%"
                                    class="mb-4 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    label="Terapkan" />
                                
                                
                            </x-splade-form>
                        </div>
                    </div>
                </div>




                <div class="mt-4 min-w-0 flex-1 space-y-4 sm:mt-0">









                    <img class="mx-auto rounded-lg shadow h-full" src="/images/produk-banner.png" style="width: 100%; background: #e1e1e1" alt="load" />





                    <div class="mb-2 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-2">
                        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                            {{ $title }}
                        </h2>
                        <div class="flex items-center space-x-4">

<!-- Modal toggle -->
<Link href="#filter-modal" class="block sm:hidden  text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
    Filter
</Link>
{{-- <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block sm:hidden  text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
  Filter
</button> --}}


                            <b>Urutkan:</b>
                            <x-splade-form name="filter" :default="[
                                'q' => request('q'),
                                'type' => request('type'),
                                'pmin' => request('pmin'),
                                'pmax' => request('pmax'),
                                'rt' => request('rt'),
                                'cat' => request('cat'),
                                'adm_cat' => request('adm_cat'),
                                'adm_p_cat' => request('adm_p_cat'),
                                'unit' => request('unit'),
                                'ob' => request('ob'),
                                'user' => request('user'),
                            ]" method="GET" action="/search"
                                :submit-on-change="['ob']">
                                <x-splade-select name="ob">
                                    <option value="0">Populer</option>
                                    <option value="1">Terbaru</option>
                                    <option value="2">Review</option>
                                    <option value="3">Harga tertinggi</option>
                                    <option value="4">Harga terendah</option>
                                </x-splade-select>
                            </x-splade-form>
                        </div>
                    </div>
                    {{-- <x-splade-lazy> --}}
                    @if(count($data) == 0)
                    <h3 class="pt-8 text-xl text-gray-900 dark:text-white sm:text-2xl text-center">Produk / Jasa tidak tersedia</h3>
                    @endif
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">

                        {{-- <x-slot:placeholder> loading... </x-slot:placeholder> --}}
                        @foreach ($data as $idx => $item)
                            @if ($title == 'Produk')
                                <Link href="/produk/{{ $item->slug }}" id="{{ $idx }}-produk">
                            @elseif($title == 'Jasa')
                                <Link href="/jasa/{{ $item->slug }}" id="{{ $idx }}-jasa">
                            @else
                                <Link href="/{{ $item->type }}/{{ $item->slug }}">
                            @endif
                            <div
                                class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800" >
                                <div class=" w-full">
                                    @if ($item->fake_price != 0 && $item->fake_price != null)
                                        <div class="discount-tag">
                                            <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                        </div>
                                    @endif
                                    <img class="rounded-top mx-auto h-full" src="{{ $item->image_thumb }}"
                                        alt="{{ $item->name }}" style="width: 100%;max-height: 250px;height: calc(100vw - 56vw); object-fit: cover; background-color: #c6c6c6"/>
                                    {{-- <img class="rounded-top mx-auto hidden h-full dark:block" src="{{ $item->image_thumb }}"
                                        alt="{{ $item->name }}" /> --}}
                                </div>
                                <div  style="height: 180px;">
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="admin-category-promo-tag"/>
                                    @endif
                                    @if($item->admin_category)
                            <div class="tag-product">{{$item->admin_category->name}}
                            </div>
                            @endif

                                    <div class="pt-1 p-2">
                                    <p
                                        class="text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}
                                    </p>

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
                                                @php        $ratings = (($item->total_comment_star_1 * 1) + ($item->total_comment_star_2 * 2) + ($item->total_comment_star_3 * 3) + ($item->total_comment_star_4 * 4) + ($item->total_comment_star_5 * 5)) / $item->total_comments; @endphp
                                                {{ number_format($ratings, 1, '.', ',') }}
                                            </p>
                                        @endif
                                        {{-- Total komen tidak ada di migration --}}
                                        {{-- <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            ({{ $item->total_comments }} Review)
                                        </p> --}}
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
                                                @if ($item->fake_price != 0 && $item->fake_price != null)<span
                                                style="font-weight: bold; font-size: 12px; color: #b2b2b2;"><del> Rp.
                                                    {{ number_format($item->fake_price, 0, ',', '.') }}</del></span>
                                                @endif
                                        </p>
                                    </div>
                                    {{-- @if(request('type')) --}}
                                    @foreach ($item->work_units as $work_unit)
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">{{ $work_unit->name }}</span>
                                    @endforeach
                                    @if($item->is_readystock == 0)
                                    <span
                                            class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Preorder</span>
                                    @endif
                                    @if($item->user->is_verified == 1)
                                    <span
                                            class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Verified</span>
                                    @else
                                    <span
                                            class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                            style="white-space: nowrap;">Unverified</span>
                                    @endif
                                    {{-- @endif --}}
                                    </div>
                                </div>
                            </div>
                            </Link>
                        @endforeach



                    </div>
                    {{ $data->links() }}
                    {{-- </x-splade-lazy> --}}
                </div>
            </div>
        </div>
    </section>




<!-- Main modal -->
<x-splade-modal name="filter-modal">
    <h6 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white sm:text-xl">Filter</h6>
                            <x-splade-form name="filter" :default="[
                                'q' => request('q'),
                                'type' => request('type'),
                                'pmin' => request('pmin'),
                                'pmax' => request('pmax'),
                                'rt' => request('rt'),
                                'cat' => request('cat'),
                                'adm_cat' => request('adm_cat'),
                                'adm_p_cat' => request('adm_p_cat'),
                                'unit' => request('unit'),
                                'ob' => request('ob'),
                                'user' => request('user'),
                            ]" method="GET" action="/search"
                                :submit-on-change="['rt', 'cat', 'adm_cat', 'adm_p_cat', 'unit', 'type']">
                                @if ($title != 'Produk' && $title != 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Tipe
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="produk" name="type" value="produk"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Produk
                                            </label>
                                        </li>
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="jasa" name="type" value="jasa"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Jasa
                                            </label>
                                        </li>
                                    </ul>
                                @endif
                                @if ($title == 'Produk' || $title == 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Kategori
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        @foreach ($categories as $idx => $item)
                                            <li class="flex items-center">
                                                <x-splade-checkbox id="{{ $item->id }}" name="cat"
                                                    value="{{ $item->id }}"
                                                    class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                <label for="apple"
                                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->name }}
                                                    {{-- (56) --}}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Jenis Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Departemen Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_promotion_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_p_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_p_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Harga
                                </h6>
                                <x-splade-input name="pmin" placeholder="Min" class="mb-2" />
                                <x-splade-input name="pmax" placeholder="Max" class="mb-2" />
                                

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Rating
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    <li class="flex items-center">
                                        <!-- <x-splade-checkbox @change="$splade.shout" label="Allocate this amount" name="rt" v-model="form.rt" value="1" /> -->
                                        <x-splade-checkbox id="4+" name="rt" value="4+"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="rt"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div> 4 keatas
                                            </div>
                                        </label>
                                    </li>
                                </ul>


                                {{-- @if(request('type')) --}}
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Unit Kerja
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($work_units as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="unit"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- @endif --}}


                                <x-splade-submit style="width: 100%"
                                    class="mb-4 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    label="Terapkan" />
                                
                                
                            </x-splade-form>
</x-splade-modal>
<div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" style="z-index: 9999;">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Filter
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                            <x-splade-form name="filter" :default="[
                                'q' => request('q'),
                                'type' => request('type'),
                                'pmin' => request('pmin'),
                                'pmax' => request('pmax'),
                                'rt' => request('rt'),
                                'cat' => request('cat'),
                                'adm_cat' => request('adm_cat'),
                                'adm_p_cat' => request('adm_p_cat'),
                                'unit' => request('unit'),
                                'ob' => request('ob'),
                                'user' => request('user'),
                            ]" method="GET" action="/search"
                                :submit-on-change="['rt', 'cat', 'adm_cat', 'adm_p_cat', 'unit', 'type']">
                                @if ($title != 'Produk' && $title != 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Tipe
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="produk" name="type" value="produk"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Produk
                                            </label>
                                        </li>
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="jasa" name="type" value="jasa"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Jasa
                                            </label>
                                        </li>
                                    </ul>
                                @endif
                                @if ($title == 'Produk' || $title == 'Jasa')
                                    <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Kategori
                                    </h6>
                                    <ul class="space-y-2 mb-4 text-sm">
                                        @foreach ($categories as $idx => $item)
                                            <li class="flex items-center">
                                                <x-splade-checkbox id="{{ $item->id }}" name="cat"
                                                    value="{{ $item->id }}"
                                                    class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                <label for="apple"
                                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->name }}
                                                    {{-- (56) --}}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Jenis Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Departemen Produk / Jasa
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($admin_promotion_categories as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="adm_p_cat"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                    <li class="flex items-center">
                                        <x-splade-checkbox id="non" name="adm_p_cat"
                                            value="non"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Non-Jenis
                                        </label>
                                    </li>
                                </ul>


                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Harga
                                </h6>
                                <x-splade-input name="pmin" placeholder="Min" class="mb-2" />
                                <x-splade-input name="pmax" placeholder="Max" class="mb-2" />
                                

                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Rating
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    <li class="flex items-center">
                                        <!-- <x-splade-checkbox @change="$splade.shout" label="Allocate this amount" name="rt" v-model="form.rt" value="1" /> -->
                                        <x-splade-checkbox id="4+" name="rt" value="4+"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="rt"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div> 4 keatas
                                            </div>
                                        </label>
                                    </li>
                                </ul>


                                {{-- @if(request('type')) --}}
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Unit Kerja
                                </h6>
                                <ul class="space-y-2 mb-4 text-sm">
                                    @foreach ($work_units as $idx => $item)
                                        <li class="flex items-center">
                                            <x-splade-checkbox id="{{ $item->id }}" name="unit"
                                                value="{{ $item->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- @endif --}}


                                <x-splade-submit style="width: 100%"
                                    class="mb-4 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    label="Terapkan" />
                                
                                
                            </x-splade-form>
            </div>
        </div>
    </div>
</div>



</x-main-layout>

<!-- <x-splade-script>
    $splade.shout = function() {
        var formElement = document.forms.filter;
       console.log(formElement.rt);
    }
</x-splade-script> -->
