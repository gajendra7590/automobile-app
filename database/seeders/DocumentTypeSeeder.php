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
            ['id' => 1,  'name' => 'USER PROFILE DOCUMENTS'],
            ['id' => 2,  'name' => 'PURCHASE DOCUMENTS'],
            ['id' => 3,  'name' => 'QUOTATION DOCUMENTS'],
            ['id' => 4,  'name' => 'SALE DOCUMENTS'],
            ['id' => 5,  'name' => 'SALES ACCOUNT DOCUMENTS'],
            ['id' => 6,  'name' => 'RTO REGISTRATION'],
            ['id' => 7,  'name' => 'RTO AGENT DOCUMENTS'],
            ['id' => 8,  'name' => 'FINANCER DOCUMENTS'],
            ['id' => 9,  'name' => 'DEALER DUCUMENTS'],
            ['id' => 10, 'name' => 'OTHER DOCUMENTS'],
        );

        foreach ($types as $type) {
            DocumentSectionTypes::updateOrCreate(['id' => $type['id']], $type);
        }
        return true;
    }
}
