<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransfer extends Model
{
    use HasFactory;

    protected $table = "purchase_transfers";

    protected $fillable = [
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
