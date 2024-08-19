<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class ProductCategoryController extends Controller
{
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

        $product_categories = QueryBuilder::for(ProductCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug', 'is_selected'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('product_categories.index', [
            'product_categories' => SpladeTable::for($product_categories)
                ->defaultSort('name')
                ->column('name', sortable: true, searchable: true)
                ->withGlobalSearch(columns: ['name'])
                ->column('slug', sortable: true, searchable: true)
                ->column('is_selected', sortable: true, searchable: true)
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product_categories.create', [
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:product_categories,name',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/product_categories/' . $fileName));
            $image = '/storage/product_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Product Category was created!')->autoDismiss(5);

        return redirect()->route('product_categories.index');
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
    public function edit(ProductCategory $product_category)
    {
        return view('product_categories.edit', [
            'product_category' => $product_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $product_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/product_categories/' . str_replace('/storage/product_categories/', '', $product_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/product_categories/' . $fileName));

            $product_category->update([
                'image' => '/storage/product_categories/' . $fileName,
            ]);
        }

        $product_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Product Category was updated!')->autoDismiss(5);

        return redirect()->route('product_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $product_category)
    {
        $products = Product::where('product_category_id', $product_category->id)->get();
        foreach ($products as $product) {
            $product->product_comments()->delete();
            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $product_images = ProductImage::where('product_id', $product->id)->get();
            foreach ($product_images as $key => $value) {
                $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/products/' . str_replace('/storage/products/', '', $value->image);
                try {
                    unlink($dirname);
                } catch (\Throwable $th) {
                }
            }

            ProductImage::where('product_id', $product->id)->delete();

            $product->work_units()->detach();
            $product->delete();
        }
        $product_category->products()->delete();
        $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/product_categories/' . str_replace('/storage/product_categories/', '', $product_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $product_category->delete();
        Toast::title('Product Category was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('product_categories.index');
    }
}
