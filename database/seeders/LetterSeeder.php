<?php

namespace Database\Seeders;

use App\Models\Master\Letter\Letter;
use Illuminate\Database\Seeder;

class LetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
        * o Program Kerja => rkia
        * o Instruksi Audit => instruction
        * o Langkah Kerja => langkah-kerja
        * o Biaya penugasan => fee
        * o Memo Opening => memo-opening
        * o Opening meeting => opening
        * o Permintaan Dokumen => doc-req
        * o Pemenuhan Dokumen => doc-full
        * o Kertas kerja => sample
        * o Tanggapan Auditee => feedback
        * o OPINI & REKOMENDASI => worksheet
        * o Memo Closing => memo-closing
        * o Closing meeting => closing
        * o Exit meeting => exiting
        * o Memo LHP => memo-lhp
        * o LHP => lha
        * o Laporan Bulanan => lb-spi
        **/
        $data = [
            // RKIA
            [
                'type' => 'rkia',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'AA-00-00',
            ],

            // PREPARATION
            [
                'type' => 'preparation.assignment',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'BB-00-00',
            ],
            [
                'type' => 'preparation.instruction',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'CC-00-00',
            ],
            [
                'type' => 'preparation.program-audit',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'DD-00-00',
            ],
            [
                'type' => 'preparation.fee',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'EE-00-00',
            ],

            // CONDUCTING
            [
                'type' => 'conducting.memo-opening',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'FF-00-00',
            ],
            [
                'type' => 'conducting.opening',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'GG-00-00',
            ],

            [
                'type' => 'conducting.doc-req',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'HH-00-00',
            ],
            [
                'type' => 'conducting.doc-full',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'II-00-00',
            ],
            [
                'type' => 'conducting.sample',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'JJ-00-01',
            ],
            [
                'type' => 'conducting.feedback',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'KK-00-01',
            ],
            [
                'type' => 'conducting.worksheet',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'LL-00-01',
            ],
            [
                'type' => 'conducting.memo-closing',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'MM-00-01',
            ],
            [
                'type' => 'conducting.closing',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'NN-00-01',
            ],

            // REPORTING - PELAPORAN AUDIT
            [
                'type' => 'reporting.memo-exiting',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'OO-00-01',
            ],
            [
                'type' => 'reporting.exiting',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'PP-00-01',
            ],
            [
                'type' => 'reporting.memo-lhp',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'QQ-00-01',
            ],
            [
                'type' => 'reporting.lha',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'RR-00-00',
            ],
            [
                'type' => 'meeting',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'SS-00-00',
            ],
            [
                'type' => 'field-inspection',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'TT-00-00',
            ],

            // followup
            [
                'type' => 'followup.memo-followup',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'UU-00-00',
            ],
            [
                'type' => 'followup.reschedule-followup',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'VV-00-00',
            ],
            [
                'type' => 'followup.monitoring-followup',
                'format' => '[NO]//[MONTH]/[YEAR]',
                'formulir-tambahan' => 'WW-00-00',
            ],
        ];

        foreach ($data as $val) {
            $record = Letter::firstOrNew(['type' => $val['type']]);
            $record->is_available   = 'noactive';
            $record->format         = $val['format'];
            $record->no_formulir    ??= 'FM-00-00';
            $record->no_formulir_tambahan   ??= strtoupper($val['formulir-tambahan']);
            $record->save();
        }
    }
}
