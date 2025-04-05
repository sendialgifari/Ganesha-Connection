<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\WebinarCategory;
use App\Models\Webinar;
use App\Models\WebinarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class WebinarCategoryController extends Controller
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

        $webinar_categories = QueryBuilder::for(WebinarCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug', 'is_selected'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('webinar_categories.index', [
            'webinar_categories' => SpladeTable::for($webinar_categories)
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
        return view('webinar_categories.create', [
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
            'name' => 'required|max:255|unique:webinar_categories,name',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/webinar_categories/' . $fileName));
            $image = '/storage/webinar_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        WebinarCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Kategori webinar berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('webinar_categories.index');
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
    public function edit(WebinarCategory $webinar_category)
    {
        return view('webinar_categories.edit', [
            'webinar_category' => $webinar_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebinarCategory $webinar_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinar_categories/' . str_replace('/storage/webinar_categories/', '', $webinar_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/webinar_categories/' . $fileName));

            $webinar_category->update([
                'image' => '/storage/webinar_categories/' . $fileName,
            ]);
        }

        $webinar_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Kategori webinar berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('webinar_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebinarCategory $webinar_category)
    {
        $webinars = Webinar::where('webinar_category_id', $webinar_category->id)->get();
        foreach ($webinars as $webinar) {
            $webinar->webinar_comments()->delete();
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinars/' . str_replace('/storage/webinars/', '', $webinar->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinars/' . str_replace('/storage/webinars/', '', $webinar->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $webinar_images = WebinarImage::where('webinar_id', $webinar->id)->get();
            foreach ($webinar_images as $key => $value) {
                $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinars/' . str_replace('/storage/webinars/', '', $value->image);
                try {
                    unlink($dirname);
                } catch (\Throwable $th) {
                }
            }
            WebinarImage::where('webinar_id', $webinar->id)->delete();
            $webinar->delete();
        }
        $webinar_category->webinars()->delete();
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinar_categories/' . str_replace('/storage/webinar_categories/', '', $webinar_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $webinar_category->delete();
        Toast::title('Kategori webinar berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('webinar_categories.index');
    }
}
