<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InvoiceController extends Controller
{


    function InvoiceListPage(Request $request)
    {
        $user_id = $request->header('id');

        $list = Invoice::with('customer')->where('user_id', $user_id)
        ->orderBy('created_at', 'desc') // descending order
        ->get();

        return Inertia::render('InvoiceListPage',['list' => $list]);
    }
    function SalePage(Request $request)
    {
        $user_id = $request->header('id');
        $customer = Customer::where('user_id', $user_id)->get();
        $products =  Product::where('user_id', $user_id)->get();
        return Inertia::render('SalePage',['customers' => $customer,'products' => $products]);
    }

    function CreateInvoice(Request $request)
    {
        DB::beginTransaction();

        try {
            $user_id = $request->header('id');

            $validated = $request->validate([
                'total' => 'required|numeric',
                'discount' => 'required|numeric',
                'vat' => 'required|numeric',
                'payable' => 'required|numeric',
                'customer_id' => 'required|exists:customers,id',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.qty' => 'required|numeric|min:1',
                'products.*.sale_price' => 'required|numeric|min:0'
            ]);

            $invoice = Invoice::create([
                'total' => $validated['total'],
                'discount' => $validated['discount'],
                'vat' => $validated['vat'],
                'payable' => $validated['payable'],
                'user_id' => $user_id,
                'customer_id' => $validated['customer_id']
            ]);

            $invoiceID = $invoice->id;

            foreach ($validated['products'] as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $product['product_id'],
                    'qty' => $product['qty'],
                    'sale_price' => $product['sale_price']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('message',"Invoice created");
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'fail',
                'message' => 'Invoice creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    function SelectInvoice(Request $request)
    {
        $user_id = $request->header('id');

        return Invoice::where('user_id', $user_id)->with('customer')->get();
    }
    function InvoiceList(Request $request)
    {
        $user_id = $request->header('id');

        return InvoiceProduct::with(['product', 'invoice.customer'])->where('user_id', $user_id)->get();
    }


    function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('cus_id');
        $invoice_id = $request->input('inv_id');
    
        $customerDetails = Customer::where('user_id', $user_id)
                                   ->where('id', $customer_id)
                                   ->first();
    
        $invoiceTotal = Invoice::where('user_id', $user_id)
                               ->where('id', $invoice_id)
                               ->first();
    
        $invoiceProduct = InvoiceProduct::where('invoice_id', $invoice_id)
                                        ->where('user_id', $user_id)
                                        ->with('product')
                                        ->get();
    
        return [
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct
        ];
    }
    


    public function InvoiceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');


            $invoice_id = request()->invoice_id;
            InvoiceProduct::where('invoice_id', $invoice_id)
                ->where('user_id', $user_id)
                ->delete();

            // Delete the invoice
            $deleted = Invoice::where('id', $invoice_id)
                ->where('user_id', $user_id)
                ->delete();

            if ($deleted === 0) {
                throw new \Exception("Invoice not found or does not belong to user");
            }

            DB::commit();

            return Redirect::back()->with([
                'status' => true,
                'message' => "Invoice deleted successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'status' => false,
                'message' => 'Invoice deletion failed: ' . $e->getMessage()
            ]);
        }
    }
}
