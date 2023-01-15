<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use CommonHelper;
    use HasFactory;

    protected $table = "purchase_invoices";

    protected $fillable = [
        'purchase_id',
        'purchase_invoice_number',
        'purchase_invoice_date',
        'gst_rate_id',
        'gst_rate_percent',
        'pre_gst_amount',
        'gst_amount',
        'ex_showroom_price',
        'discount_price',
        'grand_total',
        'created_by'
    ];

    protected $hidden = [];

    protected $casts = [];



    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if ($branch_id != '0' || $branch_id != 'null') {
            return $query->whereHas('purchase', function ($purchase) use ($branch_id) {
                $purchase->where('bike_branch', $branch_id);
            });
        }
    }


    /**
     * Map with Purchase
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
