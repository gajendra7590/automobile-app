<?php

namespace Database\Seeders;

use App\Models\DocumentSectionTypes;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return $this->insertDocumentTypes();
    }

    /**
     * insertDocumentTypes
     */
    public function insertDocumentTypes()
    {
        $types = array(
            ['id' => 1,  'name' => 'USER PROFILE DOCUMENTS', 'short_name' => 'upd', 'status' => '0'],
            ['id' => 2,  'name' => 'PURCHASE DOCUMENTS', 'short_name' => 'pd', 'status' => '1'],
            ['id' => 3,  'name' => 'QUOTATION DOCUMENTS', 'short_name' => 'qd', 'status' => '1'],
            ['id' => 4,  'name' => 'SALE DOCUMENTS', 'short_name' => 'sd', 'status' => '1'],
            ['id' => 5,  'name' => 'SALES ACCOUNT DOCUMENTS', 'short_name' => 'sad', 'status' => '1'],
            ['id' => 6,  'name' => 'RTO REGISTRATION', 'short_name' => 'rr', 'status' => '1'],
            ['id' => 7,  'name' => 'RTO AGENT DOCUMENTS', 'short_name' => 'rad', 'status' => '1'],
            ['id' => 8,  'name' => 'FINANCER DOCUMENTS', 'short_name' => 'fd', 'status' => '1'],
            ['id' => 9,  'name' => 'DEALER DUCUMENTS', 'short_name' => 'dd', 'status' => '1'],
            ['id' => 10, 'name' => 'BIKE PARKING', 'short_name' => 'bp', 'status' => '1'],
            ['id' => 11, 'name' => 'OTHER DOCUMENTS', 'short_name' => 'od', 'status' => '0'],
        );

        foreach ($types as $type) {
            DocumentSectionTypes::updateOrCreate(['id' => $type['id']], $type);
        }
        return true;
    }
}
