<?php

return [
    [
        'section' => 'NAVIGASI',
        'name' => 'navigasi',
        'perms' => 'dashboard',
    ],
    // Dashboard
    [
        'name' => 'dashboard',
        'perms' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'fa fa-th-large',
        'url' => '/home',
    ],

    // TM 1
    [
        'name' => 'tm1',
        'title' => 'TM 1',
        'icon' => 'fas fa-clipboard-list',
        'submenu' => [
            [
                'name' => 'tm1.sam',
                'perms' => 'tm1.sam',
                'title' => 'Pembukuan SAM',
                'url' => '/tm1/sam',
            ],
            [
                'name' => 'tm1.lapak',
                'perms' => 'tm1.lapak',
                'title' => 'Pembukuan Lapak',
                'url' => '/tm1/lapak',
            ],
            [
                'name' => 'tm1.kas',
                'perms' => 'tm1.kas',
                'title' => 'Kas Lapak',
                'url' => '/tm1/kas',
            ],
            [
                'name' => 'tm1.loader',
                'perms' => 'tm1.loader',
                'title' => 'Loader',
                'url' => '/tm1/loader',
            ],
        ]
    ],

    // TM 2
    [
        'name' => 'tm2',
        'title' => 'TM 2',
        'icon' => 'fas fa-clipboard-list',
        'submenu' => [
            [
                'name' => 'tm2.sam',
                'perms' => 'tm2.sam',
                'title' => 'Pembukuan SAM',
                'url' => '/tm2/sam',
            ],
            [
                'name' => 'tm2.lapak',
                'perms' => 'tm2.lapak',
                'title' => 'Pembukuan Lapak',
                'url' => '/tm2/lapak',
            ],
            [
                'name' => 'tm2.kas',
                'perms' => 'tm2.kas',
                'title' => 'Kas Lapak',
                'url' => '/tm2/kas',
            ],
        ]
    ],

    // TM 3
    [
        'name' => 'tm3',
        'title' => 'TM 3',
        'icon' => 'fas fa-clipboard-list',
        'submenu' => [
            [
                'name' => 'tm3.sam',
                'perms' => 'tm3.sam',
                'title' => 'Pembukuan SAM',
                'url' => '/tm3/sam',
            ],
            [
                'name' => 'tm3.lapak',
                'perms' => 'tm3.lapak',
                'title' => 'Pembukuan Lapak',
                'url' => '/tm3/lapak',
            ],
            [
                'name' => 'tm3.kas',
                'perms' => 'tm3.kas',
                'title' => 'Kas Lapak',
                'url' => '/tm3/kas',
            ],
        ]
    ],

    // Admin Console
    [
        'section' => 'ADMIN KONSOL',
        'name' => 'console_admin',
    ],
    [
        'name' => 'master',
        'perms' => 'master',
        'title' => 'Data Master',
        'icon' => 'fa fa-database',
        'submenu' => [
            [
                'name' => 'master.lapak',
                'title' => 'Lapak',
                'url' => '/master/lapak'
            ],
            [
                'name' => 'master.pembayaran',
                'title' => 'Pembayaran Lainnya',
                'url' => '/master/pembayaran'
            ],
            [
                'name' => 'master.kendaraan',
                'title' => 'Kendaraan',
                'url' => '/master/kendaraan'
            ],
            // [
            
            //     'name' => 'master.org',
            //     'title' => 'Struktur Organisasi',
            //     'url' => '',
            //     'submenu' => [
            //         [
            //             'name' => 'master.org.root',
            //             'title' => 'Perusahaan',
            //             'url' => '/master/org/root'
            //         ],
            //         [
            //             'name' => 'master.org.boc',
            //             'title' => 'Pengawas',
            //             'url' => '/master/org/boc',
            //         ],
            //         [
            //             'name' => 'master.org.bod',
            //             'title' => 'Direksi',
            //             'url' => '/master/org/bod',
            //         ],
            //         [
            //             'name' => 'master.org.subsidiary',
            //             'title' => 'Subsidiary',
            //             'url' => '/master/org/subsidiary',
            //         ],
            //         [
            //             'name' => 'master.org.department',
            //             'title' => 'Departemen',
            //             'url' => '/master/org/department',
            //         ],
            //         [
            //             'name' => 'master.org.division',
            //             'title' => 'Divisi',
            //             'url' => '/master/org/division',
            //         ],
            //         [
            //             'name' => 'master.org.subdivision',
            //             'title' => 'Sub Divisi',
            //             'url' => '/master/org/subdivision',
            //         ],
            //         [
            //             'name' => 'master.org.position',
            //             'title' => 'Jabatan',
            //             'url' => '/master/org/position',
            //         ],
            //     ]
            // ],
            // [
            //     'name' => 'Geografis',
            //     'title' => 'Geografis',
            //     'url' => '',
            //     'submenu' => [
            //         [
            //             'name' => 'master.province',
            //             'title' => 'Provinsi',
            //             'url' => '/master/geografis/province'
            //         ],
            //         [
            //             'name' => 'master.city',
            //             'title' => 'Kota / Kabupaten',
            //             'url' => '/master/geografis/city'
            //         ],
            //     ]
            // ],
        ]
    ],
    [
        'name' => 'setting',
        'perms' => 'setting',
        'title' => 'Pengaturan Umum',
        'icon' => 'fa fa-cogs',
        'submenu' => [
            [
                'name' => 'setting.role',
                'title' => 'Hak Akses',
                'url' => '/setting/role',
            ],
            [
                'name' => 'setting.flow',
                'title' => 'Flow Approval',
                'url' => '/setting/flow',
            ],
            [
                'name' => 'setting.user',
                'title' => 'Manajemen User',
                'url' => '/setting/user',
            ],
            [
                'name' => 'setting.activity',
                'title' => 'History Aktivitas',
                'url' => '/setting/activity',
            ],
        ]
    ],
];
