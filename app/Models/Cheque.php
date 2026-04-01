<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 */
class Cheque extends Model
{
    protected $fillable = [
        'cheque_no',
        'cheque_date',
        'cheque_amount',
        'bank_name',
        'branch_name',
        'account_no',
        'cheque_type',
        'status',
        'deposit_date',
        'realization_date',
        'bounce_date',
        'customer_id',
        'supplier_id',
        'invoice_id',
        'received_by',
        'deposited_by',
        'approved_by',
        'bank_charges',
        'penalty_amount',
        'remarks',
        'reference_no'
    ];

    // Relationships
//    public function customer()
//    {
//        return $this->belongsTo(Customer::class);
//    }
//
//    public function supplier()
//    {
//        return $this->belongsTo(Supplier::class);
//    }
//
//    public function invoice()
//    {
//        return $this->belongsTo(Invoice::class);
//    }
}
