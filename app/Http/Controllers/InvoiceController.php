<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{


    function InvoiceListPage()
    {
        return Inertia::render('InvoiceListPage');
    }
    function SalePage()
    {
        return Inertia::render('SalePage');
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
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully',
                'invoice_id' => $invoiceID
            ], 201);
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

        $customerDetails = Customer::where('user_id', $user_id)->where('id', $customer_id)->first();
        $invoiceTotal = Invoice::where('user_id', $user_id)->where('id', $invoice_id)->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->with('product')->get();

        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct
        );
    }


    function InvoiceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');

            $validated = $request->validate([
                'inv_id' => 'required|exists:invoices,id'
            ]);

            $invoice_id = $validated['inv_id'];

            // Delete related invoice products
            InvoiceProduct::where('invoice_id', $invoice_id)
                ->where('user_id', $user_id)
                ->delete();

            // Delete invoice itself
            $deleted = Invoice::where('id', $invoice_id)
                ->where('user_id', $user_id)
                ->delete();

            if ($deleted === 0) {
                throw new \Exception("Invoice not found or does not belong to user");
            }

            DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Invoice deleted"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'fail',
                'message' => 'Invoice deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
