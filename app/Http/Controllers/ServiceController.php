<?php

namespace App\Http\Controllers;

use App\Models\ServiceWorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceImage;
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

class ServiceController extends Controller
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

        $categories = ServiceCategory::pluck('name', 'id')->toArray();

        $user = Auth::user();
        if($user->getRoleNames()[0] == "admin" || $user->getRoleNames()[0] == "superadmin"){
            $services = QueryBuilder::for(Service::class)
                ->defaultSort('name')
                ->allowedSorts(['name', 'service_category.name', 'is_selected','user.name'])
                ->allowedFilters(['name', 'service_category.name', AllowedFilter::exact('service_category_id'), $globalSearch])
                ->paginate();

            return view('services.index', [
                'services' => SpladeTable::for($services)
                    ->defaultSort('name')
                    ->column('name', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image')
                    ->column('service_category.name', sortable: true, searchable: true)
                    ->column('is_selected', sortable: true, searchable: true)
                    ->column('user.name', sortable: true, searchable: true)
                    ->column('admin_category.name', sortable: true, searchable: true)
                    ->selectFilter('service_category_id', $categories)
                    ->column('action')
                ,
            ]);
        } else {
            $services = QueryBuilder::for(Service::where('user_id', $user->id))
                ->defaultSort('name')
                ->allowedSorts(['name', 'service_category.name'])
                ->allowedFilters(['name', 'service_category.name', AllowedFilter::exact('service_category_id'), $globalSearch])
                ->paginate();

            return view('services.index', [
                'services' => SpladeTable::for($services)
                    ->defaultSort('name')
                    ->column('name', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image')
                    ->column('service_category.name', sortable: true, searchable: true)
                    ->selectFilter('service_category_id', $categories)
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
        $service_categories = ServiceCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        $is_selected = [
            '0' => 'No',
            '1' => 'Yes',
        ];
        return view('services.create', compact('service_categories', 'work_units', 'is_selected', 'admin_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'service_category_id' => 'required',
            'name' => 'required|max:255|unique:services,name',
            'description' => 'required',
            'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            'is_selected' => 'required',
        ])->validate();

        $fileName = "img-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(1000, 1000, 'center')->save(storage_path('app/public/services/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(200, 200, 'center')->save(storage_path('app/public/services/' . $fileNameThumb));

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        $user = Auth::user();
        $service = Service::create([
            'admin_category_id' => $request->admin_category_id,
            'service_category_id' => $request->service_category_id,
            'name' => $request->name,
            'user_id' => $user->id,
            'description' => $request->description,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'image' => '/storage/services/' . $fileName,
            'image_thumb' => '/storage/services/' . $fileNameThumb,
            'fake_price' => $request->fake_price,
            'is_selected' => $request->is_selected,
            'slug' => $slug,
        ]);

        foreach ($request->images as $value) {
            $fileName = "img-" . $value->hashName();
            $file = $value;
            $img = Image::read($file->getRealPath());
            $img->cover(1000, 1000, 'center')->save(storage_path('app/public/services/' . $fileName));

            ServiceImage::create([
                'service_id' => $service->id,
                'image' => '/storage/services/' . $fileName,
            ]);
        }

        $service->work_units()->attach($request->work_units);

        Toast::title('Service was created!')->autoDismiss(5);

        return redirect()->route('services.index');
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
    public function edit(Service $service)
    {
        $service->images = array_column($service->images->toArray(), 'image');
        $admin_categories = AdminCategory::pluck('name', 'id')->toArray();
        $service_categories = ServiceCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        return view('services.edit', [
            'service' => $service,
            'service_categories' => $service_categories,
            'work_units' => $work_units,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
            'admin_categories' => $admin_categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        Validator::make($request->all(), [
            'service_category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            // 'is_selected' => 'required',
        ])->validate();

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(1000, 1000, 'center')->save(storage_path('app/public/services/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/services/' . $fileNameThumb));

            $service->update([
                'image' => '/storage/services/' . $fileName,
                'image_thumb' => '/storage/services/' . $fileNameThumb,
            ]);
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if($request->is_selected){
            $is_selected = $request->is_selected;
        } else {
            $is_selected = $service->is_selected;
        }

        $service->update([
            'admin_category_id' => $request->admin_category_id,
            'service_category_id' => $request->service_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'fake_price' => $request->fake_price,
            'is_selected' => $is_selected,
            'slug' => $slug,
        ]);
        $service->work_units()->sync($request->work_units);

        $service_images = ServiceImage::where('service_id', $service->id)->get();
        foreach ($service_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        ServiceImage::where('service_id', $service->id)->delete();
        
        foreach ($request->images as $value) {
            $fileName = "img-" . $value->hashName();
            $file = $value;
            $img = Image::read($file->getRealPath());
            $img->cover(1000, 1000, 'center')->save(storage_path('app/public/services/' . $fileName));

            ServiceImage::create([
                'service_id' => $service->id,
                'image' => '/storage/services/' . $fileName,
            ]);
        }

        Toast::title('Service was updated!')->autoDismiss(5);

        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->service_comments()->delete();
        $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_thumb);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }

        $service_images = ServiceImage::where('service_id', $service->id)->get();
        foreach ($service_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/itbproject/storage/app/public/services/' . str_replace('/storage/services/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        ServiceImage::where('service_id', $service->id)->delete();

        $service->work_units()->detach();
        $service->delete();
        Toast::title('Service was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('services.index');
    }
}
