<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function CreateInvoice(Request $request)
    {
        DB::beginTransaction();

        try {

            $user_id = $request->header('id');

            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id
            ]);

            $invoiceID = $invoice->id;
            $products = $request->input('products');

            foreach ($products as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price']
                ]);
            }

            DB::commit();
            return 1;
        } catch (Exception  $e) {
            DB::rollBack();
            return 0;
        }
    }


    function SelectInvoice(Request $request)
    {
        $user_id = $request->header('id');

        return Invoice::where('user_id', $user_id)->with('customer')->get();
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
            $invoice_id = $request->input('inv_id');

            InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->delete();

            Invoice::where('id', $invoice_id)->delete();

            DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Invoice deleted"
            ]);
        } catch (Exception  $e) {
            DB::rollBack();
            return 0;
        }
    }
}
