<x-main-layout>

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
                        @if($type != "partner")
                            <li>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m9 5 7 7-7 7" />
                                    </svg>

                                    <Link href="{{ $type == 'produk' ? '/search?type=produk' : '/search?type=jasa' }}"
                                        class="ms-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white md:ms-2">
                                    {{ ucwords($type) }}</Link>
                                </div>
                            </li>
                        @endif
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

    <section class="rounded-lg bg-white py-10 md:py-10 dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                    @if ($type !== 'partner')
                        <!-- <img class="w-full rounded-lg dark:hidden" src="{{ $detail_data->image }}"
                                alt="{{$detail_data->name}}" />
                            <img class="w-full rounded-lg hidden dark:block" src="{{ $detail_data->image }}"
                                alt="{{$detail_data->name}}" /> -->


                        <x-splade-toggle :data="$images_data">
                            @foreach ($images as $key => $image)
                                <img class="w-full rounded-lg" src="{{ $image['image'] }}"
                                    v-if="{{$image['section']}}" style="width: 100vw; max-height: 512px;height: calc(100vw - 11vw); object-fit: cover; background-color: #c6c6c6;" />
                            @endforeach
                            @if(count($images) > 1)
                                @foreach ($images as $key => $image)
                                    <button @click.prevent="{{$image['click']}}" class="mt-2 mr-2"><img
                                            style="width: 70px; height: 70px; object-fit: cover;"
                                            class="w-full rounded-lg" src="{{ $image['image'] }}" /></button>
                                @endforeach
                            @endif
                        </x-splade-toggle>


                    @else
                        <img class="w-full rounded-full" src="{{ $detail_data->profile_photo_url }}"
                            alt="{{$detail_data->name}}" style="width: 200px; height: 200px; object-fit: cover;" />
                        {{-- <img class="w-full rounded-full hidden dark:block" src="{{ $detail_data->profile_photo_url }}"
                            alt="{{$detail_data->name}}" style="width: 200px; height: 200px; object-fit: cover;" /> --}}
                    @endif
                </div>

                <div class="mt-6 sm:mt-8 lg:mt-0">
                    @if ($type !== 'partner')
                    @if ($detail_data->admin_promotion_category)
                        <img src="{{ $detail_data->admin_promotion_category->image }}" class="mb-2" style="height: 35px;"/>
                    @endif
                        @if($detail_data->admin_category_id)
                        <div class="admin-category-detail shadow-sm">
                        Produk {{ $detail_data->admin_category->name }}
                        </div>
                        @endif
                    @endif
                    
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        {{ $detail_data->name }}
                        @if ($type == 'partner')
                        @if($detail_data->is_verified == 1)
                        <span
                                class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                style="white-space: nowrap;">Verified</span>
                        @else
                        <span
                                class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                style="white-space: nowrap;">Unverified</span>
                        @endif
                        @endif
                    </h1>
                    <!--@if ($type !== 'partner')
                     <div class="flex items-center gap-2 mt-2 sm:mt-0">
                        @if ($detail_data->total_comments != 0)
                            <div class="flex items-center gap-1">
                                <div class="ratings">
                                    <div class="empty-stars"></div>
                                    @php    $percentage = (($detail_data->total_comment_star_1 * 1) + ($detail_data->total_comment_star_2 * 2) + ($detail_data->total_comment_star_3 * 3) + ($detail_data->total_comment_star_4 * 4) + ($detail_data->total_comment_star_5 * 5)) / ($detail_data->total_comments * 5) * 100; @endphp
                                    <div class="full-stars" style="width:{{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endif
                        @if ($detail_data->total_comments != 0)
                            <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">
                                @php    $ratings = (($detail_data->total_comment_star_1 * 1) + ($detail_data->total_comment_star_2 * 2) + ($detail_data->total_comment_star_3 * 3) + ($detail_data->total_comment_star_4 * 4) + ($detail_data->total_comment_star_5 * 5)) / $detail_data->total_comments; @endphp
                                ({{ number_format($ratings, 1, '.', ',') }})
                            </p>
                        @endif
                        <Link
                            class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">
                        {{ $detail_data->total_comments }} Reviews
                        </Link>
                    </div>
                    @endif
                    -->
                    <div class="mt-4 mb-1 sm:items-center sm:gap-4 sm:flex">
                        @if ($type !== 'partner')
                        @if ($detail_data->fake_price != 0 && $detail_data->fake_price != null)<span class="text-sm font-medium"><del>Rp.
                                    {{ number_format($detail_data->fake_price, 0, ',', '.') }}</del></span>@endif
                            
                                @if($detail_data->price_type == 0)
                                @if($type == "donasi")
                                <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                                    Rp. {{ number_format($detail_data->collected_amount, 0, ',', '.') }} / <span class="font-medium">Rp. {{ number_format($detail_data->goal_amount, 0, ',', '.') }}</span>
                                </p>
                                @else
                                <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                                    Rp. {{ number_format($detail_data->price, 0, ',', '.') }}
                                </p>
                                @endif
                                @else
                                <p class="text-md font-extrabold text-gray-900 dark:text-white">
                                    Hubungi kami untuk harga terbaik
                                </p>
                                @endif
                                
                            
                            @if ($detail_data->fake_price != 0 && $detail_data->fake_price != null)
                            <span
                                class="ml-1 bg-red-500 text-white text-xs font-medium me-2 px-1 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ round(100 - ($detail_data->price / $detail_data->fake_price) * 100) }}%</span>@endif
                        @endif
                    </div>

                    @if ($type !== 'partner')
                        @if($type == 'webinar')
                        Tanggal Webinar : {{ $detail_data->datetime }}<br>
                        Durasi : {{ $detail_data->duration }} menit
                        @endif
                        @if(count($detail_data->work_units) != 0)
                        <div class="mt-4 mb-1">
                            <span>Unit : </span>
                            @foreach ($detail_data->work_units as $work_unit)
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                                    style="white-space: nowrap;">{{ $work_unit->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        @if($detail_data->is_readystock == 0)
                        <span
                                class="bg-red-100 text-red-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                style="white-space: nowrap;">Preorder</span>
                        @endif
                        @if($detail_data->user->is_verified == 1)
                        <span
                                class="bg-green-100 text-green-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                style="white-space: nowrap;">Verified</span>
                        @else
                        <span
                                class="bg-gray-100 text-gray-800 inline-block text-xs font-medium me-2 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                style="white-space: nowrap;">Unverified</span>
                        @endif
                    @endif

                    <div class="mt-6 mb-4 sm:gap-4 sm:items-center sm:flex sm:mt-4">
                        @guest
                            <Link href="/login"
                                class="bg-green-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-green-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                role="button">
                            Login Untuk Chat Partner
                            </Link>
                        @else
                            @if ($type !== 'partner')
                                @if ($detail_data->user->phone_number)
                                    <a target="_blank"
                                        href="https://api.whatsapp.com/send?phone={{ preg_replace('/^0/', '+62', str_replace(['+', '-'], '', $detail_data->user->phone_number)) }}&text=Halo {{ $detail_data->user->name }}"
                                        class="bg-green-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-green-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                        role="button">
                                        Chat Via Whatsapp
                                    </a>
                                @endif
                                <a href="https://mail.google.com/mail/?view=cm&ui=2&tf=0&fs=1&to={{ $detail_data->user->email }}&su=Pesan {{ $detail_data->user->name }}&body=Halo {{ $detail_data->user->name }}"
                                    class="bg-red-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-red-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    target="_blank" style="background-color: #c5221e;"><i class="fab fa-google"></i> Chat
                                    via Email</a>
                                @if ($detail_data->external_link)
                                <a href="{{$detail_data->external_link}}"
                                    class="bg-red-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-red-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    target="_blank" style="background-color: #0022fd;"><i class="fab fa-google"></i> External Link</a>
                                @endif
                            @else
                                @if ($detail_data->phone_number)
                                    <a target="_blank"
                                        href="https://api.whatsapp.com/send?phone={{ preg_replace('/^0/', '+62', str_replace(['+', '-'], '', $detail_data->phone_number)) }}&text=Halo {{ $detail_data->name }}"
                                        class="bg-green-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-green-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                        role="button">
                                        Chat Via Whatsapp
                                    </a>
                                @endif
                                <a href="https://mail.google.com/mail/?view=cm&ui=2&tf=0&fs=1&to={{ $detail_data->email }}&su=Pesan {{ $detail_data->name }}&body=Halo {{ $detail_data->name }}"
                                    class="bg-red-500 flex items-center justify-center py-2.5 px-5 text-sm font-medium text-white focus:outline-none rounded-lg border border-gray-200 hover:bg-red-500 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    target="_blank" style="background-color: #c5221e;"><i class="fab fa-google"></i> Chat
                                    via Email</a>
                            @endif
                        @endguest
                    </div>

                    @if ($type !== 'partner')


                        <div
                            class="mt-4 p-2 grid mb-8 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 bg-white dark:bg-gray-800">
                            <Link href="/partner/{{ $detail_data->user->slug }}">
                            <figcaption class="flex items-center">
                                <img class="rounded-full" src="{{ $detail_data->user->profile_photo_url }}"
                                    alt="{{ $detail_data->user->name }}"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                                <div class="ml-2 space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                                    <div>{{ $detail_data->user->name }}</div>
                                    @if ($detail_data->user->city)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail_data->user->city->kabupaten_kota }}
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal bergabung :
                                        {{ date('d F Y', strtotime($detail_data->user->created_at)) }}
                                    </div>
                                </div>
                            </figcaption>
                            </Link>
                        </div>
                    @endif

                    @if ($type == 'partner')
                        @if ($detail_data->city)
                            <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                                <div>Lokasi</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $detail_data->city->kabupaten_kota }}
                                </div>
                            </div>
                        @endif
                        @if ($detail_data->address)
                            <div class="space-y-0.5 mt-4 font-medium dark:text-white text-left rtl:text-right ms-3">
                                <div>Alamat</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $detail_data->address }}
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="mt-4 flex items-center gap-2">
                        <p>Share ini</p>
                        <a href="https://api.whatsapp.com/send?text={{ $detail_data->name }}%0a%20%0a{{ Request::fullUrl() }}"
                            target="_blank" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                y="0px" width="30" height="30" viewBox="0 0 48 48">
                                <path fill="#fff"
                                    d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z">
                                </path>
                                <path fill="#fff"
                                    d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z">
                                </path>
                                <path fill="#cfd8dc"
                                    d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z">
                                </path>
                                <path fill="#40c351"
                                    d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z">
                                </path>
                                <path fill="#fff" fill-rule="evenodd"
                                    d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                                    clip-rule="evenodd"></path>
                            </svg></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ Request::fullUrl() }}&quote={{ $detail_data->name }}"
                            target="_blank" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                y="0px" width="30" height="30" viewBox="0 0 48 48">
                                <path fill="#039be5" d="M24 5A19 19 0 1 0 24 43A19 19 0 1 0 24 5Z"></path>
                                <path fill="#fff"
                                    d="M26.572,29.036h4.917l0.772-4.995h-5.69v-2.73c0-2.075,0.678-3.915,2.619-3.915h3.119v-4.359c-0.548-0.074-1.707-0.236-3.897-0.236c-4.573,0-7.254,2.415-7.254,7.917v3.323h-4.701v4.995h4.701v13.729C22.089,42.905,23.032,43,24,43c0.875,0,1.729-0.08,2.572-0.194V29.036z">
                                </path>
                            </svg></a>
                        <a href="http://twitter.com/share?text={{ $detail_data->name }}&url={{ Request::fullUrl() }}"
                            target="_blank" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                y="0px" width="30" height="30" viewBox="0 0 48 48">
                                <path fill="#212121" fill-rule="evenodd"
                                    d="M38,42H10c-2.209,0-4-1.791-4-4V10c0-2.209,1.791-4,4-4h28	c2.209,0,4,1.791,4,4v28C42,40.209,40.209,42,38,42z"
                                    clip-rule="evenodd"></path>
                                <path fill="#fff"
                                    d="M34.257,34h-6.437L13.829,14h6.437L34.257,34z M28.587,32.304h2.563L19.499,15.696h-2.563 L28.587,32.304z">
                                </path>
                                <polygon fill="#fff" points="15.866,34 23.069,25.656 22.127,24.407 13.823,34">
                                </polygon>
                                <polygon fill="#fff" points="24.45,21.721 25.355,23.01 33.136,14 31.136,14">
                                </polygon>
                            </svg></a>
                        <a href="https://telegram.me/share/url?url={{ Request::fullUrl() }}&text=%0a%20{{ $detail_data->name }}"
                            target="_blank" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                y="0px" width="30" height="30" viewBox="0 0 48 48">
                                <path fill="#29b6f6" d="M24 4A20 20 0 1 0 24 44A20 20 0 1 0 24 4Z"></path>
                                <path fill="#fff"
                                    d="M33.95,15l-3.746,19.126c0,0-0.161,0.874-1.245,0.874c-0.576,0-0.873-0.274-0.873-0.274l-8.114-6.733 l-3.97-2.001l-5.095-1.355c0,0-0.907-0.262-0.907-1.012c0-0.625,0.933-0.923,0.933-0.923l21.316-8.468 c-0.001-0.001,0.651-0.235,1.126-0.234C33.667,14,34,14.125,34,14.5C34,14.75,33.95,15,33.95,15z">
                                </path>
                                <path fill="#b0bec5"
                                    d="M23,30.505l-3.426,3.374c0,0-0.149,0.115-0.348,0.12c-0.069,0.002-0.143-0.009-0.219-0.043 l0.964-5.965L23,30.505z">
                                </path>
                                <path fill="#cfd8dc"
                                    d="M29.897,18.196c-0.169-0.22-0.481-0.26-0.701-0.093L16,26c0,0,2.106,5.892,2.427,6.912 c0.322,1.021,0.58,1.045,0.58,1.045l0.964-5.965l9.832-9.096C30.023,18.729,30.064,18.416,29.897,18.196z">
                                </path>
                            </svg></a>
                        {{-- <a href="https://pinterest.com/pin/create/button/?url={{ Request::fullUrl() }}&description={{ $detail_data->name }}. {{ $detail_data->name }}"
                            target="_blank" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                y="0px" width="30" height="30" viewBox="0 0 48 48">
                                <circle cx="24" cy="24" r="20" fill="#E60023"></circle>
                                <path fill="#FFF"
                                    d="M24.4439087,11.4161377c-8.6323242,0-13.2153931,5.7946167-13.2153931,12.1030884	c0,2.9338379,1.5615234,6.5853882,4.0599976,7.7484131c0.378418,0.1762085,0.581543,0.1000366,0.668457-0.2669067	c0.0668945-0.2784424,0.4038086-1.6369019,0.5553589-2.2684326c0.0484619-0.2015381,0.0246582-0.3746338-0.1384277-0.5731201	c-0.8269653-1.0030518-1.4884644-2.8461304-1.4884644-4.5645752c0-4.4115601,3.3399658-8.6799927,9.0299683-8.6799927	c4.9130859,0,8.3530884,3.3484497,8.3530884,8.1369019c0,5.4099731-2.7322998,9.1584473-6.2869263,9.1584473	c-1.9630737,0-3.4330444-1.6238403-2.9615479-3.6153564c0.5654297-2.3769531,1.6569214-4.9415283,1.6569214-6.6584473	c0-1.5354004-0.8230591-2.8169556-2.5299683-2.8169556c-2.006958,0-3.6184692,2.0753784-3.6184692,4.8569336	c0,1.7700195,0.5984497,2.9684448,0.5984497,2.9684448s-1.9822998,8.3815308-2.3453979,9.9415283	c-0.4019775,1.72229-0.2453003,4.1416016-0.0713501,5.7233887l0,0c0.4511108,0.1768799,0.9024048,0.3537598,1.3687744,0.4981079l0,0	c0.8168945-1.3278198,2.0349731-3.5056763,2.4864502-5.2422485c0.2438354-0.9361572,1.2468872-4.7546387,1.2468872-4.7546387	c0.6515503,1.2438965,2.5561523,2.296936,4.5831299,2.296936c6.0314941,0,10.378418-5.546936,10.378418-12.4400024	C36.7738647,16.3591919,31.3823242,11.4161377,24.4439087,11.4161377z">
                                </path>
                            </svg></a> --}}
                            <button class="w-5" @click="$splade.copy()"><img width="50" height="50" src="https://img.icons8.com/ios-filled/50/link--v1.png" alt="link--v1"/></button>
                            <p id="success-copy" style="font-size: 12px; color: green; font-weight: bold;">Salin Berhasil</p>
                    </div>

                    <hr class="mt-4 mb-4 border-gray-200 dark:border-gray-800" />

                    @if ($type !== 'partner')
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ $detail_data->short_description }}
                        </p>
                    @else
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {!! $detail_data->description !!}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($type !== 'partner')
        <section class="rounded-lg bg-white my-8 py-2 antialiased dark:bg-gray-900 md:py-2">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div style="
                                padding: 25px 45px;
                            ">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Deskripsi
                        {{ $type == 'produk' ? 'Produk' : ($type == 'jasa' ? 'Jasa' : ($type == 'webinar' ? 'Webinar' : ($type == 'donasi' ? 'Donasi' : ''))) }}
                    </h2>
                    {!! $detail_data->description !!}
                </div>
            </div>
        </section>

        <!--
        @auth
            <section class="rounded-lg bg-white py-2 antialiased dark:bg-gray-900 md:py-2" style="padding: 25px 45px;">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ is_null($check_user_comment) ? 'Tambah Review' : 'Review Anda' }}
                        </h2>
                    </div>
                    <div class="py-4">
                        @if (is_null($check_user_comment))
                            @if ($type == 'produk')
                                <x-splade-form :default="['product_id' => Crypt::encrypt($detail_data->id), 'ratings' => 5]"
                                    class="space-y-4" action="{{ route('product_comments.store') }}" preserve-scroll>
                                    <div class="relative" style="margin-bottom: 35px;">
                                        <x-splade-input type="range" name="ratings" label="Rating" min="1" max="5" value="5" step="1" />
                                        <span class="text-sm text-gray-500 dark:text-gray-400 absolute start-0 -bottom-6"><b>1
                                                star</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-1/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 25%;"><b>2 stars</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-2/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 50%;"><b>3 stars</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-3/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 75%;"><b>4 stars</b></span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 absolute end-0 -bottom-6"><b>5
                                                stars</b></span>
                                    </div>
                                    <x-splade-textarea name="comment" label="Review" />
                                    <x-splade-submit label="Save" />
                                </x-splade-form>
                            @endif
                            @if ($type == 'jasa')
                                <x-splade-form :default="['service_id' => Crypt::encrypt($detail_data->id), 'ratings' => 5]"
                                    class="space-y-4" action="{{ route('service_comments.store') }}" preserve-scroll>
                                    <div class="relative" style="margin-bottom: 35px;">
                                        <x-splade-input type="range" name="ratings" label="Rating" min="1" max="5" value="5" step="1" />
                                        <span class="text-sm text-gray-500 dark:text-gray-400 absolute start-0 -bottom-6"><b>1
                                                star</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-1/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 25%;"><b>2 stars</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-2/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 50%;"><b>3 stars</b></span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 absolute start-3/4 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6"
                                            style="inset-inline-start: 75%;"><b>4 stars</b></span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 absolute end-0 -bottom-6"><b>5
                                                stars</b></span>
                                    </div>
                                    <x-splade-textarea name="comment" label="Review" />
                                    <x-splade-submit label="Save" />
                                </x-splade-form>
                            @endif
                        @else
                            <div class="gap-3 py-2 sm:flex sm:items-start">
                                <div class="shrink-0 space-y-2 sm:w-48 md:w-72">
                                    <div class="flex items-center gap-0.5">
                                        @if ($check_user_comment->ratings >= 1)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($check_user_comment->ratings >= 2)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($check_user_comment->ratings >= 3)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($check_user_comment->ratings >= 4)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($check_user_comment->ratings >= 5)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="space-y-0.5">
                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $check_user_comment->user->name }}
                                        </p>
                                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $check_user_comment->created_at }}
                                        </p>
                                    </div>


                                </div>

                                <div class="mt-4 min-w-0 flex-1 space-y-4 sm:mt-0">
                                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">
                                        {{ $check_user_comment->comment }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endauth

        <section class="rounded-lg bg-white py-2 my-8 antialiased dark:bg-gray-900 md:py-2" style="padding: 25px 45px;">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div class="gap-3 py-2 sm:flex sm:items-start">


                    <div class="shrink-0 space-y-2 sm:w-48 md:w-72 mt-2 flex items-center gap-2 sm:mt-2">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Reviews</h2>
                        @if ($detail_data->total_comments != 0)
                            <div class="flex items-center gap-0.5">
                                <div class="ratings">
                                    <div class="empty-stars"></div>

                                    <div class="full-stars" style="width:{{ $percentage }}%"></div>
                                </div>
                            </div>
                            <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">
                                ({{ number_format($ratings, 1, '.', ',') }})</p>
                        @endif
                        <Link href="#"
                            class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">
                        {{ $detail_data->total_comments }} Reviews </Link>
                    </div>
                    <div class="mt-4 min-w-0 flex-1 space-y-4 sm:mt-4">
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}" preserve-scroll>
                        <button type="button"
                            class="mr-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Semua
                            ({{ $detail_data->total_comments }})</button></Link>
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}?stars=5" preserve-scroll>
                        <button type="button"
                            class="ml-1 mr-1 mb-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Bintang
                            5 ({{ $detail_data->total_comment_star_5 }})</button></Link>
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}?stars=4" preserve-scroll>
                        <button type="button"
                            class="ml-1 mr-1 mb-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Bintang
                            4 ({{ $detail_data->total_comment_star_4 }})</button></Link>
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}?stars=3" preserve-scroll>
                        <button type="button"
                            class="ml-1 mr-1 mb-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Bintang
                            3 ({{ $detail_data->total_comment_star_3 }})</button></Link>
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}?stars=2" preserve-scroll>
                        <button type="button"
                            class="ml-1 mr-1 mb-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Bintang
                            2 ({{ $detail_data->total_comment_star_2 }})</button></Link>
                        <Link href="{{ Request::fullUrlWithoutQuery(['page', 'stars']) }}?stars=1" preserve-scroll>
                        <button type="button"
                            class="ml-1 mb-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Bintang
                            1 ({{ $detail_data->total_comment_star_1 }})</button></Link>
                    </div>
                </div>


                {{-- <x-splade-lazy> --}}
                    {{-- <x-slot:placeholder> The items are loading... </x-slot:placeholder> --}}
                    <div class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach ($comments as $idx => $item)
                            <div class="gap-3 py-2 sm:flex sm:items-start">
                                <div class="shrink-0 space-y-2 sm:w-48 md:w-72">
                                    <div class="flex items-center gap-0.5">
                                        @if ($item->ratings >= 1)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($item->ratings >= 2)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($item->ratings >= 3)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($item->ratings >= 4)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                        @if ($item->ratings >= 5)
                                            <svg class="h-4 w-4 text-yellow-300" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="space-y-0.5">
                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $item->user->name }}
                                        </p>
                                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $item->created_at }}
                                        </p>
                                    </div>


                                </div>

                                <div class="mt-4 min-w-0 flex-1 space-y-4 sm:mt-0">
                                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">{{ $item->comment }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    {{ $comments->links() }}
                    {{--
                </x-splade-lazy> --}}

            </div>
        </section>
        -->

        @if (count($related) != 0)
            <section class="mt-4 pb-2 antialiased dark:bg-gray-900">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                                {{ $type == 'produk' ? 'Produk' : ($type == 'jasa' ? 'Jasa' : ($type == 'webinar' ? 'Webinar' : ($type == 'donasi' ? 'Donasi' : ''))) }} Terkait
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="{{ $type == 'produk' ? '/search?type=produk' : '/search?type=jasa' }}"><button
                                type="button"
                                class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                                Lihat {{ $type == 'produk' ? 'Produk' : ($type == 'jasa' ? 'Jasa' : ($type == 'webinar' ? 'Webinar' : ($type == 'donasi' ? 'Donasi' : ''))) }} selengkapnya
                            </button></Link>
                        </div>
                    </div>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-6">
                        {{-- <x-splade-lazy> --}}
                            {{-- <x-slot:placeholder> loading... </x-slot:placeholder> --}}
                            @foreach ($related as $idx => $item)
                                <Link href="{{ $type == 'produk' ? '/produk/' : '/jasa/' }}{{ $item->slug }}">
                                <div
                                    class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                                    <div class=" w-full">
                                        @if ($item->fake_price != 0 && $item->fake_price != null)
                                            <div class="discount-tag">
                                                <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                            </div>
                                        @endif
                                        <img class="rounded-top mx-auto h-full dark:hidden" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                        <img class="rounded-top mx-auto hidden h-full dark:block" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                    </div>
                                    <div  style="height: 180px;">
                                        @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="mt-1" style="height: 35px;position: absolute;margin-top: -40px;margin-left: 5px;"/>
                                    @endif
                                        @if($item->admin_category)
                                            <div class="tag-product">{{$item->admin_category->name}}
                                            </div>
                                        @endif
                                        <div class="pt-1 p-2">
                                        <p class="line-clamp-2 text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}</p>

                                        <p
                                            class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                            {{ $item->short_description }}
                                        </p>

                                        <div class="mt-1 flex items-center gap-2">


                                            @if ($item->total_comments != 0)
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @php                $ratings = (($item->total_comment_star_1 * 1) + ($item->total_comment_star_2 * 2) + ($item->total_comment_star_3 * 3) + ($item->total_comment_star_4 * 4) + ($item->total_comment_star_5 * 5)) / $item->total_comments; @endphp
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
                                                    style="font-size: 13px"><del> Rp.
                                                        {{ number_format($item->fake_price, 0, ',', '.') }}</del>
                                                </span>
                                                        @endif
                                            </p>

                                        </div>
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
                                        </div>
                                    </div>
                                </div>
                                </Link>
                            @endforeach
                            {{--
                        </x-splade-lazy> --}}

                    </div>
                </div>
            </section>
        @endif

        @if (count($user_catalogs) != 0)
            <section class="mt-4 pb-2 antialiased dark:bg-gray-900">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                                {{ $type == 'produk' ? 'Produk' : ($type == 'jasa' ? 'Jasa' : ($type == 'webinar' ? 'Webinar' : ($type == 'donasi' ? 'Donasi' : ''))) }} Lainnya dari
                                {{ $detail_data->user->name }}
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/search?type={{ $type }}&user={{ $detail_data->user->id }}"><button type="button"
                                class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                                Lihat {{ $type == 'produk' ? 'Produk' : ($type == 'jasa' ? 'Jasa' : ($type == 'webinar' ? 'Webinar' : ($type == 'donasi' ? 'Donasi' : ''))) }} selengkapnya
                            </button></Link>
                        </div>
                    </div>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-6">
                        {{-- <x-splade-lazy> --}}
                            {{-- <x-slot:placeholder> loading... </x-slot:placeholder> --}}
                            @foreach ($user_catalogs as $idx => $item)
                                <Link href="/{{ $type }}/{{ $item->slug }}">
                                <div
                                    class="rounded-lg bg-white shadow-md dark:border-gray-700 dark:bg-gray-800 mb-6">
                                    <div class=" w-full">
                                        @if ($item->fake_price != 0 && $item->fake_price != null)
                                            <div class="discount-tag">
                                                <span><b>{{ round(100 - ($item->price / $item->fake_price) * 100) }}%</b></span>
                                            </div>
                                        @endif
                                        <img class="rounded-top mx-auto h-full dark:hidden" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                        <img class="rounded-top mx-auto hidden h-full dark:block" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                    </div>
                                    
                                    <div  style="height: 180px;">
                                        @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="mt-1" style="height: 35px;position: absolute;margin-top: -40px;margin-left: 5px;"/>
                                    @endif
                                        @if($item->admin_category)
                                            <div class="tag-product">{{$item->admin_category->name}}
                                            </div>
                                        @endif
                                        <div class="pt-1 p-2">
                                        <p class="line-clamp-2 text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">{{ $item->name }}</p>

                                        <p
                                            class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                            {{ $item->short_description }}
                                        </p>

                                        <div class="mt-1 flex items-center gap-2">


                                            @if ($item->total_comments != 0)
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @php                $ratings = (($item->total_comment_star_1 * 1) + ($item->total_comment_star_2 * 2) + ($item->total_comment_star_3 * 3) + ($item->total_comment_star_4 * 4) + ($item->total_comment_star_5 * 5)) / $item->total_comments; @endphp
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
                                                </span><br />
                                                    @if ($item->fake_price != 0 && $item->fake_price != null)<span
                                                    style="font-size: 13px"><del> Rp.
                                                        {{ number_format($item->fake_price, 0, ',', '.') }}</del>
                                                    <span style="color: red; font-size: 13px;"><b>
                                                            ({{ round(100 - ($item->price / $item->fake_price) * 100) }}%)
                                                        </b></span></span>
                                                        @endif
                                            </p>

                                        </div>
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
                                        </div>
                                    </div>
                                </div>
                                </Link>
                            @endforeach
                            {{--
                        </x-splade-lazy> --}}

                    </div>
                </div>
            </section>
        @endif
    @else
        @if (count($related) != 0)
            <section class="mt-4 pb-2 antialiased dark:bg-gray-900">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                                Jasa {{ $detail_data->name }}</h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/search?type=jasa&user={{ $detail_data->id }}"><button type="button"
                                class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                                Lihat jasa selengkapnya
                            </button></Link>
                        </div>
                    </div>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-6">
                        {{-- <x-splade-lazy> --}}
                            {{-- <x-slot:placeholder> loading... </x-slot:placeholder> --}}
                            @foreach ($related as $idx => $item)
                                <Link href="/jasa/{{ $item->slug }}">
                                <div
                                    class="rounded-lg bg-white p-2 shadow-md dark:border-gray-700 dark:bg-gray-800">
                                    <div class=" w-full">
                                        <img class="mx-auto h-full dark:hidden" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                        <img class="mx-auto hidden h-full dark:block" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                    </div>
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="mt-1" style="height: 35px;position: absolute;margin-top: -40px;margin-left: 5px;"/>
                                    @endif
                                    @if($item->admin_category)
                            <div class="tag-product">{{$item->admin_category->name}}
                            </div>
                            @endif
                                    <div class="pt-1 p-2">
                                        <p class="line-clamp-2 text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}</p>

                                        <p
                                            class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                            {{ $item->short_description }}
                                        </p>

                                        <div class="mt-1 flex items-center gap-2">


                                            @if ($item->total_comments != 0)
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @php                $ratings = (($item->total_comment_star_1 * 1) + ($item->total_comment_star_2 * 2) + ($item->total_comment_star_3 * 3) + ($item->total_comment_star_4 * 4) + ($item->total_comment_star_5 * 5)) / $item->total_comments; @endphp
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
                                                </span><br />
                                                    @if ($item->fake_price != 0 && $item->fake_price != null)<span
                                                    style="font-size: 13px"><del> Rp.
                                                        {{ number_format($item->fake_price, 0, ',', '.') }}</del>
                                                    <span style="color: red; font-size: 13px;"><b>
                                                            ({{ round(100 - ($item->price / $item->fake_price) * 100) }}%)
                                                        </b></span></span>
                                                        @endif
                                            </p>

                                        </div>
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
                                    </div>
                                </div>
                                </Link>
                            @endforeach
                            {{--
                        </x-splade-lazy> --}}

                    </div>
                </div>
            </section>
        @endif

        @if (count($user_catalogs) != 0)
            <section class="mt-4 pb-2 antialiased dark:bg-gray-900">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mb-4 items-center justify-between flex sm:space-y-0 md:mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                                Produk {{ $detail_data->name }}
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/search?type=produk&user={{ $detail_data->id }}"><button type="button"
                                class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                                Lihat produk selengkapnya
                            </button></Link>
                        </div>
                    </div>
                    <div class="mb-4 grid gap-4 grid-cols-2 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-6">
                        {{-- <x-splade-lazy> --}}
                            {{-- <x-slot:placeholder> loading... </x-slot:placeholder> --}}
                            @foreach ($user_catalogs as $idx => $item)
                                <Link href="/produk/{{ $item->slug }}">
                                <div
                                    class="rounded-lg border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
                                    <div class=" w-full">
                                        <img class="mx-auto h-full dark:hidden" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                        <img class="mx-auto hidden h-full dark:block" src="{{ $item->image_thumb }}"
                                            alt="{{ $item->name }}" />
                                    </div>
                                    @if ($item->admin_promotion_category)
                                        <img src="{{ $item->admin_promotion_category->image }}" class="mt-1" style="height: 35px;position: absolute;margin-top: -40px;margin-left: 5px;"/>
                                    @endif
                                    @if($item->admin_category)
                            <div class="tag-product">{{$item->admin_category->name}}
                            </div>
                            @endif
                                    <div class="pt-1">
                                        <p class="line-clamp-2 text-md font-semibold leading-tight text-gray-900 hover:underline dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1.5rem;max-height: 3rem;white-space: initial;font-size: 14px">
                                        {{ $item->name }}</p>

                                        <p
                                            class="text-md leading-tight text-gray-900 dark:text-white pb-1" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;line-height: 1rem;max-height: 3rem;white-space: initial;font-size: 10px">
                                            {{ $item->short_description }}
                                        </p>

                                        <div class="mt-1 flex items-center gap-2">


                                            @if ($item->total_comments != 0)
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                        <path
                                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @php                $ratings = (($item->total_comment_star_1 * 1) + ($item->total_comment_star_2 * 2) + ($item->total_comment_star_3 * 3) + ($item->total_comment_star_4 * 4) + ($item->total_comment_star_5 * 5)) / $item->total_comments; @endphp
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
                                            </span><br />
                                                    @if ($item->fake_price != 0 && $item->fake_price != null)<span
                                                    style="font-size: 13px"><del> Rp.
                                                        {{ number_format($item->fake_price, 0, ',', '.') }}</del>
                                                    <span style="color: red; font-size: 13px;"><b>
                                                            ({{ round(100 - ($item->price / $item->fake_price) * 100) }}%)
                                                        </b></span></span>
                                                        @endif
                                            </p>

                                        </div>
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
                                    </div>
                                </div>
                                </Link>
                            @endforeach
                            {{--
                        </x-splade-lazy> --}}

                    </div>
                </div>
            </section>
        @endif
    @endif

    <x-splade-script>
        document.getElementById("success-copy").style.display = "none";
        $splade.copy = function () {
            navigator.clipboard.writeText('{{ Request::fullUrl() }}');
            document.getElementById("success-copy").style.display = "block";
        };
    </x-splade-script>



</x-main-layout>