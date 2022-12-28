<?php

namespace App\Traits;

use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeModel;
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

        $distModel = BikeModel::where('active_status', '1')->where($where)->get();

        $responseData = array(
            'dep_dd_name' => isset($data['dep_dd_name']) ? $data['dep_dd_name'] : '',
            'dep_dd_html' => view('admin.ajaxDropDowns.selectOptions', ['data' => $distModel, 'type' => 'models'])->render()
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
            $where['bike_model'] = $data['id'];
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
}
