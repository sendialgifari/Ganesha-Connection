<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\WebinarComment;
use App\Models\Webinar;
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

class WebinarCommentController extends Controller
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

        $webinar_comments = QueryBuilder::for(WebinarComment::class)
            ->defaultSort('comment')
            ->allowedSorts(['comment', 'created_at'])
            ->allowedFilters(['comment', 'created_at', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('webinar_comments.index', [
            'webinar_comments' => SpladeTable::for($webinar_comments)
                ->defaultSort('created_at')
                ->withGlobalSearch()
                ->column('webinar.name', sortable: true, searchable: true)
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
            'webinar_id' => 'required',
            'comment' => 'required|max:255',
            'ratings' => 'required|numeric',
        ])->validate();

        $webinar = Webinar::where('id', Crypt::decrypt($request->webinar_id))->first();
        $updateData = ['total_comments' => $webinar->total_comments + 1];
        $total_comments = $webinar->total_comments + 1;
        $total_comment_star_1 = $webinar->total_comment_star_1;
        $total_comment_star_2 = $webinar->total_comment_star_2;
        $total_comment_star_3 = $webinar->total_comment_star_3;
        $total_comment_star_4 = $webinar->total_comment_star_4;
        $total_comment_star_5 = $webinar->total_comment_star_5;
        if($request->ratings == 1){
            $updateData['total_comment_star_1'] = $webinar->total_comment_star_1 + 1;
            $total_comment_star_1 = $total_comment_star_1 + 1;
        }
        if($request->ratings == 2){
            $updateData['total_comment_star_2'] = $webinar->total_comment_star_2 + 1;
            $total_comment_star_2 = $total_comment_star_2 + 1;
        }
        if($request->ratings == 3){
            $updateData['total_comment_star_3'] = $webinar->total_comment_star_3 + 1;
            $total_comment_star_3 = $total_comment_star_3 + 1;
        }
        if($request->ratings == 4){
            $updateData['total_comment_star_4'] = $webinar->total_comment_star_4 + 1;
            $total_comment_star_4 = $total_comment_star_4 + 1;
        }
        if($request->ratings == 5){
            $updateData['total_comment_star_5'] = $webinar->total_comment_star_5 + 1;
            $total_comment_star_5 = $total_comment_star_5 + 1;
        }
        $ratings = (($total_comment_star_1 * 1) + ($total_comment_star_2 * 2) + ($total_comment_star_3 * 3) + ($total_comment_star_4 * 4) + ($total_comment_star_5 * 5)) / $total_comments;

        $updateData['ratings'] = $ratings;

        $webinar->update($updateData);

        $user = Auth::user();
        WebinarComment::create([
            'webinar_id' => Crypt::decrypt($request->webinar_id),
            'comment' => $request->comment,
            'user_id' => $user->id,
            'ratings' => $request->ratings,
        ]);

        Toast::title('Komentar produk berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('detail', ['type'=>'produk','slug'=>$webinar->slug]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebinarComment $webinar_comment)
    {
        $webinar = Webinar::where('id', $webinar_comment->webinar_id)->first();
        $updateData = ['total_comments' => $webinar->total_comments - 1];
        $total_comments = $webinar->total_comments - 1;
        $total_comment_star_1 = $webinar->total_comment_star_1;
        $total_comment_star_2 = $webinar->total_comment_star_2;
        $total_comment_star_3 = $webinar->total_comment_star_3;
        $total_comment_star_4 = $webinar->total_comment_star_4;
        $total_comment_star_5 = $webinar->total_comment_star_5;
        if($webinar_comment->ratings == 1){
            $updateData['total_comment_star_1'] = $webinar->total_comment_star_1 - 1;
            $total_comment_star_1 = $total_comment_star_1 - 1;
        }
        if($webinar_comment->ratings == 2){
            $updateData['total_comment_star_2'] = $webinar->total_comment_star_2 - 1;
            $total_comment_star_2 = $total_comment_star_2 - 1;
        }
        if($webinar_comment->ratings == 3){
            $updateData['total_comment_star_3'] = $webinar->total_comment_star_3 - 1;
            $total_comment_star_3 = $total_comment_star_3 - 1;
        }
        if($webinar_comment->ratings == 4){
            $updateData['total_comment_star_4'] = $webinar->total_comment_star_4 - 1;
            $total_comment_star_4 = $total_comment_star_4 - 1;
        }
        if($webinar_comment->ratings == 5){
            $updateData['total_comment_star_5'] = $webinar->total_comment_star_5 - 1;
            $total_comment_star_5 = $total_comment_star_5 - 1;
        }
        $ratings = (($total_comment_star_1 * 1) + ($total_comment_star_2 * 2) + ($total_comment_star_3 * 3) + ($total_comment_star_4 * 4) + ($total_comment_star_5 * 5)) / $total_comments;

        $updateData['ratings'] = $ratings;
        $webinar->update($updateData);

        $webinar_comment->delete();
        Toast::title('Komentar produk berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('webinar_comments.index');
    }
}
