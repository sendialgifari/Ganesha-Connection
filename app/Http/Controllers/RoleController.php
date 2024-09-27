<?php

namespace App\Http\Controllers;

use App\Models\RoleWorkUnit;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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

class RoleController extends Controller
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

        $roles = QueryBuilder::for(Role::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'slug', 'is_selected'])
            ->allowedFilters(['name', 'slug', $globalSearch])
            ->paginate();



        return view('roles.index', [
            'roles' => SpladeTable::for($roles)
                ->defaultSort('name')
                ->column('name', sortable: true, searchable: true)
                ->withGlobalSearch(columns: ['name'])
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:roles,name',
        ])->validate();

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        Toast::title('Role was created!')->autoDismiss(5);

        return redirect()->route('roles.index');
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
    public function edit(Role $role)
    {
        return view('roles.edit',[
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
        ])->validate();

        $role->update([
            'name' => $request->name,
        ]);

        Toast::title('Role was updated!')->autoDismiss(5);

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        Toast::title('Role was deleted!')->danger()->autoDismiss(5);

        return redirect()->route('roles.index');
    }
}
