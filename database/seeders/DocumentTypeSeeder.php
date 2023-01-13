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
            ['id' => 1,  'name' => 'USER PROFILE'],
            ['id' => 2,  'name' => 'PURCHASES'],
            ['id' => 3,  'name' => 'QUOTATIONS'],
            ['id' => 4,  'name' => 'SALES'],
            ['id' => 5,  'name' => 'SALES ACCOUNTS'],
            ['id' => 6,  'name' => 'RTO REGISTRATION'],
            ['id' => 7,  'name' => 'RTO AGENTS DOCUMENT'],
            ['id' => 8,  'name' => 'FINANCER DOCUMENT'],
            ['id' => 9,  'name' => 'DEALERS DUCUMENT'],
            ['id' => 10, 'name' => 'OTHER'],
        );

        foreach ($types as $type) {
            DocumentSectionTypes::updateOrCreate(['id' => $type['id']], $type);
        }
        return true;
    }
}
