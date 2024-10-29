<?php

namespace Database\Seeders;

use App\Models\Master\Org\LevelPosition;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $position = [
            [
                "location_code" => '03',
                "level_name"    => "Direksi",
                "code"          => 1000,
                "name"          => "President Directore",
            ],
            [
                "location_code" => '0301',
                "level_name"    => "Kepala Divisi Internal Audit",
                "code"          => 1001,
                "name"          => "Kepala Divisi Internal Audit",
            ],
            [
                "location_code" => '0301',
                "level_name"    => "Officer Internal Audit",
                "code"          => 1002,
                "name"          => "Officer Internal Audit",
            ],
            [
                "location_code" => '05',
                "level_name"    => 'Finance Directore',
                "code"          => 1003,
                "name"          => 'Finance Directore',
            ],
            [
                "location_code" => '04',
                "level_name"    => 'Operation Directore',
                "code"          => 1004,
                "name"          => 'Operation Directore',
            ],
            [
                "location_code" => '0403',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1005,
                "name"          => 'Kadep Factory',
            ],
            [
                "location_code" => '0405',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1006,
                "name"          => 'Kadep HR',
            ],
            [
                "location_code" => '0502',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1007,
                "name"          => 'Kadep Finance',
            ],
            [
                "location_code" => '0401',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1008,
                "name"          => 'Kadep Plantation',
            ],
            [
                "location_code" => '0402',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1009,
                "name"          => 'Kadep R&D',
            ],
            [
                "location_code" => '0404',
                "level_name"    => 'Kepala Departemen',
                "code"          => 1010,
                "name"          => 'Kadep Services',
            ],
            [
                "location_code" => '050301',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1011,
                "name"          => 'Kadiv Legal',
            ],
            [
                "location_code" => '040602',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1012,
                "name"          => 'Kadiv Purchasing',
            ],
            [
                "location_code" => '040601',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1013,
                "name"          => 'Kadiv QSHE',
            ],
            [
                "location_code" => '040102',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1014,
                "name"          => 'Kadiv T&FE',
            ],
            [
                "location_code" => '050202',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1015,
                "name"          => 'Kadiv Logistic',
            ],
            [
                "location_code" => '040401',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1016,
                "name"          => 'Kadiv Kemitraan',
            ],
            [
                "location_code" => '040501',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1017,
                "name"          => 'Kadiv HC',
            ],
            [
                "location_code" => '040103',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1018,
                "name"          => 'Kadiv Harvesting',
            ],
            [
                "location_code" => '040102',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1019,
                "name"          => 'Kadiv Administrasi HR',
            ],
            [
                "location_code" => '050302',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1020,
                "name"          => 'Kadiv IT',
            ],
            [
                "location_code" => '040101',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1021,
                "name"          => 'Kadiv PAS',
            ],
            [
                "location_code" => '040603',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1022,
                "name"          => 'Kadiv B&D',
            ],
            [
                "location_code" => '040402',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1023,
                "name"          => 'Kadiv General Affair',
            ],
            [
                "location_code" => '050201',
                "level_name"    => 'Kepala Divisi',
                "code"          => 1024,
                "name"          => 'Kadiv Financial Accounting',
            ],
        ];

        foreach ($position as $val) {
            try {
                $position                   = Position::firstOrNew(['code' => $val['code']]);
                $position->location_id      = OrgStruct::where('code', $val['location_code'])->first()->id;
                $position->name             = $val['name'];
                $position->level_id         = LevelPosition::where("name", $val['level_name'])->first()->id;
                $position->save();
            } catch (\Throwable $th) {
                dd($val, $th->getLine(), $th->getMessage());
            }
        }
    }
}
