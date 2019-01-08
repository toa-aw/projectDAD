<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Meal;
use App\Invoice;
use App\Http\Resources\Meal as MealResource;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateMealRequest;
use App\Http\Requests\UpdateMealRequest;
use Illuminate\Support\Facades\Storage;

class MealControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        if ($request->has('page')) {
            return MealResource::collection(Meal::paginate(5));
        } else {
            return MealResource::collection(Meal::all());
        }
      
    }

    public function store(CreateMealRequest $request)
    {
        $this->authorize('waiterActions', auth()->guard('api')->user());
        $validated = $request->validated();
        $meal = new Meal();
        $meal->fill($validated);
        $meal->start = Carbon::now()->toDateTimeString();

        $meal->save();
        return MealResource::make($meal);
    }

    public function update(UpdateMealRequest $request, $id)
    {
        $validated = $request->validated();
        $meal = Meal::findOrFail($id);
        $user = User::findOrFail($validated['userId']);

        if($validated['state'] === 'terminated'){
            $orders = $meal->orders;
            $invoice =  new Invoice();
            $invoice->state = 'pending';
            $invoice->meal_id = $id;
            $invoice->date = Carbon::now()->toDateString();
            $invoice->total_price = $meal->total_price_preview;
            $invoice->save();

            foreach ($orders as $key => $order) {
                if($order->state !== 'delivered'){
                    $order->state = 'not delivered';
                    $order->end = Carbon::now()->toDateTimeString();
                    $meal->total_price_preview -= $order->item->price;
                    $order->save();
                }else{

                    try {
                        $invoice->items()->attach($order->item, ['quantity' => 1, 'unit_price' => $order->item->price, 'sub_total_price' => $order->item->price]);
                        $invoice->save();   
                    } catch (\Exception $e) {
                        $update = $invoice->items()->where('id', $order->item->id)->first()->pivot;
                        $invoice->items()->updateExistingPivot($order->item, ['quantity' => $update->quantity + 1, 'sub_total_price' => $update->sub_total_price + $update->unit_price]);
                        $invoice->save(); 
                    }
             
                }
            }
            
         }
        $invoice->total_price = $meal->total_price_preview; 
        $invoice->save();
        $meal->state = $validated['state'];
        $meal->save();
        return MealResource::make($meal);
    }

    public function getWaitersMeals($id)
    {
        $this->authorize('waiterActions', auth()->guard('api')->user());
        $user = User::findOrFail($id);
        $meals = $user->meals()->where('state', 'active')->get();
        return MealResource::collection($meals);
    }
}
