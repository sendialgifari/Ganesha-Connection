<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Hash;
use Intervention\Image\Laravel\Facades\Image;
use ProtoneMedia\Splade\Facades\Toast;

class SliderController extends Controller
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

        $sliders = QueryBuilder::for(Slider::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('sliders.index', [
            'sliders' => SpladeTable::for($sliders)
                ->defaultSort('name')
                ->column('name', sortable: true, searchable: true)
                ->withGlobalSearch(columns: ['name'])
                ->column('image')
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:sliders,name',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ])->validate();

        $fileName = "img-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        // $img->scaleDown(width: 1280)->save(storage_path('app/public/sliders/' . $fileName));
        $img->toJpeg(85)->save(storage_path('app/public/sliders/' . $fileName));

        $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
        $file = $request->file('image');
        $img = Image::read($file->getRealPath());
        $img->scaleDown(width: 200)->save(storage_path('app/public/sliders/' . $fileNameThumb));

        Slider::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'image' => '/storage/sliders/' . $fileName,
            'image_thumb' => '/storage/sliders/' . $fileNameThumb,
        ]);

        Toast::title('Slider berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('sliders.index');
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
    public function edit(Slider $slider)
    {
        return view('sliders.edit', [
            'slider' => $slider
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ])->validate();

        if ($request->file('image')) {

            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/sliders/' . str_replace('/storage/sliders/', '', $slider->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/sliders/' . str_replace('/storage/sliders/', '', $slider->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $fileName = "img-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            // $img->scaleDown(width: 1280)->save(storage_path('app/public/sliders/' . $fileName));
            $img->toJpeg(85)->save(storage_path('app/public/sliders/' . $fileName));

            $fileNameThumb = "img-thumb-" . $request->file('image')->hashName();
            $file = $request->file('image');
            $img = Image::read($file->getRealPath());
            $img->scaleDown(width: 200)->save(storage_path('app/public/sliders/' . $fileNameThumb));

            $slider->update([
                'image' => '/storage/sliders/' . $fileName,
                'image_thumb' => '/storage/sliders/' . $fileNameThumb,
            ]);

        }

        $slider->update([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
        ]);

        Toast::title('Slider berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('sliders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/sliders/' . str_replace('/storage/sliders/', '', $slider->image);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/sliders/' . str_replace('/storage/sliders/', '', $slider->image_thumb);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }

        $slider->delete();
        Toast::title('Slider berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('sliders.index');
    }
}
