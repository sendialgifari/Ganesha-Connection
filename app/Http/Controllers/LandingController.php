<?php

namespace App\Http\Controllers;

use App\Models\WorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\Catalog;
use App\Models\AdminCategory;
use App\Models\AdminPromotionCategory;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationComment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceComment;
use App\Models\StaticPage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\Slider;
use App\Models\User;
use App\Models\Webinar;
use App\Models\WebinarCategory;
use App\Models\WebinarComment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Auth;
use DB;
use Hash;
use View;
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
    public function __construct(){
        $data['filter_product_categories'] = ProductCategory::limit(14)->pluck('name', 'id')->toArray();
        $data['filter_service_categories'] = ServiceCategory::limit(14)->pluck('name', 'id')->toArray();
        $data['filter_work_units'] = WorkUnit::where('is_active', 1)->limit(14)->pluck('name', 'id')->toArray();
        $data['filter_admin_categories'] = AdminCategory::where('is_selected', 1)->limit(14)->pluck('name', 'id')->toArray();
        $data['filter_admin_promotion_categories'] = AdminPromotionCategory::where('is_selected', 1)->limit(14)->pluck('name', 'id')->toArray();
        $data['is_public'] = [
                '0' => 'Civitas ITB',
                '1' => 'Umum',
        ];
        View::share ($data);
    }
    
    public function index(Request $request)
    {
        $is_public = 1;
        if (Auth::check()) {
            $is_public = Auth::user()->is_public;
        } 

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

        if($is_public == 1){
            $product_selected = Product::where('is_selected', 1)->where('is_public', 1)->inRandomOrder()->limit(12)->get();
            $service_selected = Service::where('is_selected', 1)->where('is_public', 1)->inRandomOrder()->limit(12)->get();
            $products = Product::where('is_public', 1)->inRandomOrder()->limit(12)->get();
            $services = Service::where('is_public', 1)->inRandomOrder()->limit(12)->get();
        } else {
            $product_selected = Product::where('is_selected', 1)->inRandomOrder()->limit(12)->get();
            $service_selected = Service::where('is_selected', 1)->inRandomOrder()->limit(12)->get();
            $products = Product::inRandomOrder()->limit(12)->get();
            $services = Service::inRandomOrder()->limit(12)->get();
        }

        return view('index', [
            'sliders' => Splade::onLazy(fn() => Slider::orderBy('id', 'DESC')->get()),
            'product_categories_selected' => ProductCategory::where('is_selected', 1)->orderBy('name', 'ASC')->limit(8)->get(),
            'service_categories_selected' => ServiceCategory::where('is_selected', 1)->orderBy('name', 'ASC')->limit(8)->get(),
            'product_selected' => $product_selected,
            'service_selected' => $service_selected,
            'partner_selected' => User::where('is_selected', 1)->orderBy('name', 'ASC')->limit(10)->get(),
            'products' => $products,
            'services' => $services,
            'admin_promotion_categories' => AdminPromotionCategory::where('is_selected', 1)->orderBy('name', 'ASC')->get(),
            'admin_categories' => AdminCategory::where('is_selected', 1)->orderBy('name', 'ASC')->get(),
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

        $is_public = 1;
        if (Auth::check()) {
            $is_public = Auth::user()->is_public;
        }

        $q = $request->input('q');
        $cat = $request->input('cat');
        $adm_cat = $request->input('adm_cat');
        $adm_p_cat = $request->input('adm_p_cat');
        $type = $request->input('type');
        $pmin = $request->input('pmin');
        $pmax = $request->input('pmax');
        $rt = $request->input('rt');
        $ob = $request->input('ob');
        $unit = $request->input('unit');
        $user = $request->input('user');
        $query = strtolower($q);

        $admin_categories = AdminCategory::orderBy('name', 'ASC')->get();

        $work_units = WorkUnit::where('is_active', 1)->orderBy('name', 'ASC')->get();

        $admin_promotion_categories = AdminPromotionCategory::orderBy('name', 'ASC')->get();

        $admin_categories_ids = $admin_categories->pluck('id')->toArray();

        $categories = "";
        if ($type == "produk") {
            $title = 'Produk';
            if ($query == "") {
                if($is_public == 1){
                    $data = Product::where('is_public', 1);
                } else {
                    $data = new Product();
                }
            } else {
                $title = 'Search Produk "' . $query . '"';
                if($is_public == 1){
                    $data = Product::whereRaw('lower(name) like (?)', ["%{$query}%"])->where('is_public', 1);
                } else {
                    $data = Product::whereRaw('lower(name) like (?)', ["%{$query}%"]);
                }
            }

            $categories = ProductCategory::orderBy('name', 'ASC')->get();
        } else if ($type == "jasa") {
            $title = 'Jasa';
            if ($query == "") {
                if($is_public == 1){
                    $data = Service::where('is_public', 1);
                } else {
                    $data = new Service();
                }
            } else {
                $title = 'Search Jasa "' . $query . '"';
                if($is_public == 1){
                    $data = Service::whereRaw('lower(name) like (?)', ["%{$query}%"])->where('is_public', 1);
                } else {
                    $data = Service::whereRaw('lower(name) like (?)', ["%{$query}%"]);
                }
            }

            $categories = ServiceCategory::orderBy('name', 'ASC')->get();
        } else if ($type == "webinar") {
            $title = 'Webinar';
            if ($query == "") {
                if($is_public == 1){
                    $data = Webinar::where('is_public', 1);
                } else {
                    $data = new Webinar();
                }
            } else {
                $title = 'Search Webinar "' . $query . '"';
                if($is_public == 1){
                    $data = Webinar::whereRaw('lower(name) like (?)', ["%{$query}%"])->where('is_public', 1);
                } else {
                    $data = Webinar::whereRaw('lower(name) like (?)', ["%{$query}%"]);
                }
            }

            $categories = WebinarCategory::orderBy('name', 'ASC')->get();
        } else if ($type == "donasi") {
            $title = 'Donasi';
            if ($query == "") {
                if($is_public == 1){
                    $data = Donation::where('is_public', 1);
                } else {
                    $data = new Donation();
                }
            } else {
                $title = 'Search Donasi "' . $query . '"';
                if($is_public == 1){
                    $data = Donation::whereRaw('lower(name) like (?)', ["%{$query}%"])->where('is_public', 1);
                } else {
                    $data = Donation::whereRaw('lower(name) like (?)', ["%{$query}%"]);
                }
            }

            $categories = DonationCategory::orderBy('name', 'ASC')->get();
        } else {
            if($query){
                $title = 'Search "' . $query . '"';
            } else {
                if($adm_p_cat){
                    if($adm_p_cat == "all"){
                        $title = 'Search Semua Departemen';
                    } else if($adm_p_cat == "non"){
                        $title = 'Search Departemen Non-Jenis';
                    } else {
                        $admin_promotion_category = AdminPromotionCategory::where('id', $adm_p_cat)->first();
                        if($admin_promotion_category){
                            $title = 'Search Departemen '. $admin_promotion_category->name;
                        } else {
                            $title = 'Search Departemen Non-Jenis';
                        }
                    }
                } else if($adm_cat){
                    if($adm_cat == "all"){
                        $title = 'Search Semua Jenis';
                    } else if($adm_cat == "non"){
                        $title = 'Search Semua Non-Jenis';
                    } else {
                        $admin_category = AdminCategory::where('id', $adm_cat)->first();
                        if($admin_category){
                            $title = 'Search Jenis '. $admin_category->name;
                        } else {
                            $title = 'Search Non-Jenis';
                        }
                    }
                } else if($unit){
                    if($unit == "all"){
                        $title = 'Search Semua Unit Kerja';
                    } else {
                        $work_unit = WorkUnit::where('id', $unit)->first();
                        if($work_unit){
                            $title = 'Search Unit Kerja '. $work_unit->name;
                        } else {
                            $title = 'Search Semua Unit Kerja';
                        }
                    }
                }
                
            }
            
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

            if($is_public == 1){
                $data = Catalog::whereRaw('lower(name) like (?)', ["%{$query}%"])->where('is_public', 1);
            } else {
                $data = Catalog::whereRaw('lower(name) like (?)', ["%{$query}%"]);
            }

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
            if($unit == "all"){
                $work_unit_ids = $work_units->pluck('id')->toArray();
                $data = $data->whereHas('work_units', function ($query) use ($work_unit_ids) {
                    return $query->whereIn('work_unit_id', $work_unit_ids);
                });
            } else {
                $data = $data->whereHas('work_units', function ($query) use ($unit) {
                    return $query->where('work_unit_id', '=', $unit);
                });
            }
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
        if ($adm_cat) {
            if($adm_cat == "all"){
                $data = $data->whereNotNull('admin_category_id');
            } else if($adm_cat == "non"){
                $data = $data->where('admin_category_id', null);
            } else {
                $data = $data->where('admin_category_id', $adm_cat);
            }
        }
        if ($adm_p_cat) {
            if($adm_p_cat == "all"){
                $data = $data->whereNotNull('admin_promotion_category_id');
            } else if($adm_p_cat == "non"){
                $data = $data->where('admin_promotion_category_id', null);
            } else {
                $data = $data->where('admin_promotion_category_id', $adm_p_cat);
            }
        }

        if($type != "donasi"){
            if (!$pmin) {
                $pmin = 0;
            }
            if (!$pmax) {
                $pmax = 999999999;
            }
            $data = $data->whereBetween('price', [$pmin, $pmax]);
        }
        

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

        $data = array(
            'title' => $title,
            'search_type' => $type,
            'data' => $data,
            'categories' => $categories,
            'work_units' => $work_units,
            'admin_categories' => $admin_categories,
            'admin_promotion_categories' => $admin_promotion_categories,
        );

        return view('search', $data);
    }

    public function detail(Request $request, $type, $slug)
    {

        $is_public = 1;
        if (Auth::check()) {
            $is_public = Auth::user()->is_public;
        }

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

            if($is_public == 1){
                $related = Product::where('is_public', 1)->where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            } else {
                $related = Product::where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($related) == 0) {
                if($is_public == 1){
                    $related = Product::where('is_public', 1)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $related = Product::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if($is_public == 1){
                $user_catalogs = Product::where('is_public', 1)->where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            } else {
                $user_catalogs = Product::where('product_category_id', $detail_data->product_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($user_catalogs) == 0) {
                if($is_public == 1){
                    $user_catalogs = Product::where('is_public', 1)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $user_catalogs = Product::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                }
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

            if($is_public == 1){
                $related = Service::where('is_public', 1)->where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            } else {
                $related = Service::where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($related) == 0) {
                if($is_public == 1){
                    $related = Service::where('is_public', 1)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $related = Service::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if($is_public == 1){
                $user_catalogs = Service::where('is_public', 1)->where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            } else {
                $user_catalogs = Service::where('service_category_id', $detail_data->service_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($user_catalogs) == 0) {
                if($is_public == 1){
                    $user_catalogs = Service::where('is_public', 1)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $user_catalogs = Service::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                }
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

        } else if ($type == "webinar") {
            $detail_data = Webinar::where('slug', $slug)->first();

            $images = $detail_data->images->toArray();
            array_unshift($images, array('image' => $detail_data->image, 'webinar_id' => $detail_data->id));
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

            if($is_public == 1){
                $related = Webinar::where('is_public', 1)->where('webinar_category_id', $detail_data->webinar_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            } else {
                $related = Webinar::where('webinar_category_id', $detail_data->webinar_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($related) == 0) {
                if($is_public == 1){
                    $related = Webinar::where('is_public', 1)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $related = Webinar::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if($is_public == 1){
                $user_catalogs = Webinar::where('is_public', 1)->where('webinar_category_id', $detail_data->webinar_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            } else {
                $user_catalogs = Webinar::where('webinar_category_id', $detail_data->webinar_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($user_catalogs) == 0) {
                if($is_public == 1){
                    $user_catalogs = Webinar::where('is_public', 1)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $user_catalogs = Webinar::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if ($request->input('stars')) {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('webinar_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = WebinarComment::where('webinar_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            } else {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('webinar_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = WebinarComment::where('webinar_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            }

            $check_user_comment = [];
            if (Auth::check()) {
                $user = Auth::user();
                $check_user_comment = WebinarComment::where('webinar_id', $detail_data->id)->where('user_id', $user->id)->first();
            }

            $meta_desc = $detail_data->short_description;

        }  else if ($type == "donasi") {
            $detail_data = Donation::where('slug', $slug)->first();

            $images = $detail_data->images->toArray();
            array_unshift($images, array('image' => $detail_data->image, 'donation_id' => $detail_data->id));
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

            if($is_public == 1){
                $related = Donation::where('is_public', 1)->where('donation_category_id', $detail_data->donation_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            } else {
                $related = Donation::where('donation_category_id', $detail_data->donation_category->id)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($related) == 0) {
                if($is_public == 1){
                    $related = Donation::where('is_public', 1)->where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $related = Donation::where('id', '!=', $detail_data->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if($is_public == 1){
                $user_catalogs = Donation::where('is_public', 1)->where('donation_category_id', $detail_data->donation_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            } else {
                $user_catalogs = Donation::where('donation_category_id', $detail_data->donation_category->id)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
            }
            if (count($user_catalogs) == 0) {
                if($is_public == 1){
                    $user_catalogs = Donation::where('is_public', 1)->where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                } else {
                    $user_catalogs = Donation::where('id', '!=', $detail_data->id)->where('user_id', $detail_data->user->id)->inRandomOrder()->limit(6)->get();
                }
            }
            if ($request->input('stars')) {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('donation_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = DonationComment::where('donation_id', $detail_data->id)->where('ratings', $request->input('stars'))->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            } else {
                // $comments = Splade::onLazy(fn() => ServiceComment::where('donation_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString());
                $comments = DonationComment::where('donation_id', $detail_data->id)->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();
            }

            $check_user_comment = [];
            if (Auth::check()) {
                $user = Auth::user();
                $check_user_comment = DonationComment::where('donation_id', $detail_data->id)->where('user_id', $user->id)->first();
            }

            $meta_desc = $detail_data->short_description;

        } else if ($type == "partner") {
            $detail_data = User::where('slug', $slug)->first();
            if($is_public == 1){
                $related = Service::where('is_public', 1)->where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
                $user_catalogs = Product::where('is_public', 1)->where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
            } else {
                $related = Service::where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
                $user_catalogs = Product::where('user_id', $detail_data->id)->inRandomOrder()->limit(6)->get();
            }
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
