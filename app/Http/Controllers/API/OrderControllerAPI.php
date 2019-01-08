<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use App\Meal;
use App\Http\Resources\Order as OrderResource;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request){
        if ($request->has('page')) {
            return OrderResource::collection(Order::paginate(5));
        } else {
            return OrderResource::collection(Order::all());
        }
    }

    public function store(CreateOrderRequest $request)
    {
        $this->authorize('waiterActions', auth()->guard('api')->user());
        
        $request->validated();
        $order = new Order;
        $order->fill($request->all());
        $order->start = Carbon::now()->toDateTimeString();
        $meal = $order->meal;
        $meal->total_price_preview += $order->item->price;
        $meal->save();
        $order->save();
        return OrderResource::make($order);
    }

    public function destroy($id)
    {
        $this->authorize('waiterActions', auth()->guard('api')->user());
        $order = Order::findOrFail($id);
       
        if($order->state !== 'pending'){
            $errors = 'This order can not be deleted';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
        }

        $now = Carbon::now();
        $start = Carbon::parse($order->start);
        $secondsPassed = $now->diffInSeconds($start);
        if($secondsPassed > 5){
            $errors = 'This order can not be deleted';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
        }

        $meal = $order->meal;
        $meal->total_price_preview -= $order->item->price;
        $meal->save();
        $order->delete();
        return response()->json(null, 204);
    }

    public function getCooksOrders($id){
        $this->authorize('cookActions', auth()->guard('api')->user());
        $user = User::findOrFail($id);
        if($user->type !== 'cook'){
            $errors = 'You can not perfomr this action';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
            return 0;
        }
        $orders = $user->orders()->whereIn('state', ['confirmed','in preparation'])->orderBy('start', 'asc');
        return OrderResource::collection($orders->get());
        
    }

    public function filterOrders(Request $request)
    {

        $states = ['in preparation', 'confirmed', 'prepared', 'delivered', 'not delivered'];
        if(!in_array($request->state, $states )){
            $errors = 'You can not perfomr this action';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
        }
        $orders = Order::whereIn('state', $request->state);
        return OrderResource::collection($orders)->get();
    }

    public function getUnassigned(){
        $this->authorize('cookActions', auth()->guard('api')->user());
        $orders = Order::whereIn('state', ['confirmed','in preparation'])->where('responsible_cook_id', null)
            ->orderBy('start', 'asc');
        return OrderResource::collection($orders->get());
    }

    public function changeState(UpdateOrderRequest $request, $id){
        $this->authorize('changeOrderState', auth()->guard('api')->user());

        $validated = $request->validated();
        $order = Order::findOrFail($id);
        $user = User::findOrFail($validated['userId']);

        if($validated['state'] === 'in preparation'){
            $order->responsible_cook_id = $user->id;
        }

        if($validated['state'] === 'delivered' || $validated['state'] === 'not delivered'){
            $order->end = Carbon::now()->toDateTimeString();
        }
        $order->state = $validated['state'];
        $order->save();

        return response()->json(new OrderResource($order), 201);
    }

    public function show($id)
    {
        return OrderResource::make(Order::findOrFail($id));
    }
}
