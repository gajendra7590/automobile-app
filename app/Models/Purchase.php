<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;

class Purchase extends Model
{
    use CommonHelper;
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'uuid',
        'bike_branch',
        'bike_dealer',
        'bike_brand',
        'bike_model',
        'bike_model_color',
        'bike_type',
        'bike_fuel_type',
        'break_type',
        'wheel_type',
        'dc_number',
        'dc_date',
        'vin_number',
        'vin_physical_status',
        'variant',
        'sku',
        'sku_description',
        'hsn_number',
        'engine_number',
        'key_number',
        'service_book_number',
        'tyre_brand_id',
        'tyre_front_number',
        'tyre_rear_number',
        'battery_brand_id',
        'battery_number',
        'gst_rate',
        'gst_rate_percent',
        'pre_gst_amount',
        'gst_amount',
        'ex_showroom_price',
        'discount_price',
        'grand_total',
        'bike_description',
        'status',
        'created_by',
        'updated_by',
        'is_editable',
        'transfer_status',
        'invoice_status'
    ];

    protected  $hidden = [];

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



    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    /**
     * Mapping with branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'bike_branch');
    }

    /**
     * Mapping with dealers
     */
    public function dealer()
    {
        return $this->belongsTo(BikeDealer::class, 'bike_dealer');
    }

    /**
     * Mapping with brand
     */
    public function brand()
    {
        return $this->belongsTo(BikeBrand::class, 'bike_brand');
    }

    /**
     * Mapping with model
     */
    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'bike_model');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }

    /**
     * Mapping with color
     */
    public function modelColor()
    {
        return $this->belongsTo(BikeColor::class, 'bike_model_color');
    }
    /**
     * Mapping with color
     */
    public function color()
    {
        return $this->belongsTo(BikeColor::class, 'bike_model_color');
    }

    public function tyreBrand()
    {
        return $this->belongsTo(TyreBrand::class, 'tyre_brand_id');
    }

    public function batteryBrand()
    {
        return $this->belongsTo(BatteryBrand::class, 'battery_brand_id');
    }

    public function invoice()
    {
        return $this->hasOne(PurchaseInvoice::class, 'purchase_id');
    }

    public function brokers() {
        return $this->belongsToMany(Broker::class,'purchase_transfer')->latest();
    }

    public function purchase_transfer_latest()
    {
        return $this->hasOne(PurchaseTransfer::class)->latest();
    }

    public function transfers()
    {
        return $this->hasOne(PurchaseTransfer::class, 'purchase_id')->where('active_status', '1')->orderBy('id', 'DESC');
    }
}
