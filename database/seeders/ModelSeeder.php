<?php

namespace Database\Seeders;

use App\Models\BikeColor;
use App\Models\BikeModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bike_models')->truncate();
        DB::table('bike_colors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $today = date('Y-m-d H:i:s');
        $AllModels = [
            //Hero
            array(
                [
                    'brand_id'   => 1,
                    'model_name' => 'GLAMOUR',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "BLUE BLACK", "color_code" => "BBK"],
                        ['color_name' => "MATT VERNIER GREY", "color_code" => "MVG"],
                        ['color_name' => "BLACK AND ACCENT", "color_code" => "BLA"]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'GLAMOUR XTEC',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "CANDY BLAZING RED", "color_code" => "BRD"],
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'HF DELUXE',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "PURPLE BLACK", "color_code" => ""],
                        ['color_name' => "TECHNO BLUE", "color_code" => ""],
                        ['color_name' => "RED BLACK", "color_code"   => ""],
                        ['color_name' => "PURPLE BLACK", "color_code" => ""],
                        ['color_name' => "NEXUS BLUE", "color_code" => ""],
                        ['color_name' => "BLACK RED STRIPE", "color_code" => ""],
                        ['color_name' => "BLACK - GREY STRIPE", "color_code" => ""],
                        ['color_name' => "ALL BLACK NEXUS BLUE", "color_code" => ""],
                        ['color_name' => "CANDY BLAZING RED", "color_code" => ""]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'MAESTRO EDGE 125',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "PANTHER BLACK", "color_code" => "PBK"],
                        ['color_name' => "PEARL SILVER WHITE", "color_code" => "PSW"],
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'PASSION PRO',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "MOON YELLOW", "color_code" => ""],
                        ['color_name' => "HEAVY GREY", "color_code" => ""],
                        ['color_name' => "FI SPORTS RED", "color_code" => ""],
                        ['color_name' => "FI BLACK", "color_code" => ""],
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'PASSION XTEC',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "CANDY BLAZING R", "color_code" => ""],
                        ['color_name' => "BLACK POLESTAR BLUE", "color_code" => ""]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'SPLENDOR +',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "MATT SHIELD GOLD", "color_code" => ""],
                        ['color_name' => "BLACK RED", "color_code" => ""]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'SPLENDOR+ XTEC',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "BLACK TORNADO GREY", "color_code" => ""]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'SUPER SPLENDOR',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "METALLIC NEXUS BLUE", "color_code" => ""],
                        ['color_name' => "BLACK SILVER STRIPE", "color_code" => ""],
                        ['color_name' => "CANDY BLAZING RED", "color_code" => ""]
                    ]
                ],
                [
                    'brand_id' => 1,
                    'model_name' => 'XTREME 160R',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => [
                        ['color_name' => "PEARL SILVER WHITE", "color_code" => ""]
                    ]
                ]
            ),
            //Honda
            array(
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 125 DRUM',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 125 DISK',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 125 DRUM ALLOY',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 6G STD',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 6G DLX',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'ACTIVA 6G SP. EDITION',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'GRAZIA DRUM',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'GRAZIA DISC',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'GRAZIA DISC REPSOL',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CD 110 DREAM DLX',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CB 125 SHINE DRUM',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CB 125 SHINE DISC',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CB 125 SHINE DRUM LTD EDI',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CB 125 SHINE DISC LTD EDI',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'HORNET 2.0',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'UNICORN ABS -160',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'SP 125 SHINE DRUM',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'SP 125 SHINE  DISC',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'NEW DIO STD',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'NEW DIO DLX',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'LIVO DRUM',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'LIVO DISC',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][1]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'X BLADE ABS',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ],
                [
                    'brand_id' => 2,
                    'model_name' => 'CB2000X CB190NX 2ID',
                    'model_code' => 'CODE',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['honda'][0]
                ]
            ),
            //Bajaj
            array(
                [
                    'brand_id' => 3,
                    'model_name' => 'CT100 Alloy KS Alloy Upgrade-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT110 Alloy-ES-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT110 X-ES-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT110 X-ES-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT110 X-KS BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT125 X-ES DRUM BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'CT125 X-ES DISC BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Platina 100 ES Drum 4 Speed',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Platina 100 ES Alloy DISC BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Platina 110 ES Drum 5 Speed',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Platina 110 ES H Disc BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Platina 110 ES 5 Speed Disc ABS',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-150 SD BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-150 TD BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-150 TD BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-150 Neon BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125 Neon Drum BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125 Drum Split Seat BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125 Neon Disc BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125  Disc Split Seat- BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125  Disc Split Seat- BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-NS 125',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125 Carbon Disc Single Seat Bs6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-125 Carbon Disc Split Seat Bs6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-180 Bikini Fairing BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR 160 NS TD BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR N 160 Single Channel',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR N 160 Dual Channel',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR NS 200 BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-220 BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR-220 BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'AVENGER 160 Street BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'AVENGER 220 Cruise BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR RS 200 Twin Channel-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR 250 F',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'PULSAR 250 N',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Dominar D 250-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Dominar D 400-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ],
                [
                    'brand_id' => 3,
                    'model_name' => 'Dominar D 400-BS6',
                    'model_code' => '',
                    'active_status' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                    'colors'     => ($this->colors())['bajaj'][0]
                ]
            )
        ];

        foreach ($AllModels as $models) {
            foreach ($models as $model) {
                $colors = $model['colors'];
                unset($model['colors']);
                $createData = $model;

                $create = BikeModel::create($createData);
                if ($create) {
                    $model_id = $create->id;
                    foreach ($colors as $color) {
                        $color['bike_model'] = $model_id;
                        BikeColor::create($color);
                    }
                }
            }
        }

        // print_r($create);
        // die;
        // DB::table('bike_models')->insert($create);
        DB::commit();
    }

    public function colors()
    {
        return array(
            'hero' => [],
            'honda' => [
                array(
                    ['color_name' => "BLK/RED", "color_code" => ""],
                    ['color_name' => "BLK/BLUE", "color_code" => ""],
                    ['color_name' => "BLK/SILVER", "color_code" => ""],
                    ['color_name' => "RED", "color_code" => ""],
                    ['color_name' => "WHITE", "color_code" => ""],
                    ['color_name' => "BLUE", "color_code" => ""]
                ),
                array(
                    ['color_name' => "BLK/GREEN", "color_code" => ""],
                    ['color_name' => "GREEY", "color_code" => ""]
                )
            ],
            'bajaj' => [
                array(
                    ['color_name' => "EBONY BLK RED DKL", "color_code" => ""],
                    ['color_name' => "FLAME RED DECAL RED", "color_code" => ""],
                    ['color_name' => "MATTE WILD GREEN", "color_code" => ""],
                    ['color_name' => "EBONY BLK BLUE DKL", "color_code" => ""],
                    ['color_name' => "Ebony Black with Silver Grey Decal", "color_code" => ""],
                    ['color_name' => "BLACK/ DECAL GREEN", "color_code" => ""],
                    ['color_name' => "EB BLK WITH METTALIC RED DECAL", "color_code" => ""],
                    ['color_name' => "EB BLK WITH BRASS GOLD DECAL", "color_code"  => ""],
                    ['color_name' => "BLK GLOS PEW GRA", "color_code" => ""],
                    ['color_name' => "EBONY BLK SOLAR RED", "color_code" => ""],
                    ['color_name' => "GLOSS PEWTER GREY", "color_code" => ""],
                    ['color_name' => "E Black with Platinum Silver Decal", "color_code" => ""],
                    ['color_name' => "BEACH BLUE", "color_code" => ""],
                    ['color_name' => "SPARKLING METALLIC BLACK BLUE", "color_code" => ""],
                    ['color_name' => "SPARKLING METALLIC BLACK RED", "color_code" => ""],
                    ['color_name' => "SPARKLING METALLIC BLACK SILVER", "color_code" => ""],
                    ['color_name' => "FIERY ORANGE", "color_code" => ""],
                    ['color_name' => "FIERY ORANGE", "color_code" => ""]
                )
            ],
        );
    }
}
