<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $transaction_id
 * @property int|null $product_id
 * @property string $product_name
 * @property int $qty
 * @property int $price_at_transaction
 * @property int $subtotal
 */
class TransactionItem extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'product_name', 'qty', 'price_at_transaction', 'subtotal'];

    /** @return BelongsTo<Transaction, $this> */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
