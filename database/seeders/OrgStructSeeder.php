<?php

namespace Database\Seeders;

use App\Models\Master\Org\OrgStruct;
use Illuminate\Database\Seeder;

class OrgStructSeeder extends Seeder
{
    public function run()
    {
        $structs = [
            // type => 1:Dirut, 2:direktur,3 department, 4:division, 5:sub division
            // Level Root
            [
                'level'         => 'root',
                'name'          => config('base.company.name'),
                'phone'         => config('base.company.phone'),
                'address'       => config('base.company.address'),
                'email'         => config('base.company.email'),
                'website'       => config('base.company.website'),
                'code'          => 1000,
                'type'          => 0,
                'city_id'       => 156,
            ],
            // Level BoC
            [
                'level'         => 'boc',
                'name'          => 'Dewan Komisaris',
                'parent_code'   => 1000,
                'code'          => '01',
                'type'          => 0,
            ],
            [
                'level'         => 'boc',
                'name'          => 'Komite Audit',
                'parent_code'   => 1000,
                'code'          => '02',
                'type'          => 0,
            ],


            // Level BoD
            [
                'level'         => 'bod',
                'name'          => 'President Directore',
                'parent_code'   => 1000,
                'code'          => '03',
                'type'          => 1,
            ],
            [
                'level'         => 'bod',
                'name'          => 'Operation Directore',
                'parent_code'   => 1000,
                'code'          => '04',
            ],
            [
                'level'         => 'bod',
                'name'          => 'Finance Directore',
                'parent_code'   => 1000,
                'code'          => '05',
                'type'          => 2,
            ],

            // Level Subsidiary
            [
                'level'         => 'subsidiary',
                'name'          => 'PT Pemukasakti Manis Indah',
                'parent_code'   => '03',
                'code'          => '0301',
            ],
            [
                'level'         => 'subsidiary',
                'name'          => 'Yayasan Gunung Madu',
                'parent_code'   => '03',
                'code'          => '0302',
            ],
            [
                'level'         => 'subsidiary',
                'name'          => 'Koperasi Jasa Gunung Madu',
                'parent_code'   => '03',
                'code'          => '0303',
            ],
            [
                'level'         => 'subsidiary',
                'name'          => 'Yayayasan Pemukasakti Manisindah',
                'parent_code'   => '03',
                'code'          => '0304',
            ],
            // Level Departemen
            [
                'level'         => 'department',
                'name'          => 'Internal Audit',
                'parent_code'   => '03',
                'code'          => '0301',
                'type'          => 3
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Plantation',
                'parent_code'   => '04',
                'code'          => '0401',
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen R&D',
                'parent_code'   => '04',
                'code'          => '0402',
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Factory',
                'parent_code'   => '04',
                'code'          => '0403',
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Services',
                'parent_code'   => '04',
                'code'          => '0404',
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Human Resources',
                'parent_code'   => '04',
                'code'          => '0405',
            ],
            [
                'level'         => 'department',
                'name'          => 'Directore Office - Operation',
                'parent_code'   => '04',
                'code'          => '0406',
            ],
            [
                'level'         => 'department',
                'name'          => 'Departemen Finance',
                'parent_code'   => '05',
                'code'          => '0502',
            ],
            [
                'level'         => 'department',
                'name'          => 'Directore Office - Finance',
                'parent_code'   => '05',
                'code'          => '0506',
            ],

            // Level Divisi
            [
                'level'         => 'division',
                'name'          => 'Divisi Human Capital',
                'parent_code'   => '0405',
                'code'          => '040501',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Administrasi',
                'parent_code'   => '0405',
                'code'          => '040502',
            ],
            [
                'level'         => 'division',
                'name'          => 'Financial Accounting',
                'parent_code'   => '0502',
                'code'          => '050201',
            ],
            [
                'level'         => 'division',
                'name'          => 'Logistic (Warehouse)',
                'parent_code'   => '0502',
                'code'          => '050202',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Kemitraan',
                'parent_code'   => '0404',
                'code'          => '040401',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi General Affair',
                'parent_code'   => '0404',
                'code'          => '040402',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Security',
                'parent_code'   => '0404',
                'code'          => '040403',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi QSHE',
                'parent_code'   => '0406',
                'code'          => '040601',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Purchasing',
                'parent_code'   => '0406',
                'code'          => '040602',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Business & Development',
                'parent_code'   => '0406',
                'code'          => '040603',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Legal',
                'parent_code'   => '0502',
                'code'          => '050301',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Information Technology',
                'parent_code'   => '0502',
                'code'          => '050302',
                'type'          => 4,
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Plantation Admin Support',
                'parent_code'   => '0401',
                'code'          => '040101',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi T & FE',
                'parent_code'   => '0401',
                'code'          => '040102',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Harvesting',
                'parent_code'   => '0401',
                'code'          => '040103',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Area 1-7',
                'parent_code'   => '0401',
                'code'          => '040104',
            ],
            [
                'level'         => 'division',
                'name'          => 'Divisi Civil Maintenance Building',
                'parent_code'   => '0404',
                'code'          => '040404',
            ],

        ];

        foreach ($structs as $val) {
            $struct = OrgStruct::firstOrNew(['code' => $val['code']]);
            $struct->level   = $val['level'];
            $struct->name    = $val['name'];
            $struct->type    = $val['type'] ?? 0;
            $struct->phone   = $val['phone'] ?? null;
            $struct->address = $val['address'] ?? null;
            $struct->email   = $val['email'] ?? null;
            $struct->website = $val['website'] ?? null;
            $struct->city_id = $val['city_id'] ?? null;
            $struct->code   = $val['code'] ?? null;

            if (!empty($val['parent_code'])) {
                if ($parent = OrgStruct::where('code', $val['parent_code'])->first()) {
                    $struct->parent_id = $parent->id;
                }
            }
            $struct->save();
        }
    }
}
