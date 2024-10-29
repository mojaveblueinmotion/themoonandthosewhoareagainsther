<?php

namespace Database\Seeders;

use App\Models\Globals\Menu;
use App\Models\Globals\MenuFlow;
use Illuminate\Database\Seeder;

class MenuFlowSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // RISK ASSESSMENT
            // [
            //     'module'   => 'pembukuan_tm',
            //     'submenu' => [
            //         [
            //             'module'   => 'pembukuan ',
            //             'FLOWS'     => [
            //                 [
            //                     "role_id"   => 4,
            //                     "type"      => 1,
            //                 ],
            //             ],
            //         ],
            //         [
            //             'module'   => 'risk-assessment.inherent-risk',
            //             'FLOWS'     => [
            //                 [
            //                     "role_id"   => 4,
            //                     "type"      => 1,
            //                 ],
            //             ],
            //         ],
            //         [
            //             'module'   => 'risk-assessment.current-risk',
            //             'FLOWS'     => [
            //                 [
            //                     "role_id"   => 4,
            //                     "type"      => 1,
            //                 ],
            //             ],
            //         ],
            //     ]
            // ],
        ];


        ini_set("memory_limit", -1);
        $exists = [];
        $order = 1;
        try {
            foreach ($data as $row) {
                $menu = Menu::firstOrNew(['module' => $row['module']]);
                $menu->order = $order;
                $menu->save();
                $exists[] = $menu->id;
                $order++;
                if (!empty($row['submenu'])) {
                    foreach ($row['submenu'] as $val) {
                        $submenu = $menu->child()->firstOrNew(['module' => $val['module']]);
                        $submenu->order = $order;
                        $submenu->save();
                        $exists[] = $submenu->id;
                        $order++;
                        if (isset($val['FLOWS'])) {
                            $submenu->flows()->delete();
                            $f = 1;
                            foreach ($val['FLOWS'] as $key => $flow) {
                                $record = MenuFlow::firstOrNew([
                                    'menu_id'   => $submenu->id,
                                    'role_id'   => $flow['role_id'],
                                    'type'      => $flow['type'],
                                    'order'     => $f++,
                                ]);
                                $record->save();
                            }
                        }
                    }
                }
                if (isset($row['FLOWS'])) {
                    $menu->flows()->delete();
                    $f = 1;
                    foreach ($row['FLOWS'] as $key => $flow) {
                        $record = MenuFlow::firstOrNew([
                            'menu_id'   => $menu->id,
                            'role_id'   => $flow['role_id'],
                            'type'      => $flow['type'],
                            'order'     => $f++,
                        ]);
                        $record->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($flow, $th->getLine(), $th->getMessage(), $th->getTraceAsString());
            throw $th;
        }
        Menu::whereNotIn('id', $exists)->delete();
    }
}
