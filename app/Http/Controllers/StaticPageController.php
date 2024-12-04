<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class StaticPageController extends Controller
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
                        ->orWhere('name', 'LIKE', "%{$value}%");
                });
            });
        });

        $static_pages = QueryBuilder::for(StaticPage::class)
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('static_pages.index', [
            'static_pages' => SpladeTable::for($static_pages)
                ->defaultSort('name')
                ->withGlobalSearch()
                ->column('name', sortable: true, searchable: true)
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('static_pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:products,name',
            'description' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        StaticPage::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
        ]);

        Toast::title('Halaman statis berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('static_pages.index');
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
    public function edit(StaticPage $static_page)
    {
        return view('static_pages.edit', [
            'static_page' => $static_page
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaticPage $static_page)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:products,name',
            'description' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        $static_page->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
        ]);

        Toast::title('Halaman statis berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('static_pages.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaticPage $static_page)
    {
        $static_page->delete();
        Toast::title('Halaman statis berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('static_pages.index');
    }
}
