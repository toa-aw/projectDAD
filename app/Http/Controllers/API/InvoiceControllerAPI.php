<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Http\Requests\UpdateInvoiceRequest;
use PDF;

class InvoiceControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }
    
    public function index(Request $request){
        return InvoiceResource::collection(Invoice::all());
    }

    public function update(UpdateInvoiceRequest $request, $id){
        $validated = $request->validated();

        $invoice = Invoice::findOrFail($id);

        $invoice->state = 'paid';
        $invoice->name = $validated['name'];
        $invoice->nif = $validated['nif'];

        $meal = $invoice->meal;
        $meal->state = 'paid';

        $invoice->save();
        $meal->save();

        return InvoiceResource::make($invoice);
    }

    public function getPendingInvoices(){
        $pending_invoices = Invoice::all()->where('state', 'pending');
        $invoices = InvoiceResource::collection($pending_invoices);
        
        return $invoices;
    }

    public function downloadPDF($id)
    {
        $this->authorize('downloadPDF', auth()->guard('api')->user());
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json([
                'error' => __('messages.fail-find', [
                    'name' => trans_choice('messages.invoices', 1)
                ])
            ], 500);
        }
        if ($invoice->state != 'paid') {
            return response()->json([
                    'error' => __('messages.fail-not-final-state', [
                        'name' => trans_choice('messages.invoices', 1)
                    ])
            ], 500);
        }

        $pdf = PDF::loadView('invoice', compact('invoice'))->setPaper('A7');
        $file_name = 'invoice_' . $invoice->id . '_' . $invoice->state;
        return $pdf->download($file_name . '.pdf');
    }
}
