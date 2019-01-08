<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;

class PhotoControllerAPI extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(UploadImageRequest $request)
    {
        $validated = $request->validated();
        
        $photo_url = null;
        if (isset($validated['photo'])){
            $path = $validated['photo']->store($validated['type'], 'public');
            $photo_url = basename($path); 
        }

        return response()->json($photo_url, 200);
    }
}
