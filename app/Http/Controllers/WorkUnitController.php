<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;

class WorkUnitController extends Controller
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

        $work_units = QueryBuilder::for(WorkUnit::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('work_units.index', [
            'work_units' => SpladeTable::for($work_units)
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
        return view('work_units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:work_units,name',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        WorkUnit::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        Toast::title('Work Unit was created!')->autoDismiss(5);

        return redirect()->route('work_units.index');
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
    public function edit(WorkUnit $work_unit)
    {
        return view('work_units.edit', [
            'work_unit' => $work_unit,
            'is_active' => [
                '0' => 'Non-active',
                '1' => 'Active',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkUnit $work_unit)
    {

        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_active' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name));

        $work_unit->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
            'slug' => $slug,
        ]);

        Toast::title('Work Unit was updated!')->autoDismiss(5);

        return redirect()->route('work_units.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkUnit $work_unit)
    {
        $work_unit->delete();
        Toast::title('Work Unit was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('work_units.index');
    }
}
