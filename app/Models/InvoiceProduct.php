<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    protected $fillable = ['invoice_id', 'product_id', 'user_id', 'qty', 'sale_price'];

    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,    // Final related model
            Invoice::class,     // Intermediate model
            'id',               // Foreign key on Invoice (local key of InvoiceProduct)
            'id',               // Foreign key on Customer (local key of Invoice)
            'invoice_id',       // Local key on InvoiceProduct (foreign key to Invoice)
            'customer_id'       // Local key on Invoice (foreign key to Customer)
        );
    }

    function product(): BelongsTo
    {
        return $this->belongsTo((Product::class));
    }

    function invoice()
    {
        return $this->belongsTo((Invoice::class));
    }
}
