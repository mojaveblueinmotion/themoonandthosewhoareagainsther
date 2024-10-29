<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Pembukuan\Kendaraan;
use App\Models\Master\Pembukuan\Lapak;
use App\Models\Master\Pembukuan\Pembayaran;
use App\Models\Master\Risk\MainProcess;
use App\Models\Master\Risk\RiskRating;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'        => 'TM 1',
            ],
            [
                'name'        => 'TM 2',
            ],
            [
                'name'        => 'TM 3',
            ],
        ];

        foreach ($data as $val) {
            $record          = Lapak::firstOrNew(['name' => $val['name']]);
            $record->save();
        }

        $pembayaran = [
            [
                'name'        => 'Nando',
            ],
            [
                'name'        => 'Ko Akin',
            ],
            [
                'name'        => 'Ko Rico',
            ],
        ];

        foreach ($pembayaran as $val) {
            $record          = Pembayaran::firstOrNew(['name' => $val['name']]);
            $record->save();
        }

        $pembayaran = [
            [
                'name'                  => 'Gold',
                'no_kendaraan'          => 'BE8055SN',
            ],
            [
                'name'                  => 'Pink',
                'no_kendaraan'          => 'BE8018SN',
            ],
            [
                'name'                  => 'Silver',
                'no_kendaraan'          => 'BE8457SN',
            ],
            [
                'name'                  => 'Praz',
                'no_kendaraan'          => 'BE9254QD',
            ],
            [
                'name'                  => 'Pick Up',
                'no_kendaraan'          => 'BE8744SC',
            ],
        ];

        foreach ($pembayaran as $val) {
            $record          = Kendaraan::firstOrNew(['name' => $val['name']]);
            $record->no_kendaraan = $val['no_kendaraan'];
            $record->save();
        }
    }
}
