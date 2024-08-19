<?php

namespace App\Http\Controllers;

use App\Models\WorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\Catalog;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceComment;
use App\Models\StaticPage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Auth;
use DB;
use Hash;
use Intervention\Image\Laravel\Facades\Image;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\Splade\Facades\SEO;
use ProtoneMedia\Splade\Facades\Splade;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        SEO::macro('openGraphLocale', function (string $value) {
            return $this->metaByProperty('og:locale', $value);
        });

        SEO::title('Ganesha Connection')
            ->openGraphLocale('id')
            ->description('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection')
            ->keywords('ganesha connection, ganesha, itb, connection, produk itb');

        SEO::openGraphType('website');
        SEO::openGraphSiteName('Ganesha Connection');
        SEO::openGraphTitle('Ganesha Connection');

        SEO::macro('openGraphDescription', function (string $value) {
            return $this->metaByProperty('og:description', $value);
        });
        SEO::openGraphDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');

        SEO::openGraphUrl($request->fullUrl());
        SEO::openGraphImage(url('/') . '/images/favicon.ico');

        SEO::twitterCard('summary_large_image');
        SEO::twitterSite('Ganesha Connection');
        SEO::twitterTitle('Ganesha Connection');
        SEO::twitterDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');
        SEO::twitterImage(url('/') . '/images/favicon.ico');

        return view('index', [
            'sliders' => Splade::onLazy(fn() => Slider::orderBy('id', 'DESC')->get()),
            'product_categories_selected' => ProductCategory::where('is_selected', 1)->orderBy('name', 'ASC')->limit(4)->get(),
            'service_categories_selected' => ServiceCategory::where('is_selected', 1)->orderBy('name', 'ASC')->limit(4)->get(),
            'product_selected' => Product::where('is_selected', 1)->orderBy('name', 'ASC')->limit(6)->get(),
            'service_selected' => Service::where('is_selected', 1)->orderBy('name', 'ASC')->limit(6)->get(),
            'partner_selected' => User::where('is_selected', 1)->orderBy('name', 'ASC')->limit(8)->get(),
            // 'products' => Splade::onLazy(fn() => Product::latest()->limit(10)->get()),
            // 'services' => Splade::onLazy(fn() => Service::latest()->limit(10)->get()),
            'products' => Product::latest()->limit(10)->get(),
            'services' => Service::latest()->limit(10)->get(),
        ]);
    }

    public function static_page(Request $request, $slug)
    {
        $detail_data = StaticPage::where('slug', $slug)->first();

        SEO::macro('openGraphLocale', function (string $value) {
            return $this->metaByProperty('og:locale', $value);
        });

        SEO::title($detail_data->name . ' | Ganesha Connection')
            ->openGraphLocale('id')
            ->description($detail_data->name)
            ->keywords($detail_data->name . ', Ganesha Connection');

        SEO::openGraphType('website');
        SEO::openGraphSiteName('Ganesha Connection');
        SEO::openGraphTitle($detail_data->name . ' | Ganesha Connection');

        SEO::macro('openGraphDescription', function (string $value) {
            return $this->metaByProperty('og:description', $value);
        });
        SEO::openGraphDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');

        SEO::openGraphUrl($request->fullUrl());
        SEO::openGraphImage(url('/') . '/images/favicon.ico');

        SEO::twitterCard('summary_large_image');
        SEO::twitterSite('Ganesha Connection');
        SEO::twitterTitle($detail_data->name . ' | Ganesha Connection');
        SEO::twitterDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');
        SEO::twitterImage(url('/') . '/images/favicon.ico');


        $data = array(
            'detail_data' => $detail_data,
        );
        return view('static', $data);
    }

    public function search(Request $request)
    {

        $q = $request->input('q');
        $cat = $request->input('cat');
        $type = $request->input('type');
        $pmin = $request->input('pmin');
        $pmax = $request->input('pmax');
        $rt = $request->input('rt');
        $ob = $request->input('ob');
        $unit = $request->input('unit');
        $user = $request->input('user');
        $query = strtolower($q);

        $categories = "";
        if ($type == "produk") {
            $title = 'Produk';
            if ($query == "") {
                $data = new Product();
            } else {
                $title = 'Search Produk "' . $query . '"';
                $data = Product::whereRaw('lower(name) like (?)', ["%{$query}%"]);
            }

            $categories = ProductCategory::orderBy('name', 'ASC')->get();
        } else if ($type == "jasa") {
            $title = 'Jasa';
            if ($query == "") {
                $data = new Service();
            } else {
                $title = 'Search Jasa "' . $query . '"';
                $data = Service::whereRaw('lower(name) like (?)', ["%{$query}%"]);
            }

            $categories = ServiceCategory::orderBy('name', 'ASC')->get();
        } else {
            $title = 'Search "' . $query . '"';
            // $products = Product::select('admin_category_id', 'id', 'user_id', 'product_category_id as category_id', 'name', 'fake_price', 'price', 'image_thumb', 'created_at', 'slug', 'total_comments', 'total_comment_star_1', 'total_comment_star_2', 'total_comment_star_3', 'total_comment_star_4', 'total_comment_star_5')->whereRaw('lower(name) like (?)', ["%{$query}%"]);
            // $products = $products->addSelect(DB::raw("'produk' as type"));

            // $products = DB::table('products')->whereHas('work_units', function ($query) use ($role) {
            //     $query->where('name', $role);
            // })->with('roles')
            // ->join('product_work_units', 'product_work_units.product_id', '=', 'work_units.id')
            // ->select('id', 'product_category_id as category_id', 'name', 'fake_price', 'price', 'image_thumb', 'created_at', 'slug', 'total_comments', 'total_comment_star_1', 'total_comment_star_2', 'total_comment_star_3', 'total_comment_star_4', 'total_comment_star_5')
            // ->whereRaw('lower(name) like (?)', ["%{$query}%"]);
            // $products = $products->addSelect(DB::raw("'produk' as type"));

            // $data = Service::select('admin_category_id', 'id', 'user_id', 'service_category_id as category_id', 'name', 'fake_price', 'price', 'image_thumb', 'created_at', 'slug', 'total_comments', 'total_comment_star_1', 'total_comment_star_2', 'total_comment_star_3', 'total_comment_star_4', 'total_comment_star_5')->whereRaw('lower(name) like (?)', ["%{$query}%"])->addSelect(DB::raw("'jasa' as type"))
            //     ->union($products);

            $data = Catalog::whereRaw('lower(name) like (?)', ["%{$query}%"]);

            $categories = [];
        }

        SEO::macro('openGraphLocale', function (string $value) {
            return $this->metaByProperty('og:locale', $value);
        });

        SEO::title($title . ' | Ganesha Connection')
            ->openGraphLocale('id')
            ->description('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection')
            ->keywords('ganesha connection, ganesha, itb, connection, produk itb');

        SEO::openGraphType('website');
        SEO::openGraphSiteName('Ganesha Connection');
        SEO::openGraphTitle($title . ' | Ganesha Connection');

        SEO::macro('openGraphDescription', function (string $value) {
            return $this->metaByProperty('og:description', $value);
        });
        SEO::openGraphDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');

        SEO::openGraphUrl($request->fullUrl());
        SEO::openGraphImage(url('/') . '/images/favicon.ico');

        SEO::twitterCard('summary_large_image');
        SEO::twitterSite('Ganesha Connection');
        SEO::twitterTitle($title . ' | Ganesha Connection');
        SEO::twitterDescription('Temukan lebih jauh produk pilihan karya civitas ITB. Produk dan jasa inovatif, semua ada di Ganesha Connection');
        SEO::twitterImage(url('/') . '/images/favicon.ico');

        if ($unit) {
            $data = $data->whereHas('work_units', function ($query) use ($unit) {
                return $query->where('work_unit_id', '=', $unit);
            });
        }

        if($user) {
            $data = $data->where('user_id', $user);
        }

        if ($cat) {
            if ($type == "produk") {
                $data = $data->where('product_category_id', $cat);
            } else if ($type == "jasa") {
                $data = $data->where('service_category_id', $cat);
            }
        }
        if (!$pmin) {
            $pmin = 0;
        }
        if (!$pmax) {
            $pmax = 999999999;
        }
        $data = $data->whereBetween('price', [$pmin, $pmax]);

        if ($rt) {
            if ($rt != 0) {
                $data = $data->whereRaw('CAST(ratings as DECIMAL) >= 4');
            }
        }
        if ($ob) {
            if ($ob == "0") {
                $data = $data->orderBy('views_counter', 'DESC');
            }
            if ($ob == "1") {
                $data = $data->orderBy('created_at', 'DESC');
            }
            if ($ob == "2") {
                $data = $data->orderBy('total_comments', 'DESC');
            }
            if ($ob == "3") {
                $data = $data->orderBy('price', 'DESC');
            }
            if ($ob == "4") {
                $data = $data->orderBy('price', 'ASC');
            }
        }

        // $data = Splade::onLazy(fn() => $data->paginate(18)->withQueryString());
        $data = $data->paginate(18)->withQueryString();

        // dd($data);

        $work_units = WorkUnit::where('is_active', 1)->orderBy('name', 'ASC')->get();

        $data = array(
            'title' => $title,
            'search_type' => $type,
            'data' => $data,
            'categories' => $categories,
            'work_units' => $work_units,
        );

        return view('search', $data);
    }

    public function detail(Request $request, $type, $slug)
    {

        $images_data = [];
        $images = [];
        if ($type == "produk") {
            $detail_data = Product::where('slug', $slug)->first();
            $images = $detail_data->images->toArray();
            array_unshift($images, array('image' => $detail_data->image, 'product_id' => $detail_data->id));
            foreach ($images as $key => $value) {
                if($key == 0){
                    $images_data["image".$key] = true;
                } else {
                    $images_data["image".$key] = false;
                }
            }
            foreach ($images as $key => $value) {
                $click = "";
                $section = "image".$key;
                foreach ($images_data as $key_data => $val) {
                    if($key_data == $section){
                        $click .= "setToggle('".$key_data."', true);";
                    } else {
                        $click .= "setToggle('".$key_data."', false);";
                    }
                }
                $images[$key]['click'] = $click;
                $images[$key]['section'] = $section;
            }

            $related = Product::where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            if (count($related) == 0) {
                $related = Product::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            $user_catalogs = Product::where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            if (count($user_catalogs) == 0) {
                $user_catalogs = Product::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if ($request->input('stars')) {
                // $comments = Splade::onLazy(fn() => ProductComment::where('product_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = ProductComment::where('product_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            } else {
                // $comments = Splade::onLazy(fn() => ProductComment::where('product_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = ProductComment::where('product_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            }

            $check_user_comment = [];
            if (Auth::check()) {
                $user = Auth::user();
                $check_user_comment = ProductComment::where('product_id', $detail_data->id)->where('user_id', $user->id)->first();
            }

            $meta_desc = $detail_data->short_description;

        } else if ($type == "jasa") {
            $detail_data = Service::where('slug', $slug)->first();

            $images = $detail_data->images->toArray();
            array_unshift($images, array('image' => $detail_data->image, 'service_id' => $detail_data->id));
            foreach ($images as $key => $value) {
                if($key == 0){
                    $images_data["image".$key] = true;
                } else {
                    $images_data["image".$key] = false;
                }
            }
            foreach ($images as $key => $value) {
                $click = "";
                $section = "image".$key;
                foreach ($images_data as $key_data => $val) {
                    if($key_data == $section){
                        $click .= "setToggle('".$key_data."', true);";
                    } else {
                        $click .= "setToggle('".$key_data."', false);";
                    }
                }
                $images[$key]['click'] = $click;
                $images[$key]['section'] = $section;
            }

            $related = Service::where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            if (count($related) == 0) {
                $related = Service::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            $user_catalogs = Service::where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            if (count($user_catalogs) == 0) {
                $user_catalogs = Service::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if ($request->input('stars')) {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('service_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = ServiceComment::where('service_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            } else {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('service_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = ServiceComment::where('service_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            }

            $check_user_comment = [];
            if (Auth::check()) {
                $user = Auth::user();
                $check_user_comment = ServiceComment::where('service_id', $detail_data->id)->where('user_id', $user->id)->first();
            }

            $meta_desc = $detail_data->short_description;

        } else if ($type == "partner") {
            $detail_data = User::where('slug', $slug)->first();
            $related = Service::where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
            $user_catalogs = Product::where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
            $comments = [];
            $check_user_comment = [];

            $meta_desc = $detail_data->name;
        }
        $detail_data->views_counter = $detail_data->views_counter + 1;
        $detail_data->save();

        

        SEO::macro('openGraphLocale', function (string $value) {
            return $this->metaByProperty('og:locale', $value);
        });

        SEO::title($detail_data->name . " | Ganesha Connection")
            ->openGraphLocale('id')
            ->description($meta_desc)
            ->keywords('ganesha connection, ganesha, itb, connection, produk itb');

        SEO::openGraphType('website');
        SEO::openGraphSiteName('Ganesha Connection');
        SEO::openGraphTitle($detail_data->name);

        SEO::macro('openGraphDescription', function (string $value) {
            return $this->metaByProperty('og:description', $value);
        });
        SEO::openGraphDescription($meta_desc);

        SEO::openGraphUrl($request->fullUrl());
        SEO::openGraphImage(url('/') . $detail_data->image_thumb);

        SEO::twitterCard('summary_large_image');
        SEO::twitterSite('Ganesha Connection');
        SEO::twitterTitle($detail_data->name . ' | Ganesha Connection');
        SEO::twitterDescription($meta_desc);
        SEO::twitterImage(url('/') . $detail_data->image_thumb);

        $data = array(
            'images_data' => $images_data,
            'images' => $images,
            'type' => $type,
            'detail_data' => $detail_data,
            'check_user_comment' => $check_user_comment,
            'comments' => $comments,
            'related' => $related,
            'user_catalogs' => $user_catalogs
        );
        return view('detail', $data);
    }
}
