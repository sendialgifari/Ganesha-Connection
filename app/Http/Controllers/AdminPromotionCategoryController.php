<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\AdminPromotionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class AdminPromotionCategoryController extends Controller
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

        $admin_promotion_categories = QueryBuilder::for(AdminPromotionCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('admin_promotion_categories.index', [
            'admin_promotion_categories' => SpladeTable::for($admin_promotion_categories)
                ->defaultSort('name')
                ->column('name', sortable: true, searchable: true)
                ->withGlobalSearch(columns: ['name'])
                ->column('slug', sortable: true, searchable: true)
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_promotion_categories.create', [
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
            'name' => 'required|max:255|unique:admin_promotion_categories,name',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(150, 150, 'center')->save(storage_path('app/public/admin_promotion_categories/' . $fileName));
            $image = '/storage/admin_promotion_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        AdminPromotionCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Admin Category was created!')->autoDismiss(5);

        return redirect()->route('admin_promotion_categories.index');
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
    public function edit(AdminPromotionCategory $admin_promotion_category)
    {
        return view('admin_promotion_categories.edit', [
            'admin_promotion_category' => $admin_promotion_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminPromotionCategory $admin_promotion_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,jpg,png',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/admin_promotion_categories/' . str_replace('/storage/admin_promotion_categories/', '', $admin_promotion_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(150, 150, 'center')->save(storage_path('app/public/admin_promotion_categories/' . $fileName));

            $admin_promotion_category->update([
                'image' => '/storage/admin_promotion_categories/' . $fileName,
            ]);
        }

        $admin_promotion_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Admin Category was updated!')->autoDismiss(5);

        return redirect()->route('admin_promotion_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminPromotionCategory $admin_promotion_category)
    {
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/admin_promotion_categories/' . str_replace('/storage/admin_promotion_categories/', '', $admin_promotion_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        
        $admin_promotion_category->delete();
        Toast::title('Admin Category was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('admin_promotion_categories.index');
    }
}
