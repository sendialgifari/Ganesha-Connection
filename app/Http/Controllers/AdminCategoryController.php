<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class AdminCategoryController extends Controller
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

        $admin_categories = QueryBuilder::for(AdminCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('admin_categories.index', [
            'admin_categories' => SpladeTable::for($admin_categories)
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
        return view('admin_categories.create', [
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
            'name' => 'required|max:255|unique:admin_categories,name',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/admin_categories/' . $fileName));
            $image = '/storage/admin_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        AdminCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Admin Category was created!')->autoDismiss(5);

        return redirect()->route('admin_categories.index');
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
    public function edit(AdminCategory $admin_category)
    {
        return view('admin_categories.edit', [
            'admin_category' => $admin_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminCategory $admin_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/admin_categories/' . str_replace('/storage/admin_categories/', '', $admin_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/admin_categories/' . $fileName));

            $admin_category->update([
                'image' => '/storage/admin_categories/' . $fileName,
            ]);
        }

        $admin_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Admin Category was updated!')->autoDismiss(5);

        return redirect()->route('admin_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminCategory $admin_category)
    {
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/admin_categories/' . str_replace('/storage/admin_categories/', '', $admin_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }

        $admin_category->delete();
        Toast::title('Admin Category was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('admin_categories.index');
    }
}
