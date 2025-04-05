<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\DonationCategory;
use App\Models\Donation;
use App\Models\DonationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Laravel\Facades\Image;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class DonationCategoryController extends Controller
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

        $donation_categories = QueryBuilder::for(DonationCategory::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug', 'is_selected'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('donation_categories.index', [
            'donation_categories' => SpladeTable::for($donation_categories)
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
        return view('donation_categories.create', [
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
            'name' => 'required|max:255|unique:donation_categories,name',
            'is_selected' => 'required',
        ])->validate();

        $image = '';
        if ($request->file('image')) {
            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/donation_categories/' . $fileName));
            $image = '/storage/donation_categories/' . $fileName;
        }

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        DonationCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Kategori donation berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('donation_categories.index');
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
    public function edit(DonationCategory $donation_category)
    {
        return view('donation_categories.edit', [
            'donation_category' => $donation_category,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DonationCategory $donation_category)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_selected' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donation_categories/' . str_replace('/storage/donation_categories/', '', $donation_category->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->cover(200, 200, 'center')->save(storage_path('app/public/donation_categories/' . $fileName));

            $donation_category->update([
                'image' => '/storage/donation_categories/' . $fileName,
            ]);
        }

        $donation_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_selected' => $request->is_selected,
        ]);

        Toast::title('Kategori donation berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('donation_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DonationCategory $donation_category)
    {
        $donations = Donation::where('donation_category_id', $donation_category->id)->get();
        foreach ($donations as $donation) {
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
            $donation->delete();
        }
        $donation_category->donations()->delete();
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/donation_categories/' . str_replace('/storage/donation_categories/', '', $donation_category->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $donation_category->delete();
        Toast::title('Kategori donation berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('donation_categories.index');
    }
}
