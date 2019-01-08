<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\User;
use Carbon\Carbon;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Hash;
use App\Notifications\AccountActivate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class UserControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $this->authorize('managerActions', auth()->guard('api')->user());

        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('managerActions', auth()->guard('api')->user());

        $validated = $request->validated();
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make(Carbon::now()->toDateTimeString());
        $user->activation_token = Hash::make($user->id.$user->username);
        $user->activation_token = str_replace('/', '-', $user->activation_token);
        $user->save();
        $user->notify(new AccountActivate($user));
        return response()->json(new UserResource($user), 201);
    }


    public function show($id)
    {
        $user = User::find($id);
        $this->authorize('authUserActions', $user);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('authUserActions', $user);
        
        $validated = $request->validated();

        if(strpos($validated['photo_url'], $user->photo_url) !== false){
            $validated['photo_url'] = $user->photo_url;
        }else{
            $path = 'public/profiles/'.$user->photo_url;
            Storage::delete($path);
        }
        $user->update($validated);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('alterAccess', $user);

        $path = 'public/profiles/'.$user->photo_url;
        Storage::delete($path);
        try {
            $user->delete();
        } catch (\Exception $e) {
            $user->deleted_at = Carbon::now()->toDateTimeString();
            $user->save();
        }
        
        return response()->json(null, 204);
    }

    public function myProfile(Request $request)
    {
        $this->authorize('authUserActions', $request->user());
        return new UserResource($request->user());
    }

    public function setPassword(SetPasswordRequest $request, $token){ 
        $validated = $request->validated();
        $user = User::where('activation_token', $token)->first();
        $user->activation_token = '';
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->password = Hash::make($validated['new']);
        $user->save();
        return new UserResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request, $id){ 
        $user = User::findOrFail($id);
        $this->authorize('authUserActions', $user);

        $validated = $request->validated();
        $user->password = Hash::make($validated['new']);
        $user->save();
        return new UserResource($user);
    }

    public function alterShift($id)
    {  
        
        $user = User::findOrFail($id);
        if((bool)$user->shift_active){
               $user->last_shift_end =	Carbon::now()->toDateTimeString();
               $user->shift_active = false;
        }else{
            $user->last_shift_start = Carbon::now()->toDateTimeString();
            $user->shift_active = true;
        }
        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function alterBlock($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('alterAccess', $user);
        if((bool)$user->blocked){
            $user->blocked = false;
        }else{
            $user->blocked = true;
        }
        $user->save();
        return response()->json(new UserResource($user), 201);
    }
}
