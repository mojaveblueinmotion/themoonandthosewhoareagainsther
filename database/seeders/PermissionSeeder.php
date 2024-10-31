<?php

namespace Database\Seeders;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            /** Example **/
            // [
            //     'name'          => 'settings.reportex',
            //     'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            // ],

            /** DASHBOARD **/
            [
                'name'          => 'dashboard',
                'action'        => ['view'],
            ],

            /** TM 1 **/
            [
                'name'          => 'tm1.lapak',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm1.sam',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm1.kas',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm1.loader',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],

            /** TM 2 **/
            [
                'name'          => 'tm2.lapak',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm2.sam',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm2.kas',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm2.loader',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],

            /** TM 3 **/
            [
                'name'          => 'tm3.lapak',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm3.sam',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm3.kas',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'tm3.loader',
                'action'        => ['view', 'create', 'edit', 'delete', 'approve'],
            ],

            /** REPORT **/
            [
                'name'          => 'report',
                'action'        => ['view'],
            ],

            /** ADMIN CONSOLE **/
            [
                'name'          => 'master',
                'action'        => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name'          => 'setting',
                'action'        => ['view', 'create', 'edit', 'delete'],
            ],
        ];

        $this->generate($permissions);

        $ROLES = [
            [
                'name'  => 'Administrator',
                'PERMISSIONS'   => [
                    'dashboard'                                             => ['view'],

                    'tm1.lapak'                                   => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm2.lapak'                                   => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm3.lapak'                                   => ['view', 'create', 'edit', 'delete', 'approve'],

                    'tm1.sam'                                     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm2.sam'                                     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm3.sam'                                     => ['view', 'create', 'edit', 'delete', 'approve'],

                    'tm1.kas'                                     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm2.kas'                                     => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm3.kas'                                     => ['view', 'create', 'edit', 'delete', 'approve'],

                    'tm1.loader'                                  => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm2.loader'                                  => ['view', 'create', 'edit', 'delete', 'approve'],
                    'tm3.loader'                                  => ['view', 'create', 'edit', 'delete', 'approve'],

                    'master'                                                => ['view', 'create', 'edit', 'delete'],
                    'setting'                                               => ['view', 'create', 'edit', 'delete'],
                ],
            ],
        ];
        foreach ($ROLES as $role) {
            $record = Role::firstOrNew(['name' => $role['name']]);
            $record->name = $role['name'];
            $record->save();
            $perms = [];
            foreach ($role['PERMISSIONS'] as $module => $actions) {
                foreach ($actions as $action) {
                    $perms[] = $module . '.' . $action;
                }
            }
            $perm_ids = Permission::whereIn('name', $perms)->pluck('id');
            $record->syncPermissions($perm_ids);
        }
    }

    public function generate($permissions)
    {
        // Role
        $admin = Role::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Administrator',
            ]
        );

        $perms_ids = [];
        foreach ($permissions as $row) {
            foreach ($row['action'] as $key => $val) {
                $name = $row['name'] . '.' . trim($val);
                $perms = Permission::firstOrCreate(compact('name'));
                $perms_ids[] = $perms->id;
                if (!$admin->hasPermissionTo($perms->name)) {
                    if ($name == 'monitoring.view') continue;
                    $admin->givePermissionTo($perms);
                }
            }
        }
        Permission::whereNotIn('id', $perms_ids)->delete();

        // Clear Perms Cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
