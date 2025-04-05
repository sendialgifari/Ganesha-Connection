<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use App\Models\AdminPromotionCategory;
use App\Models\Webinar;
use App\Models\WebinarCategory;
use App\Models\WebinarImage;
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

class WebinarController extends Controller
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

        $categories = WebinarCategory::pluck('name', 'id')->toArray();

        $user = Auth::user();
        if ($user->getRoleNames()[0] == "admin" || $user->getRoleNames()[0] == "superadmin") {
            $webinars = QueryBuilder::for(Webinar::class)
                ->defaultSort('name')
                ->allowedSorts(['name', 'webinar_category.name', 'is_selected', 'user.name'])
                ->allowedFilters(['name', 'webinar_category.name', AllowedFilter::exact('webinar_category_id'), $globalSearch])
                ->paginate();

            return view('webinars.index', [
                'webinars' => SpladeTable::for($webinars)
                    ->defaultSort('name')
                    ->column('name', 'Nama', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image', 'Gambar')
                    ->column('webinar_category.name', 'Kategori produk', sortable: true, searchable: true)
                    ->column('is_selected', 'pilihan', sortable: true, searchable: true)
                    ->column('user.name', 'owner', sortable: true, searchable: true)
                    ->column('admin_category.name', 'admin kategori', sortable: true, searchable: true)
                    ->selectFilter('webinar_category_id', $categories)
                    ->column('action')
                ,
            ]);
        } else {
            $webinars = QueryBuilder::for(Webinar::where('user_id', $user->id))
                ->defaultSort('name')
                ->allowedSorts(['name', 'webinar_category.name'])
                ->allowedFilters(['name', 'webinar_category.name', AllowedFilter::exact('webinar_category_id'), $globalSearch])
                ->paginate();

            return view('webinars.index', [
                'webinars' => SpladeTable::for($webinars)
                    ->defaultSort('name')
                    ->column('name', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image')
                    ->column('webinar_category.name', sortable: true, searchable: true)
                    ->selectFilter('webinar_category_id', $categories)
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
        $webinar_categories = WebinarCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        $is_selected = [
            '0' => 'No',
            '1' => 'Yes',
        ];
        $price_type = [
            '0' => 'Menggunakan harga',
            '1' => 'Hubungi kami',
        ];
        $is_public = [
            '0' => 'Hanya Civitas ITB',
            '1' => 'Publik',
        ];
        return view('webinars.create', compact('webinar_categories', 'work_units', 'is_selected', 'admin_categories', 'admin_promotion_categories', 'price_type', 'is_public'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'webinar_category_id' => 'required',
            'name' => 'required|max:255|unique:webinars,name',
            'description' => 'required',
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            'is_selected' => 'required',
            'price_type' => 'required|numeric',
            'is_public' => 'required|numeric',
        ])->validate();

        $fileName = "img-webinar-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileName));
        // $img->resize(1000, 1000, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/webinars/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileNameThumb));
        // $img->resize(200, 200, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/webinars/' . $fileNameThumb));

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
        $webinar = Webinar::create([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'webinar_category_id' => $request->webinar_category_id,
            'name' => $request->name,
            'user_id' => $user->id,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'image' => '/storage/webinars/' . $fileName,
            'image_thumb' => '/storage/webinars/' . $fileNameThumb,
            'fake_price' => $fake_price,
            'price_type' => $request->price_type,
            'is_selected' => $request->is_selected,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
            'datetime' => $request->datetime,
            'duration' => $request->duration,
        ]);

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-webinar-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileName));

                WebinarImage::create([
                    'webinar_id' => $webinar->id,
                    'image' => '/storage/webinars/' . $fileName,
                ]);
            }
        }

        $webinar->work_units()->attach($request->work_units);

        Toast::title('Webinar berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('webinars.index');
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
    public function edit(Webinar $webinar)
    {
        $webinar->images = array_column($webinar->images->toArray(), 'image');
        $admin_categories = AdminCategory::pluck('name', 'id')->toArray();
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
        $webinar_categories = WebinarCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        return view('webinars.edit', [
            'webinar' => $webinar,
            'webinar_categories' => $webinar_categories,
            'work_units' => $work_units,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
            'price_type' => [
                '0' => 'Menggunakan harga',
                '1' => 'Hubungi kami',
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
    public function update(Request $request, Webinar $webinar)
    {
        Validator::make($request->all(), [
            'webinar_category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            // 'price' => 'required|numeric',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            // 'fake_price' => 'required|numeric',
            // 'is_selected' => 'required',
            'price_type' => 'required|numeric',
            'is_public' => 'required|numeric',
        ])->validate();

        if ($request->file('image')) {

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

            $fileName = "img-webinar-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileNameThumb));

            $webinar->update([
                'image' => '/storage/webinars/' . $fileName,
                'image_thumb' => '/storage/webinars/' . $fileNameThumb,
            ]);
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->is_selected) {
            $is_selected = $request->is_selected;
        } else {
            $is_selected = $webinar->is_selected;
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

        $webinar->update([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'webinar_category_id' => $request->webinar_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $price,
            'short_description' => $request->short_description,
            'fake_price' => $fake_price,
            'price_type' => $request->price_type,
            'is_selected' => $is_selected,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
            'datetime' => $request->datetime,
            'duration' => $request->duration,
        ]);

        $webinar->work_units()->sync($request->work_units);

        $webinar_images = WebinarImage::where('webinar_id', $webinar->id)->get();
        foreach ($webinar_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/webinars/' . str_replace('/storage/webinars/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        WebinarImage::where('webinar_id', $webinar->id)->delete();

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-webinar-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/webinars/' . $fileName));
    
                WebinarImage::create([
                    'webinar_id' => $webinar->id,
                    'image' => '/storage/webinars/' . $fileName,
                ]);
            }
        }

        Toast::title('Webinar berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('webinars.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Webinar $webinar)
    {
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

        $webinar->work_units()->detach();
        $webinar->delete();
        Toast::title('Webinar berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('webinars.index');
    }
}
