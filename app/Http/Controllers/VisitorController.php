<?php

namespace App\Http\Controllers;

use App\Models\ServiceWorkUnit;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Visitor;
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

class VisitorController extends Controller
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
                        ->orWhere('url', 'LIKE', "%{$value}%")
                        ->orWhere('useragent', 'LIKE', "%{$value}%")
                        ->orWhere('platform', 'LIKE', "%{$value}%")
                        ->orWhere('browser', 'LIKE', "%{$value}%")
                        ->orWhere('ip', 'LIKE', "%{$value}%");
                });
            });
        });

        $visitors = QueryBuilder::for(Visitor::class)
            ->defaultSort('created_at')
            ->allowedSorts(['url', 'useragent', 'platform', 'browser', 'ip'])
            ->allowedFilters(['url', 'useragent', 'platform', 'browser', 'ip', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('visitors.index', [
            'visitors' => SpladeTable::for($visitors)
                ->defaultSort('created_at')
                ->withGlobalSearch()
                ->column('created_at', sortable: true, searchable: true)
                ->column('url', sortable: true, searchable: true)
                // ->column('useragent', sortable: true, searchable: true)
                ->column('platform', sortable: true, searchable: true)
                ->column('browser', sortable: true, searchable: true)
                ->column('ip', sortable: true, searchable: true)
            ,
        ]);
    }
}
