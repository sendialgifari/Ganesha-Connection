<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\AdminCategory;
use App\Models\AdminPromotionCategory;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationImage;
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

class DonationController extends Controller
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

        $categories = DonationCategory::pluck('name', 'id')->toArray();

        $user = Auth::user();
        if ($user->getRoleNames()[0] == "admin" || $user->getRoleNames()[0] == "superadmin") {
            $donations = QueryBuilder::for(Donation::class)
                ->defaultSort('name')
                ->allowedSorts(['name', 'donation_category.name', 'is_selected', 'user.name'])
                ->allowedFilters(['name', 'donation_category.name', AllowedFilter::exact('donation_category_id'), $globalSearch])
                ->paginate();

            return view('donations.index', [
                'donations' => SpladeTable::for($donations)
                    ->defaultSort('name')
                    ->column('name', 'Nama', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image', 'Gambar')
                    ->column('donation_category.name', 'Kategori produk', sortable: true, searchable: true)
                    ->column('is_selected', 'pilihan', sortable: true, searchable: true)
                    ->column('user.name', 'owner', sortable: true, searchable: true)
                    ->column('admin_category.name', 'admin kategori', sortable: true, searchable: true)
                    ->selectFilter('donation_category_id', $categories)
                    ->column('action')
                ,
            ]);
        } else {
            $donations = QueryBuilder::for(Donation::where('user_id', $user->id))
                ->defaultSort('name')
                ->allowedSorts(['name', 'donation_category.name'])
                ->allowedFilters(['name', 'donation_category.name', AllowedFilter::exact('donation_category_id'), $globalSearch])
                ->paginate();

            return view('donations.index', [
                'donations' => SpladeTable::for($donations)
                    ->defaultSort('name')
                    ->column('name', sortable: true, searchable: true)
                    ->withGlobalSearch(columns: ['name'])
                    ->column('image')
                    ->column('donation_category.name', sortable: true, searchable: true)
                    ->selectFilter('donation_category_id', $categories)
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
        $donation_categories = DonationCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        $is_selected = [
            '0' => 'No',
            '1' => 'Yes',
        ];
        $is_public = [
            '0' => 'Hanya Civitas ITB',
            '1' => 'Publik',
        ];
        return view('donations.create', compact('donation_categories', 'work_units', 'is_selected', 'admin_categories', 'admin_promotion_categories', 'is_public'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'donation_category_id' => 'required',
            'name' => 'required|max:255|unique:donations,name',
            'description' => 'required',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'is_selected' => 'required',
            'is_public' => 'required|numeric',
        ])->validate();

        $fileName = "img-donation-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileName));
        // $img->resize(1000, 1000, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/donations/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileNameThumb));
        // $img->resize(200, 200, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save(storage_path('app/public/donations/' . $fileNameThumb));

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));


        $admin_category_id = $request->admin_category_id;
        if($request->admin_category_id == 0){
            $admin_category_id = null;
        }

        $admin_promotion_category_id = $request->admin_promotion_category_id;
        if($request->admin_promotion_category_id == 0){
            $admin_promotion_category_id = null;
        }

        $user = Auth::user();
        $donation = Donation::create([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'donation_category_id' => $request->donation_category_id,
            'name' => $request->name,
            'user_id' => $user->id,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image' => '/storage/donations/' . $fileName,
            'image_thumb' => '/storage/donations/' . $fileNameThumb,
            'is_selected' => $request->is_selected,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
            'goal_amount' => $request->goal_amount,
        ]);

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-donation-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileName));

                DonationImage::create([
                    'donation_id' => $donation->id,
                    'image' => '/storage/donations/' . $fileName,
                ]);
            }
        }

        $donation->work_units()->attach($request->work_units);

        Toast::title('Donation berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('donations.index');
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
    public function edit(Donation $donation)
    {
        $donation->images = array_column($donation->images->toArray(), 'image');
        $admin_categories = AdminCategory::pluck('name', 'id')->toArray();
        $admin_categories[0] = "Tidak ada kategori admin";
        $admin_promotion_categories = AdminPromotionCategory::pluck('name', 'id')->toArray();
        $admin_promotion_categories[0] = "Tidak ada kategori admin";
        $donation_categories = DonationCategory::pluck('name', 'id')->toArray();
        $work_units = WorkUnit::pluck('name', 'id')->toArray();
        return view('donations.edit', [
            'donation' => $donation,
            'donation_categories' => $donation_categories,
            'work_units' => $work_units,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
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
    public function update(Request $request, Donation $donation)
    {
        Validator::make($request->all(), [
            'donation_category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'is_public' => 'required|numeric',
        ])->validate();

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $donation->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $donation->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-donation-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileNameThumb));

            $donation->update([
                'image' => '/storage/donations/' . $fileName,
                'image_thumb' => '/storage/donations/' . $fileNameThumb,
            ]);
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->is_selected) {
            $is_selected = $request->is_selected;
        } else {
            $is_selected = $donation->is_selected;
        }

        $admin_category_id = $request->admin_category_id;
        if($request->admin_category_id == 0){
            $admin_category_id = null;
        }

        $admin_promotion_category_id = $request->admin_promotion_category_id;
        if($request->admin_promotion_category_id == 0){
            $admin_promotion_category_id = null;
        }

        $donation->update([
            'admin_category_id' => $admin_category_id,
            'admin_promotion_category_id' => $admin_promotion_category_id,
            'donation_category_id' => $request->donation_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'is_selected' => $is_selected,
            'is_public' => $request->is_public,
            'slug' => $slug,
            'external_link' => $request->external_link,
            'goal_amount' => $request->goal_amount,
        ]);

        $donation->work_units()->sync($request->work_units);

        $donation_images = DonationImage::where('donation_id', $donation->id)->get();
        foreach ($donation_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        DonationImage::where('donation_id', $donation->id)->delete();

        if($request->images){
            foreach ($request->images as $value) {
                $fileName = "img-donation-" . $value->hashName();
                $file = $value;
                $img = Image::read($file->getRealPath());
                $img->cover(700, 700, 'center')->toJpeg(90)->save(storage_path('app/public/donations/' . $fileName));
    
                DonationImage::create([
                    'donation_id' => $donation->id,
                    'image' => '/storage/donations/' . $fileName,
                ]);
            }
        }

        Toast::title('Donation berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('donations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $donation->donation_comments()->delete();
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $donation->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $donation->image_thumb);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }

        $donation_images = DonationImage::where('donation_id', $donation->id)->get();
        foreach ($donation_images as $key => $value) {
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donations/' . str_replace('/storage/donations/', '', $value->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
        }

        DonationImage::where('donation_id', $donation->id)->delete();

        $donation->work_units()->detach();
        $donation->delete();
        Toast::title('Donation berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('donations.index');
    }
}
