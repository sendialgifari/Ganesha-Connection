<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\ServiceComment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Hash;
use Auth;
use Crypt;
use Intervention\Image\Laravel\Facades\Image;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Storage;

class ServiceCommentController extends Controller
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
                        ->orWhere('comment', 'LIKE', "%{$value}%");
                });
            });
        });

        $service_comments = QueryBuilder::for(ServiceComment::class)
            ->defaultSort('comment')
            ->allowedSorts(['comment', 'created_at'])
            ->allowedFilters(['comment', 'created_at', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('service_comments.index', [
            'service_comments' => SpladeTable::for($service_comments)
                ->defaultSort('created_at')
                ->withGlobalSearch()
                ->column('service.name', sortable: true, searchable: true)
                ->column('user.name', sortable: true, searchable: true)
                ->column('comment', sortable: true, searchable: true)
                ->column('ratings', sortable: true, searchable: true)
                ->column('action')
            ,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'service_id' => 'required',
            'comment' => 'required|max:255',
            'ratings' => 'required|numeric',
        ])->validate();

        $service = Service::where('id', Crypt::decrypt($request->service_id))->first();
        $updateData = ['total_comments' => $service->total_comments + 1];
        $total_comments = $service->total_comments + 1;
        $total_comment_star_1 = $service->total_comment_star_1;
        $total_comment_star_2 = $service->total_comment_star_2;
        $total_comment_star_3 = $service->total_comment_star_3;
        $total_comment_star_4 = $service->total_comment_star_4;
        $total_comment_star_5 = $service->total_comment_star_5;
        if($request->ratings == 1){
            $updateData['total_comment_star_1'] = $service->total_comment_star_1 + 1;
            $total_comment_star_1 = $total_comment_star_1 + 1;
        }
        if($request->ratings == 2){
            $updateData['total_comment_star_2'] = $service->total_comment_star_2 + 1;
            $total_comment_star_2 = $total_comment_star_2 + 1;
        }
        if($request->ratings == 3){
            $updateData['total_comment_star_3'] = $service->total_comment_star_3 + 1;
            $total_comment_star_3 = $total_comment_star_3 + 1;
        }
        if($request->ratings == 4){
            $updateData['total_comment_star_4'] = $service->total_comment_star_4 + 1;
            $total_comment_star_4 = $total_comment_star_4 + 1;
        }
        if($request->ratings == 5){
            $updateData['total_comment_star_5'] = $service->total_comment_star_5 + 1;
            $total_comment_star_5 = $total_comment_star_5 + 1;
        }
        $ratings = (($total_comment_star_1 * 1) + ($total_comment_star_2 * 2) + ($total_comment_star_3 * 3) + ($total_comment_star_4 * 4) + ($total_comment_star_5 * 5)) / $total_comments;

        $updateData['ratings'] = $ratings;

        $service->update($updateData);

        $user = Auth::user();
        ServiceComment::create([
            'service_id' => Crypt::decrypt($request->service_id),
            'comment' => $request->comment,
            'user_id' => $user->id,
            'ratings' => $request->ratings,
        ]);

        Toast::title('Komentar jasa berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('detail', ['type'=>'jasa','slug'=>$service->slug]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceComment $service_comment)
    {

        $service = Service::where('id', $service_comment->service_id)->first();
        $updateData = ['total_comments' => $service->total_comments - 1];
        $total_comments = $service->total_comments - 1;
        $total_comment_star_1 = $service->total_comment_star_1;
        $total_comment_star_2 = $service->total_comment_star_2;
        $total_comment_star_3 = $service->total_comment_star_3;
        $total_comment_star_4 = $service->total_comment_star_4;
        $total_comment_star_5 = $service->total_comment_star_5;
        if($service_comment->ratings == 1){
            $updateData['total_comment_star_1'] = $service->total_comment_star_1 - 1;
            $total_comment_star_1 = $total_comment_star_1 - 1;
        }
        if($service_comment->ratings == 2){
            $updateData['total_comment_star_2'] = $service->total_comment_star_2 - 1;
            $total_comment_star_2 = $total_comment_star_2 - 1;
        }
        if($service_comment->ratings == 3){
            $updateData['total_comment_star_3'] = $service->total_comment_star_3 - 1;
            $total_comment_star_3 = $total_comment_star_3 - 1;
        }
        if($service_comment->ratings == 4){
            $updateData['total_comment_star_4'] = $service->total_comment_star_4 - 1;
            $total_comment_star_4 = $total_comment_star_4 - 1;
        }
        if($service_comment->ratings == 5){
            $updateData['total_comment_star_5'] = $service->total_comment_star_5 - 1;
            $total_comment_star_5 = $total_comment_star_5 - 1;
        }
        $ratings = (($total_comment_star_1 * 1) + ($total_comment_star_2 * 2) + ($total_comment_star_3 * 3) + ($total_comment_star_4 * 4) + ($total_comment_star_5 * 5)) / $total_comments;

        $updateData['ratings'] = $ratings;
        $service->update($updateData);

        $service_comment->delete();
        Toast::title('Komentar jasa berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('service_comments.index');
    }
}
