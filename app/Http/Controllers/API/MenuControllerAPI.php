<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Carbon\Carbon;

class MenuControllerAPI extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return ItemResource::collection(Item::paginate(5));
        } else {
            return ItemResource::collection(Item::orderBy('name')->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemRequest $request)
    {  
        $this->authorize('managerActions', auth()->guard('api')->user());
        $validated = $request->validated();
        $item = new Item();
        $item->fill($request->all());
        $item->save();
        return response()->json(new ItemResource($item), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ItemResource(Item::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $this->authorize('managerActions', auth()->guard('api')->user());
        $validated = $request->validated();
        $item = Item::findOrFail($id);
        if(strpos($validated['photo_url'], $item->photo_url) !== false){
            $validated['photo_url'] = $item->photo_url;
        }else{
            $path = 'public/profiles/'.$item->photo_url;
            Storage::delete($path);
        }
        $item->fill($validated);
        $item->save();
        return response()->json(new ItemResource($item), 201);
    }

    public function destroy($id)
    {
        $this->authorize('managerActions', auth()->guard('api')->user());
        $item = Item::findOrFail($id);
        try {
            $item->delete();
        } catch (\Exception $e) {
            $item->deleted_at = Carbon::now()->toDateTimeString();
            $item->save();
        }
        
        return response()->json(null, 204);
    }
}
