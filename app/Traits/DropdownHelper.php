<?php

namespace App\Traits;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\BikeModelVariant;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use Illuminate\Database\Eloquent\Model;

trait DropdownHelper
{

    public static function getStates($data)
    {
        dd($data);
    }

    /**
     * Get Districts
     */
    public static function getDistricts($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['state_id'] = $data['id'];
        }

        $distModel = District::where('active_status', '1')->where($where)->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $distModel, 'type' => 'districts'])->render()
        );

        if (isset($data['dep_dd2_name']) && ($data['dep_dd2_name'] != '')) {
            $responseData['dep_dd2_name'] = $data['dep_dd2_name'];
            $responseData['dep_dd2_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'cities'])->render();
        } else {
            $responseData['dep_dd2_name'] = '';
            $responseData['dep_dd2_html'] = '';
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }


    public static function getCities($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['district_id'] = $data['id'];
        }

        $distModel = City::where('active_status', '1')->where($where)->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $distModel, 'type' => 'cities'])->render(),
            'dep_dd2_name' => '',
            'dep_dd2_html' => ''
        );

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    public static function getBranches($data)
    {
        $branches = Branch::where('active_status', '1')->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $branches, 'type' => 'cities'])->render(),
            'dep_dd2_name' => '',
            'dep_dd2_html' => ''
        );

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    public static function getDealers($data)
    {
        $branch_id = isset($data['id']) ? $data['id'] : 0;
        $dealers = BikeDealer::where('active_status', '1')->where('branch_id', $branch_id)->select('id', 'company_name')->get();
        $brands = BikeBrand::where('active_status', '1')->where('branch_id', $branch_id)->select('id', 'name')->get();
        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $dealers, 'type' => 'dealers'])->render(),
            'dep_dd2_name' => isset($data['dep_dd2_name']) ? $data['dep_dd2_name'] : '',
            'dep_dd2_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $brands, 'type' => 'brands'])->render(),
            'dep_dd3_name' => isset($data['dep_dd3_name']) ? $data['dep_dd3_name'] : '',
            'dep_dd3_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => [], 'type' => 'models'])->render(),
            'dep_dd4_name' => isset($data['dep_dd4_name']) ? $data['dep_dd4_name'] : '',
            'dep_dd4_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => [], 'type' => 'variants'])->render(),
            'dep_dd5_name' => isset($data['dep_dd5_name']) ? $data['dep_dd5_name'] : '',
            'dep_dd5_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => [], 'type' => 'colors'])->render()
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    //getDealers

    public static function getBrands($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['branch_id'] = $data['id'];
        }

        $brands = BikeBrand::where('active_status', '1')->where($where)->get();
        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $brands, 'type' => 'brands'])->render()
        );

        if (isset($data['dep_dd2_name']) && ($data['dep_dd2_name'] != '')) {
            $responseData['dep_dd2_name'] = $data['dep_dd2_name'];
            $responseData['dep_dd2_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'models'])->render();
        } else {
            $responseData['dep_dd2_name'] = '';
            $responseData['dep_dd2_html'] = '';
        }

        if (isset($data['dep_dd3_name']) && ($data['dep_dd3_name'] != '')) {
            $responseData['dep_dd3_name'] = $data['dep_dd3_name'];
            $responseData['dep_dd3_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'colors'])->render();
        } else {
            $responseData['dep_dd3_name'] = '';
            $responseData['dep_dd3_html'] = '';
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }


    public static function getModels($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['brand_id'] = $data['id'];
        }

        $bikeModel = BikeModel::where('active_status', '1')->where($where)->get();
        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $bikeModel, 'type' => 'models'])->render()
        );

        if (isset($data['dep_dd2_name']) && ($data['dep_dd2_name'] != '')) {
            $responseData['dep_dd2_name'] = $data['dep_dd2_name'];
            $responseData['dep_dd2_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'variants'])->render();
        } else {
            $responseData['dep_dd2_name'] = '';
            $responseData['dep_dd2_html'] = '';
        }

        if (isset($data['dep_dd3_name']) && ($data['dep_dd3_name'] != '')) {
            $responseData['dep_dd3_name'] = $data['dep_dd3_name'];
            $responseData['dep_dd3_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'colors'])->render();
        } else {
            $responseData['dep_dd3_name'] = '';
            $responseData['dep_dd3_html'] = '';
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    public static function getVariants($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['model_id'] = $data['id'];
        }

        $modelVariants = BikeModelVariant::where('active_status', '1')->where($where)->get();
        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $modelVariants, 'type' => 'variants'])->render()
        );

        if (isset($data['dep_dd2_name']) && ($data['dep_dd2_name'] != '')) {
            $responseData['dep_dd2_name'] = $data['dep_dd2_name'];
            $responseData['dep_dd2_html'] = view('admin.ajaxDropDowns.selectDefaultOptions', ['type' => 'colors'])->render();
        } else {
            $responseData['dep_dd2_name'] = '';
            $responseData['dep_dd2_html'] = '';
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    public static function getColors($data)
    {
        $where = array();
        if (isset($data['id']) && (intval($data['id']) > 0)) {
            $where['model_variant_id'] = $data['id'];
        }

        $distModel = BikeColor::where('active_status', '1')->where($where)->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $distModel, 'type' => 'colors'])->render()
        );

        $responseData['dep_dd2_name'] = '';
        $responseData['dep_dd2_html'] = '';

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }

    public static function getFinanciersList($data)
    {
        $where = array('financer_type' => '0');
        if (isset($data['id'])) {
            switch ($data['id']) {
                case 1:
                    $where = array('financer_type' => '0');
                    break;
                case 2:
                    $where = array('financer_type' => '1');
                    break;
                case 3:
                    $where = array('financer_type' => '2');
                    break;
            }
        }

        $finModel = BankFinancer::where($where)->select('id', 'bank_name')->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $finModel, 'type' => 'financers'])->render(),
        );

        $responseData['dep_dd2_name'] = '';
        $responseData['dep_dd2_html'] = '';

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Dropdwon Request",
            'data'       =>  $responseData
        ]);
    }
}
