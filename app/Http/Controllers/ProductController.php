<?php

namespace App\Http\Controllers;

use App\Models\ProductWorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use App\Models\AdminPromotionCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Hash;
use Auth;
use Intervention\Image\Laravel\Facades\Image;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('name', 'ILIKE', "%{$value}%");
                });
            });
        });

        $categories = ProductCategory::pluck('name', 'id')->toArray();

        $user = Auth::user();
        if ($user->getRoleNames()[0] == "admin" || $user->getRoleNames()[0] == "superadmin") {
            $products = QueryBuilder::for(Product::class)
                ->defaultSort('name')
                ->allowedSorts(['name', 'product_category.name', 'is_selected', 'user.name'])
                ->allowedFilters(['name', 'product_category.name', AllowedFilter::exact('product_category_id'), $globalSearch])
                ->paginate();

            return view('products.index', [
                'products' => SpladeTable::for($products)
                    ->defaultSort('name')
                    ->column('name', 'Nama', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image', 'Gambar')
                    ->column('product_category.name', 'Kategori produk', sortable: true, searchable: true)
                    ->column('is_selected', 'pilihan', sortable: true, searchable: true)
                    ->column('user.name', 'owner', sortable: true, searchable: true)
                    ->column('admin_category.name', 'admin kategori', sortable: true, searchable: true)
                    ->selectFilter('product_category_id', $categories)
                    ->column('action')
                ,
            ]);
        } else {
            $products = QueryBuilder::for(Product::where('user_id', $user->id))
                ->defaultSort('name')
                ->allowedSorts(['name', 'product_category.name'])
                ->allowedFilters(['name', 'product_category.name', AllowedFilter::exact('product_category_id'), $globalSearch])
                ->paginate();

            return view('products.index', [
                'products' => SpladeTable::for($products)
                    ->defaultSort('name')
                    ->column('name', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image')
                    ->column('product_category.name', sortable: true, searchable: true)
                    ->selectFilter('product_category_id', $categories)
                    ->column('action')
                ,
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin_categories = AdminCategory::pluck('name', 'id')->toArray();
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
        $product_categories = ProductCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        $is_selected = [
            '0' => 'No',
            '1' => 'Yes',
        ];
        $price_type = [
            '0' => 'Menggunakan harga',
            '1' => 'Hubungi kami',
        ];
        $is_readystock = [
            '0' => 'Pre Order',
            '1' => 'Ready Stock',
        ];
        $is_public = [
            '0' => 'Hanya Civitas ITB',
            '1' => 'Publik',
        ];
        return view('products.create', compact('product_categories', 'work_units', 'is_selected', 'admin_categories', 'admin_promotion_categories', 'price_type', 'is_readystock', 'is_public'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'product_category_id' => 'required',
            'name' => 'required|max:255|unique:products,name',
            'description' => 'required',
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            'is_selected' => 'required',
            'price_type' => 'required|numeric',
            'is_readystock' => 'required|numeric',
            'is_public' => 'required|numeric',
        ])->validate();

        $fileName = "img-product-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileName));
        // $img->resize(1000, 1000, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/products/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileNameThumb));
        // $img->resize(200, 200, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/products/' . $fileNameThumb));

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        $price = $request->price;
        if($request->price_type == 1){
            $price = 0;
        }
        if(is_null($price)){
            $price = 0;
        }

        $admin_category_id = $request->admin_category_id;
        if($request->admin_category_id == 0){
            $admin_category_id = null;
        }

        $admin_promotion_category_id = $request->admin_promotion_category_id;
        if($request->admin_promotion_category_id == 0){
            $admin_promotion_category_id = null;
        }

        $fake_price = $request->fake_price;
        if($request->is_fake_price == "no"){
            $fake_price = 0;
        }

        $user = Auth::user();
        $product = Product::create([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'product_category_id' => $request->product_category_id,
            'name' => $request->name,
            'user_id' => $user->id,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'image' => '/storage/products/' . $fileName,
            'image_thumb' => '/storage/products/' . $fileNameThumb,
            'fake_price' => $fake_price,
            'price_type' => $request->price_type,
            'is_selected' => $request->is_selected,
            'is_readystock' => $request->is_readystock,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
        ]);

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-product-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileName));

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => '/storage/products/' . $fileName,
                ]);
            }
        }

        $product->work_units()->attach($request->work_units);

        Toast::title('Produk berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->images = array_column($product->images->toArray(), 'image');
        $admin_categories = AdminCategory::pluck('name', 'id')->toArray();
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
        $product_categories = ProductCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        return view('products.edit', [
            'product' => $product,
            'product_categories' => $product_categories,
            'work_units' => $work_units,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
            'price_type' => [
                '0' => 'Menggunakan harga',
                '1' => 'Hubungi kami',
            ],
            'is_readystock' => [
                '0' => 'Pre Order',
                '1' => 'Ready Stock',
            ],
            'is_public' => [
                '0' => 'Hanya Civitas ITB',
                '1' => 'Publik',
            ],
            'admin_categories' => $admin_categories,
            'admin_promotion_categories' => $admin_promotion_categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Validator::make($request->all(), [
            'product_category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            // 'is_selected' => 'required',
            'price_type' => 'required|numeric',
            'is_readystock' => 'required|numeric',
            'is_public' => 'required|numeric',
        ])->validate();

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-product-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileNameThumb));

            $product->update([
                'image' => '/storage/products/' . $fileName,
                'image_thumb' => '/storage/products/' . $fileNameThumb,
            ]);
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->is_selected) {
            $is_selected = $request->is_selected;
        } else {
            $is_selected = $product->is_selected;
        }

        $price = $request->price;
        if($request->price_type == 1){
            $price = 0;
        }
        if(is_null($price)){
            $price = 0;
        }

        $admin_category_id = $request->admin_category_id;
        if($request->admin_category_id == 0){
            $admin_category_id = null;
        }

        $admin_promotion_category_id = $request->admin_promotion_category_id;
        if($request->admin_promotion_category_id == 0){
            $admin_promotion_category_id = null;
        }

        $fake_price = $request->fake_price;
        if($request->is_fake_price == "no"){
            $fake_price = 0;
        }

        $product->update([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'product_category_id' => $request->product_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'fake_price' => $fake_price,
            'price_type' => $request->price_type,
            'is_selected' => $is_selected,
            'is_readystock' => $request->is_readystock,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
        ]);
        $product->work_units()->sync($request->work_units);

        $product_images = ProductImage::where('product_id', $product->id)->get();
        foreach ($product_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        ProductImage::where('product_id', $product->id)->delete();

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-product-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/products/' . $fileName));
    
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => '/storage/products/' . $fileName,
                ]);
            }
        }

        Toast::title('Produk berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->product_comments()->delete();
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image_thumb);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }

        $product_images = ProductImage::where('product_id', $product->id)->get();
        foreach ($product_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        ProductImage::where('product_id', $product->id)->delete();

        $product->work_units()->detach();
        $product->delete();
        Toast::title('Produk berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('products.index');
    }
}
