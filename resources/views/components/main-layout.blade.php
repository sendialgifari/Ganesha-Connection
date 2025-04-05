<div>

    @auth
    @if(is_null(auth()->user()->is_public))
    <x-splade-modal opened>
        {{-- <p class="pb-4">Pilih Status Anda</p> --}}
        <x-splade-form name="filter" method="PUT" action="{{ route('user_update_public', ['id' => auth()->user()->id])}}">
            {{-- <x-splade-select name="is_public">
                <option value="0">Civitas ITB</option>
                <option value="1">Umum</option>
            </x-splade-select> --}}
            <x-splade-radios name="is_public" label="Pilih Status Anda" :options="$is_public" />
            <x-splade-button class="font-bold bg-indigo-500 hover:bg-indigo-700 text-white mt-4" style="width: 100%">Submit</x-splade-button>
        </x-splade-form>
    </x-splade-modal>
    @endif
    @endauth
    <nav class="block sm:hidden fixed w-full z-20 top-0 start-0 p-1" style="z-index: 99; background: #005aab !important;">

            <x-splade-toggle>
                <div class="flex md:order-2 items-center">


                    <Link href="/" class="flex items-center space-x-3 rtl:space-x-reverse" style="margin-right: 5px;">
                        <img src="{{asset('images/logo-g.png')}}" class="h-8 me-3" alt="Gannect Logo" />
                    </Link>


                   <x-splade-form :default="['q' => request('q')]" class="flex items-center mx-auto" style="width: 100%; margin-right:8px" method="GET" action="/search">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <x-splade-input style="font-size: 14px; padding: 2px 10px; border-radius: 3px;" name="q" id="simple-search" placeholder="Cari di Ganesha Connection..." required />
                        </div>
                        <x-splade-submit
                            class="ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" style="font-size: 12px; padding: 1px 10px;"
                            label="Cari" />

                    </x-splade-form>


                    @auth
                        <x-splade-dropdown>
                            <x-slot:trigger>
                                <button
                                    class="mt-1 flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->profile_photo_url }}"
                                        alt="{{ auth()->user()->name }}">
                                </button>
                            </x-slot>
                            <div class="w-60 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <div class="px-4 py-3">
                                    <span
                                        class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                    <span
                                        class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="py-2" aria-labelledby="user-menu-button">
                                    <li>
                                        <Link href="{{ url('/dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        Dashboard</Link>
                                    </li>
                                    {{-- <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                            </li> --}}
                                    <li>
                                        <x-splade-form :action="route('logout')">
                                            <x-dropdown-link as="button">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </x-splade-form>
                                        {{-- <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Log
                                            out</a> --}}
                                    </li>
                                </ul>
                            </div>
                        </x-splade-dropdown>
                    @else
                        <Link href="{{ route('login') }}"
                            class="font-semibold text-white hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">

                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                        </svg>




                        </Link>

                    @endauth

                </div>
            </x-splade-toggle>
    </nav>

    <nav class="block sm:hidden fixed w-full z-20 bottom-0 start-0 pl-5 pr-4 pt-2 pb-2" style="z-index: 99; background: white !important; -webkit-box-shadow: 2px 4px 24px -9px rgba(0,0,0,0.75); -moz-box-shadow: 2px 4px 24px -9px rgba(0,0,0,0.75); box-shadow: 2px 4px 24px -9px rgba(0,0,0,0.75);">

        <div class="grid grid-cols-5 md:grid-cols-5 gap-2" style="align-items: center; text-align: center;">
            <x-splade-dropdown>
                <x-slot:trigger>
                    <div style="height: 50px;" href="javascript:function() { return false; }">
                        <div class="icon-product"></div>
                        <div style="font-size: 10px;">Produk</div>
                    </div>
                </x-slot>
                <div class="w-48 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                    <x-dropdown-link href="/search?type=produk">
                        Semua Produk
                    </x-dropdown-link>
                    @foreach ($filter_product_categories as $id => $item)
                        <x-dropdown-link href="/search?q=&type=produk&pmin=&pmax=&rt=&cat={{$id}}&adm_cat=&adm_p_cat=&unit=&ob=&user=">
                            {{$item}}
                        </x-dropdown-link>
                    @endforeach
                </div>
            </x-splade-dropdown>
            <x-splade-dropdown>
                <x-slot:trigger>
                    <div style="height: 50px;" href="javascript:function() { return false; }">
                        <div class="icon-jasa"></div>
                        <div style="font-size: 10px;">Jasa</div>
                    </div>
                </x-slot>
                <div class="w-48 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                    <x-dropdown-link href="/search?type=jasa">
                        Semua Jasa
                    </x-dropdown-link>
                    @foreach ($filter_service_categories as $id => $item)
                        <x-dropdown-link href="/search?q=&type=jasa&pmin=&pmax=&rt=&cat={{$id}}&adm_cat=&adm_p_cat=&unit=&ob=&user=">
                            {{$item}}
                        </x-dropdown-link>
                    @endforeach
                </div>
            </x-splade-dropdown>
            <x-splade-dropdown>
                <x-slot:trigger>
                    <div style="height: 50px;" href="javascript:function() { return false; }">
                        <div class="icon-jenis"></div>
                        <div style="font-size: 10px;">Jenis</div>
                    </div>
                </x-slot>
                <div class="w-48 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                    <x-dropdown-link href="/search?adm_cat=all">
                        Semua Jenis Produk Jasa
                    </x-dropdown-link>
                    @foreach ($filter_admin_categories as $id => $item)
                        <x-dropdown-link href="/search?q=&type=&pmin=&pmax=&rt=&cat=&adm_cat={{$id}}&adm_p_cat=&unit=&ob=&user=">
                            {{$item}}
                        </x-dropdown-link>
                    @endforeach
                </div>
            </x-splade-dropdown>
            <x-splade-dropdown>
                <x-slot:trigger>
                    <div style="height: 50px;" href="javascript:function() { return false; }">
                        <div class="icon-department"></div>
                        <div style="font-size: 10px;">Departemen</div>
                    </div>
                </x-slot>
                <div class="w-48 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                    <x-dropdown-link href="/search?adm_p_cat=all">
                        Semua Departemen
                    </x-dropdown-link>
                    @foreach ($filter_admin_promotion_categories as $id => $item)
                        <x-dropdown-link href="/search?q=&type=&pmin=&pmax=&rt=&cat=&adm_cat=&adm_p_cat={{$id}}&unit=&ob=&user=">
                            {{$item}}
                        </x-dropdown-link>
                    @endforeach
                </div>
            </x-splade-dropdown>
            <x-splade-dropdown>
                <x-slot:trigger>
                    <div style="height: 50px;" href="javascript:function() { return false; }">
                        <div class="icon-unit"></div>
                        <div style="font-size: 10px;">Unit</div>
                    </div>
                </x-slot>
                <div class="w-48 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                    <x-dropdown-link href="/search?unit=all">
                        Semua Unit Kerja
                    </x-dropdown-link>
                    @foreach ($filter_work_units as $id => $item)
                        <x-dropdown-link href="/search?q=&type=&pmin=&pmax=&rt=&cat=&adm_cat=&adm_p_cat=&unit={{$id}}&ob=&user=">
                            {{$item}}
                        </x-dropdown-link>
                    @endforeach
                </div>
            </x-splade-dropdown>
            {{-- <x-splade-form name="filter" :default="[
                'q' => request('q'),
                'type' => 'produk',
                'pmin' => request('pmin'),
                'pmax' => request('pmax'),
                'rt' => request('rt'),
                'cat' => '',
                'adm_cat' => request('adm_cat'),
                'adm_p_cat' => request('adm_p_cat'),
                'unit' => request('unit'),
                'ob' => request('ob'),
                'user' => request('user'),
            ]" method="GET" action="/search"
                :submit-on-change="['cat']">
                    <x-splade-select name="cat" :options="$filter_product_categories"  style="height: 32px; width: 32px; background: #333;" />
                    <span style="font-size: 12px; color: #222;">Produk</span>
            </x-splade-form>
            <x-splade-form name="filter" :default="[
                'q' => request('q'),
                'type' => 'jasa',
                'pmin' => request('pmin'),
                'pmax' => request('pmax'),
                'rt' => request('rt'),
                'cat' => '',
                'adm_cat' => request('adm_cat'),
                'adm_p_cat' => request('adm_p_cat'),
                'unit' => request('unit'),
                'ob' => request('ob'),
                'user' => request('user'),
            ]" method="GET" action="/search"
                :submit-on-change="['cat']">
                    <x-splade-select name="cat" :options="$filter_service_categories"  style="height: 32px; width: 32px; background: #333;"/>
                    <span style="font-size: 12px; color: #222;">Jasa</span>
            </x-splade-form>
            <x-splade-form name="filter" :default="[
                'q' => request('q'),
                'type' => request('type'),
                'pmin' => request('pmin'),
                'pmax' => request('pmax'),
                'rt' => request('rt'),
                'cat' => request('cat'),
                'adm_cat' => '',
                'adm_p_cat' => request('adm_p_cat'),
                'unit' => request('unit'),
                'ob' => request('ob'),
                'user' => request('user'),
            ]" method="GET" action="/search"
                :submit-on-change="['adm_cat']">
                <x-splade-select name="adm_cat" :options="$filter_admin_categories"  style="height: 32px; width: 32px; background: #333;" />
                <span style="font-size: 12px; color: #222;">Jenis</span>
            </x-splade-form>
            <x-splade-form name="filter" :default="[
                'q' => request('q'),
                'type' => request('type'),
                'pmin' => request('pmin'),
                'pmax' => request('pmax'),
                'rt' => request('rt'),
                'cat' => request('cat'),
                'adm_cat' => request('adm_cat'),
                'adm_p_cat' => '',
                'unit' => request('unit'),
                'ob' => request('ob'),
                'user' => request('user'),
            ]" method="GET" action="/search"
                :submit-on-change="['adm_p_cat']">
                <x-splade-select name="adm_p_cat" :options="$filter_admin_promotion_categories"  style="height: 32px; width: 32px; background: #333;" />
                <span style="font-size: 12px; color: #222;">Departemen</span>
            </x-splade-form>
            <x-splade-form name="filter" :default="[
                'q' => request('q'),
                'type' => request('type'),
                'pmin' => request('pmin'),
                'pmax' => request('pmax'),
                'rt' => request('rt'),
                'cat' => request('cat'),
                'adm_cat' => request('adm_cat'),
                'adm_p_cat' => request('adm_p_cat'),
                'unit' => '',
                'ob' => request('ob'),
                'user' => request('user'),
            ]" method="GET" action="/search"
                :submit-on-change="['unit']">
                    <x-splade-select name="unit" :options="$filter_work_units" style="height: 32px; width: 32px; background: #333;"/>
                    <span style="font-size: 12px; color: #222;">Unit</span>
            </x-splade-form> --}}
        </div>
    </nav>



    <nav class="hidden sm:block fixed w-full z-20 top-0 start-0"
        style="z-index: 99; background: #005aab !important;">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <Link href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{asset('images/logo-ganect-white.png')}}" class="h-8 me-3" alt="Gannect Logo" />
                <img src="{{asset('images/logo-itb.png')}}" class="h-8 me-3" alt="Gannect Logo" />
            </Link>
            <x-splade-toggle>
                <div class="flex md:order-2 items-center">

                    <button @click.prevent="toggle"
                        class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 me-1">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>

                    @auth
                        <x-splade-dropdown>
                            <x-slot:trigger>
                                <button
                                    class="mt-1 flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->profile_photo_url }}"
                                        alt="{{ auth()->user()->name }}">
                                </button>
                            </x-slot>
                            <div class="w-60 mt-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <div class="px-4 py-3">
                                    <span
                                        class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                    <span
                                        class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="py-2" aria-labelledby="user-menu-button">
                                    <li>
                                        <Link href="{{ url('/dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        Dashboard</Link>
                                    </li>
                                    {{-- <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                            </li> --}}
                                    <li>
                                        <x-splade-form :action="route('logout')">
                                            <x-dropdown-link as="button">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </x-splade-form>
                                        {{-- <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Log
                                            out</a> --}}
                                    </li>
                                </ul>
                            </div>
                        </x-splade-dropdown>
                    @else
                        <Link href="{{ route('login') }}"
                            class="font-semibold text-white hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Log in</Link>

                        @if ($canRegister)
                            <Link href="{{ route('register') }}"
                                class="ml-4 font-semibold text-white hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Register</Link>
                        @endif
                    @endauth
                </div>


                <div class="relative hidden md:block">
                    <x-splade-form :default="['q' => request('q')]" class="flex items-center mx-auto" style="width: 600px;" method="GET" action="/search">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <x-splade-input name="q" id="simple-search" placeholder="Cari Apa Saja di Ganesha Connection..." required />
                        </div>
                        <x-splade-submit
                            class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            label="Search" />
                    </x-splade-form>
                </div>



                <div v-show="toggled" class="items-center justify-between w-full md:flex md:w-auto md:order-1"
                    id="navbar-search">
                    <div class="relative mt-3">
                        <x-splade-form class="flex items-center max-w-sm mx-auto" method="GET" action="/search">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <x-splade-input name="q" id="simple-search" placeholder="Cari ..." required />
                            </div>
                            <x-splade-submit
                                class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                label="Search" />
                        </x-splade-form>
                    </div>
                </div>


            </x-splade-toggle>
        </div>
    </nav>


    <nav class="block sm:hidden" style="padding-top: 40px;">
    </nav>


    <nav class="hidden sm:block" style="padding-top: 70px; background: #0b72cf;">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <li>
                        <Link href="/" class="text-white dark:text-white hover:underline" aria-current="page">
                        Home</Link>
                    </li>
                    <li>
                        <Link href="/search?type=produk" class="text-white dark:text-white hover:underline">Produk
                        </Link>
                    </li>
                    <li>
                        <Link href="/search?type=jasa" class="text-white dark:text-white hover:underline">Jasa</Link>
                    </li>
                    <li>
                        <Link href="/search?type=webinar" class="text-white dark:text-white hover:underline">Informasi Webinar</Link>
                    </li>
                    <li>
                        <Link href="/search?type=donasi" class="text-white dark:text-white hover:underline">Donasi</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="relative min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
        <div class="mx-auto w-full max-w-screen-xl">
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <footer class="bg-white dark:bg-gray-900">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <Link href="/" class="flex items-center">
                        <img src="{{asset('images/logo-ganect.png')}}" class="h-8 me-3" alt="Gannect Logo" />
                    </Link>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-8 sm:grid-cols-2">
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Jelajahi Kami
                        </h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            @foreach($static_pages as $idx => $val)
                            @if($idx <= ceil(count($static_pages) / 2) - 1)
                            <li class="mb-2">
                                <Link href="/static/{{$val->slug}}" class="hover:underline">{{$val->name}}</Link>
                            </li>
                            @endif
                            @endforeach
                            
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">&nbsp;
                        </h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            @foreach($static_pages as $idx => $val)
                            @if($idx > ceil(count($static_pages) / 2) - 1)
                            <li class="mb-2">
                                <Link href="/static/{{$val->slug}}" class="hover:underline">{{$val->name}}</Link>
                            </li>
                            @endif
                            @endforeach
                            
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-4" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{date('Y')}} <Link
                        href="/" class="hover:underline">Ganesha Connection</Link>. All Rights Reserved.
                </span>
                <div class="flex mt-4 sm:justify-center sm:mt-0">
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                          </svg>
                          
                        <span class="sr-only">Facebook</span>
                    </a>
                    <a href="https://www.instagram.com/ganeshaconnection" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-5 h-5 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                          </svg>
                          
                        <span class="sr-only">Instagram</span>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-5 h-5 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                          </svg>                          
                        <span class="sr-only">X</span>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-5 h-5 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M21.7 8.037a4.26 4.26 0 0 0-.789-1.964 2.84 2.84 0 0 0-1.984-.839c-2.767-.2-6.926-.2-6.926-.2s-4.157 0-6.928.2a2.836 2.836 0 0 0-1.983.839 4.225 4.225 0 0 0-.79 1.965 30.146 30.146 0 0 0-.2 3.206v1.5a30.12 30.12 0 0 0 .2 3.206c.094.712.364 1.39.784 1.972.604.536 1.38.837 2.187.848 1.583.151 6.731.2 6.731.2s4.161 0 6.928-.2a2.844 2.844 0 0 0 1.985-.84 4.27 4.27 0 0 0 .787-1.965 30.12 30.12 0 0 0 .2-3.206v-1.516a30.672 30.672 0 0 0-.202-3.206Zm-11.692 6.554v-5.62l5.4 2.819-5.4 2.801Z" clip-rule="evenodd"/>
                          </svg>                          
                        <span class="sr-only">Youtube</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</div>
