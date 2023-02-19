<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransfer extends Model
{
    use HasFactory;

    protected $table = "purchase_transfers";

    protected $fillable = [
        'sno',
        'year',
        'purchase_id',
        'broker_id',
        'transfer_date',
        'transfer_note',
        'return_date',
        'return_note',
        'total_price_on_road',
        'status',
        'active_status',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = PurchaseTransfer::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if ($year == $findModel->year) {
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

    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id')->with([
            'stateDetail' => function ($state) {
                $state->select('id', 'state_name');
            },
            'districtDetail' => function ($district) {
                $district->select('id', 'district_name');
            },
            'cityDetail' => function ($city) {
                $city->select('id', 'city_name');
            }
        ]);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id')
            ->select('id', 'bike_branch', 'bike_dealer', 'bike_brand', 'bike_model', 'bike_model_color', 'sku', 'engine_number', 'hsn_number', 'key_number')
            ->with([
                'branch' => function ($model) {
                    $model->select('*');
                },
                'dealer' => function ($model) {
                    $model->select('id', 'company_name');
                },
                'brand' => function ($model) {
                    $model->select('id', 'name');
                },
                'model' => function ($model) {
                    $model->select('id', 'model_name');
                },
                'modelColor' => function ($model) {
                    $model->select('id', 'color_name');
                },
                'tyreBrand' => function ($model) {
                    $model->select('id', 'name');
                },
                'batteryBrand' => function ($model) {
                    $model->select('id', 'name');
                }
            ]);
    }
}
