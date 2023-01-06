<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\DropdownHelper;

class AjaxCommonController extends Controller
{
    use DropdownHelper;

    /**
     * Common function for handle common ajax dropdowns
     */
    public function index(Request $request)
    {
        try {
            $postData = $request->all();
            if (isset($postData['req']) && ($postData['req'] != '')) {

                switch ($postData['req']) {
                    case 'states':
                        return $this->getStates($postData);
                        break;
                    case 'districts':
                        return $this->getDistricts($postData);
                        break;
                    case 'cities':
                        return $this->getCities($postData);
                        break;
                    case 'branches':
                        return $this->getBranches($postData);
                        break;
                    case 'brands':
                        return $this->getBrands($postData);
                        break;
                    case 'models':
                        return $this->getModels($postData);
                        break;
                    case 'colors':
                        return $this->getColors($postData);
                        break;
                    case 'financiers_list':
                        return $this->getFinanciersList($postData);
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! invalid request"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }
}
