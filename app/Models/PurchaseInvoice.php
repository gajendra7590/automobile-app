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
        'sno',
        'year',
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

    protected $appends = ['serial_number'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = PurchaseInvoice::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if (isset($findModel->year) && ($year == $findModel->year)) {
                $sno = ($findModel->sno) + 1;
            }
            $model->year = $year;
            $model->sno = $sno;
        });
    }

    public function getSerialNumberAttribute()
    {
        return $this->year . '/' . $this->sno;
    }


    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if (!empty($branch_id)) {
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
