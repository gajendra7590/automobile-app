<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CsvHelper
{
    public function parseCsvData($file_path)
    {
        $productsList = array();
        $headers = array();
        $file_handle = fopen($file_path, 'r');
        $i = 0;
        $j = 0;
        while (($data = fgetcsv($file_handle)) !== FALSE) {
            if ($i == 0) {
                $headers = $data;
            } else {
                for ($k = 0; $k < count($headers); $k++) {
                    $key = trim($headers[$k]);
                    $productsList[$j][$key] = (trim($data[$k]) == 'NaN') ? '' : (trim($data[$k]));
                }
                $j++;
            }
            $i++;
        }
        fclose($file_handle);
        unlink($file_path);
        return $productsList;
    }
}
