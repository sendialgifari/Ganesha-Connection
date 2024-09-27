<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class ServiceCategoryController extends Controller
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

        $service_categories = QueryBuilder::for(ServiceCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug', 'is_selected'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('service_categories.index', [
            'service_categories' => SpladeTable::for($service_categories)
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
        return view('service_categories.create', [
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
            'name' => 'required|max:255|unique:service_categories,name',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/service_categories/' . $fileName));
            $image = '/storage/service_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        ServiceCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Service Category was created!')->autoDismiss(5);

        return redirect()->route('service_categories.index');
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
    public function edit(ServiceCategory $service_category)
    {
        return view('service_categories.edit', [
            'service_category' => $service_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCategory $service_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/service_categories/' . str_replace('/storage/service_categories/', '', $service_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/service_categories/' . $fileName));

            $service_category->update([
                'image' => '/storage/service_categories/' . $fileName,
            ]);
        }

        $service_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Service Category was updated!')->autoDismiss(5);

        return redirect()->route('service_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCategory $service_category)
    {
        $services = Service::where('service_category_id', $service_category->id)->get();
        foreach ($services as $service) {
            $service->service_comments()->delete();
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $service_images = ServiceImage::where('service_id', $service->id)->get();
            foreach ($service_images as $key => $value) {
                $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $value->image);
                try {
                    unlink($dirname);
                } catch (\Throwable $th) {
                }
            }

            ServiceImage::where('service_id', $service->id)->delete();

            $service->work_units()->detach();
            $service->delete();
        }
        $service_category->services()->delete();
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/service_categories/' . str_replace('/storage/service_categories/', '', $service_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $service_category->delete();
        Toast::title('Service Category was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('service_categories.index');
    }
}
