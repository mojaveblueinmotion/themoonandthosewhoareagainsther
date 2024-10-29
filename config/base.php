<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Laravel'),
        'version' => 'v1.0.0',
        'copyright' => 'All Rights Reserved',
    ],

    'company' => [
        'key'       => 'gmp',
        'name'      => 'Tunas Mekar',
        'phone'     => '(021) 3853628',
        'address'   => '',
        'email'     => '',
        'website'   => '',
        'city'      => 'KOTA ADM. JAKARTA PUSAT',
        'province'  => 'DKI JAKARTA',
    ],

    'logo' => [
        'favicon' => 'assets/media/logos/logo-tm.png',
        'auth' => 'assets/media/logos/logo-tm.png',
        'aside' => 'assets/media/logos/logo-tm.png',
        'login' => 'assets/media/logos/logo-tm.png',
        'print' => 'assets/media/logos/logo-tm.png',
        'barcode' => 'assets/media/logos/logo-tm.png',
    ],

    'mail' => [
        'send' => env('MAIL_SEND_STATUS', false),
        'logo' => '',
    ],

    'custom-menu' => true,
];
