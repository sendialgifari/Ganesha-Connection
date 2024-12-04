<?php

namespace App\Http\Controllers;

use App\Models\ServiceWorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use App\Models\AdminPromotionCategory;
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
                    ->column('name', 'nama', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image', 'gambar')
                    ->column('service_category.name', 'kategori jasa', sortable: true, searchable: true)
                    ->column('is_selected', 'pilihan', sortable: true, searchable: true)
                    ->column('user.name', 'owner', sortable: true, searchable: true)
                    ->column('admin_category.name', 'admin kategori', sortable: true, searchable: true)
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
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
        $service_categories = ServiceCategory::pluck('name', 'id')->toArray();
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
        return view('services.create', compact('service_categories', 'work_units', 'is_selected', 'admin_categories', 'admin_promotion_categories', 'price_type', 'is_readystock', 'is_public'));
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
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image_real' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            'is_selected' => 'required',
            'price_type' => 'required|numeric',
            'is_readystock' => 'required|numeric',
            'is_public' => 'required|numeric',
        ])->validate();

        $fileName = "img-service-" . $request->file('image_real')->hashName();
        $file = $request->file('image_real');
        $img = Image::read($file->getRealPath());
        $img->contain(700, 700, 'efefef')->toJpeg(90)->save(storage_path('app/public/services/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image_real')->hashName();
        $file = $request->file('image_real');
        $img = Image::read($file->getRealPath());
        $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/services/' . $fileNameThumb));

        $fileNameReal = "img-real-" . $request->file('image_real')->hashName();
        $file = $request->file('image_real');
        $img = Image::read($file->getRealPath());
        $img->save(storage_path('app/public/services/' . $fileNameReal));

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
        $service = Service::create([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'service_category_id' => $request->service_category_id,
            'name' => $request->name,
            'user_id' => $user->id,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'image' => '/storage/services/' . $fileName,
            'image_thumb' => '/storage/services/' . $fileNameThumb,
            'image_real' => '/storage/services/' . $fileNameReal,
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
                $fileName = "img-service-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/services/' . $fileName));

                ServiceImage::create([
                    'service_id' => $service->id,
                    'image' => '/storage/services/' . $fileName,
                ]);
            }
        }

        $service->work_units()->attach($request->work_units);

        Toast::title('Jasa berhasil dibuat!')->autoDismiss(5);

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
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
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
    public function update(Request $request, Service $service)
    {
        Validator::make($request->all(), [
            'service_category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image_real' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            // 'is_selected' => 'required',
            'price_type' => 'required|numeric',
            // 'is_readystock' => 'required|numeric',
            // 'is_public' => 'required|numeric',
        ])->validate();

        if ($request->file('image_real')) {

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
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_real);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-service-" . $request->file('image_real')->hashName();
            $file = $request->file('image_real');
            $img = Image::read($file->getRealPath());
            $img->contain(700, 700, 'efefef')->toJpeg(90)->save(storage_path('app/public/services/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image_real')->hashName();
            $file = $request->file('image_real');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/services/' . $fileNameThumb));

            $fileNameReal = "img-real-" . $request->file('image_real')->hashName();
            $file = $request->file('image_real');
            $img = Image::read($file->getRealPath());
            $img->save(storage_path('app/public/services/' . $fileNameReal));

            $service->update([
                'image' => '/storage/services/' . $fileName,
                'image_thumb' => '/storage/services/' . $fileNameThumb,
                'image_real' => '/storage/services/' . $fileNameReal,
            ]);
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if($request->is_selected){
            $is_selected = $request->is_selected;
        } else {
            $is_selected = $service->is_selected;
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

        $service->update([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'service_category_id' => $request->service_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'fake_price' => $fake_price,
            'price_type' => $request->price_type,
            'is_selected' => $is_selected,
            // 'is_readystock' => $request->is_readystock,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
        ]);
        $service->work_units()->sync($request->work_units);

        $service_images = ServiceImage::where('service_id', $service->id)->get();
        foreach ($service_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        ServiceImage::where('service_id', $service->id)->delete();
        
        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-service-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/services/' . $fileName));

                ServiceImage::create([
                    'service_id' => $service->id,
                    'image' => '/storage/services/' . $fileName,
                ]);
            }
        }

        Toast::title('Jasa berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
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
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_real);
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
        Toast::title('Jasa berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('services.index');
    }
}
