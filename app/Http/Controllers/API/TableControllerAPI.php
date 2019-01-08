<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Table;
use App\Meal;
use App\Http\Resources\Table as TableResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TableControllerAPI extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $tables = Table::all();
        return TableResource::collection($tables);
    }

    public function store(Request $request)
    {  
        $this->authorize('managerActions', auth()->guard('api')->user());
        if(!is_numeric($request['table_number']) || $request['table_number'] < 1){
            $errors = 'Table number must be a positive number';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
            return 0;
        }

        $tableExists = Table::find($request['table_number']);
        if($tableExists){
            $errors = 'You can not perfomr this action';
            throw new HttpResponseException(response()->json(['errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));;
            return 0;
        }

 
        $table = new Table();
        $table->table_number = $request['table_number'];
        $table->save();
        return TableResource::make($table);
    }

    public function destroy($id)
    {
        $this->authorize('managerActions', auth()->guard('api')->user());
  
        try {
            $table = Table::findOrFail($id);
            $table->delete();
        } catch (\Exception $e) {
            $table->deleted_at = Carbon::now()->toDateTimeString();
            $table->save();
        }
        return response()->json(null, 204);
    }

    public function getFreeTables() 
    {
        $this->authorize('getFreeTables', auth()->guard('api')->user());
        $tables = Table::all();

        foreach ($tables as $key => $value) {

            $meal = $value->meals()->where('state', 'active')->first();
            if($meal === null){
                $freeTables [] = $tables[$key];
            }
        }
        return $freeTables;
    }
}
