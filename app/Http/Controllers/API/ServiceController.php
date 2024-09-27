<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Service;
use Validator;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\JsonResponse;
class ServiceController extends BaseController
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(): JsonResponse
    {
        $services = Service::all();
        return $this->sendResponse(ServiceResource::collection($services), 'Services retrieved successfully.');
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $service = Service::create($input);
        
        return $this->sendResponse(new ServiceResource($service), 'Service created successfully.');
    } 
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    
    public function show($id): JsonResponse
    {
        $service = Service::find($id);
        if (is_null($service)) {
            return $this->sendError('Service not found.');
        }
        return $this->sendResponse(new ServiceResource($service), 'Service retrieved successfully.');
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Service $service): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $service->name = $input['name'];
        $service->detail = $input['detail'];
        $service->save();
        
        return $this->sendResponse(new ServiceResource($service), 'Service updated successfully.');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();
        return $this->sendResponse([], 'Service deleted successfully.');
    }
}