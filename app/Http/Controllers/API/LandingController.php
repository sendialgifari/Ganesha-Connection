<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
class LandingController extends BaseController
{
    
    public function search(Request $request)
    {
        $query = strtolower($request->input('query'));
        if($request->input('page')){
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $count = (int)$request->input('count');
        $limit_range = ((int)$page-1)*$count;

        if($request->input('category_type') == "products"){
            $detail_data = Product::orderBy('created_at', 'DESC');
        } elseif($request->input('category_type') == "services"){
            $detail_data = Service::orderBy('created_at', 'DESC');
        }
        
        if($query){
            $detail_data = $detail_data->whereRaw('lower(name) like (?)',["%{$query}%"]);
        } 
        if ($request->input('category_id')){
            if($request->input('category_type') == "products"){
                $detail_data = $detail_data->where('product_category_id', $request->input('category_id'));
            } elseif($request->input('category_type') == "services"){
                $detail_data = $detail_data->where('product_category_id', $request->input('category_id'));
            }
        }
        if($request->input('location')){
            $filter_location = $request->input('location');
            $users = User::where('city_id', $filter_location)->pluck('id')->toArray();
            $detail_data = $detail_data->whereIn('user_id', $users);
        } 
        if ($request->input('price')){
            $filter_price = explode(',', $request->input('price'));
            $detail_data = $detail_data->whereBetween('price', [$filter_price[0], $filter_price[1]]);
        }

        foreach ($detail_data->take($count)->skip($limit_range)->get() as $idx => $val) {

        }

        dd($request->input('query'));
        // return $this->sendResponse(ProductResource::collection($request->all()), 'Search retrieved successfully.');
    }
}