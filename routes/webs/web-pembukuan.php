<?php

// TM 1
Route::namespace('Tm1')->prefix('tm1')->name('tm1.')->group(function () {
    // SAM
    Route::get('sam/{record}/detail', 'PembukuanSamController@detail')->name('sam.detail');
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');

    // Grid
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');
    Route::post('sam/{record}/detailGridShow', 'PembukuanSamController@detailGridShow')->name('sam.detailGridShow');

    // Detail
    Route::get('sam/{record}/detailCreate', 'PembukuanSamController@detailCreate')->name('sam.detailCreate');
    Route::post('sam/{id}/detailStore', 'PembukuanSamController@detailStore')->name('sam.detailStore');
    Route::get('sam/{detail}/detailEdit', 'PembukuanSamController@detailEdit')->name('sam.detailEdit');
    Route::get('sam/{detail}/detailShow', 'PembukuanSamController@detailShow')->name('sam.detailShow');
    Route::patch('sam/{detail}/detailUpdate', 'PembukuanSamController@detailUpdate')->name('sam.detailUpdate');
    Route::delete('sam/{detail}/detailDestroy', 'PembukuanSamController@detailDestroy')->name('sam.detailDestroy');

    Route::post('sam/{record}/revisi', 'PembukuanSamController@revisi')->name('sam.revisi');

    Route::grid(
        'sam',
        'PembukuanSamController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );


    // LAPAK
    Route::get('lapak/{record}/detail', 'PembukuanLapakController@detail')->name('lapak.detail');
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');

    // Grid
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');
    Route::post('lapak/{record}/detailGridShow', 'PembukuanLapakController@detailGridShow')->name('lapak.detailGridShow');

    // Detail
    Route::get('lapak/{record}/detailCreate', 'PembukuanLapakController@detailCreate')->name('lapak.detailCreate');
    Route::post('lapak/{id}/detailStore', 'PembukuanLapakController@detailStore')->name('lapak.detailStore');
    Route::get('lapak/{detail}/detailEdit', 'PembukuanLapakController@detailEdit')->name('lapak.detailEdit');
    Route::get('lapak/{detail}/detailShow', 'PembukuanLapakController@detailShow')->name('lapak.detailShow');
    Route::patch('lapak/{detail}/detailUpdate', 'PembukuanLapakController@detailUpdate')->name('lapak.detailUpdate');
    Route::delete('lapak/{detail}/detailDestroy', 'PembukuanLapakController@detailDestroy')->name('lapak.detailDestroy');

    Route::post('lapak/{record}/revisi', 'PembukuanLapakController@revisi')->name('lapak.revisi');

    Route::grid(
        'lapak',
        'PembukuanLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );


    // KAS LAPAK
    Route::get('kas/{record}/detail', 'KasLapakController@detail')->name('kas.detail');
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');

    // Grid
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');
    Route::post('kas/{record}/detailGridShow', 'KasLapakController@detailGridShow')->name('kas.detailGridShow');

    // Detail
    Route::get('kas/{record}/detailCreate', 'KasLapakController@detailCreate')->name('kas.detailCreate');
    Route::post('kas/{id}/detailStore', 'KasLapakController@detailStore')->name('kas.detailStore');
    Route::get('kas/{detail}/detailEdit', 'KasLapakController@detailEdit')->name('kas.detailEdit');
    Route::get('kas/{detail}/detailShow', 'KasLapakController@detailShow')->name('kas.detailShow');
    Route::patch('kas/{detail}/detailUpdate', 'KasLapakController@detailUpdate')->name('kas.detailUpdate');
    Route::delete('kas/{detail}/detailDestroy', 'KasLapakController@detailDestroy')->name('kas.detailDestroy');

    Route::post('kas/{record}/revisi', 'KasLapakController@revisi')->name('kas.revisi');

    Route::grid(
        'kas',
        'KasLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );
});

// TM 2

Route::namespace('Tm2')->prefix('tm2')->name('tm2.')->group(function () {
    // SAM
    Route::get('sam/{record}/detail', 'PembukuanSamController@detail')->name('sam.detail');
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');

    // Grid
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');
    Route::post('sam/{record}/detailGridShow', 'PembukuanSamController@detailGridShow')->name('sam.detailGridShow');

    // Detail
    Route::get('sam/{record}/detailCreate', 'PembukuanSamController@detailCreate')->name('sam.detailCreate');
    Route::post('sam/{id}/detailStore', 'PembukuanSamController@detailStore')->name('sam.detailStore');
    Route::get('sam/{detail}/detailEdit', 'PembukuanSamController@detailEdit')->name('sam.detailEdit');
    Route::get('sam/{detail}/detailShow', 'PembukuanSamController@detailShow')->name('sam.detailShow');
    Route::patch('sam/{detail}/detailUpdate', 'PembukuanSamController@detailUpdate')->name('sam.detailUpdate');
    Route::delete('sam/{detail}/detailDestroy', 'PembukuanSamController@detailDestroy')->name('sam.detailDestroy');

    Route::post('sam/{record}/revisi', 'PembukuanSamController@revisi')->name('sam.revisi');

    Route::grid(
        'sam',
        'PembukuanSamController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );


    // LAPAK
    Route::get('lapak/{record}/detail', 'PembukuanLapakController@detail')->name('lapak.detail');
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');

    // Grid
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');
    Route::post('lapak/{record}/detailGridShow', 'PembukuanLapakController@detailGridShow')->name('lapak.detailGridShow');

    // Detail
    Route::get('lapak/{record}/detailCreate', 'PembukuanLapakController@detailCreate')->name('lapak.detailCreate');
    Route::post('lapak/{id}/detailStore', 'PembukuanLapakController@detailStore')->name('lapak.detailStore');
    Route::get('lapak/{detail}/detailEdit', 'PembukuanLapakController@detailEdit')->name('lapak.detailEdit');
    Route::get('lapak/{detail}/detailShow', 'PembukuanLapakController@detailShow')->name('lapak.detailShow');
    Route::patch('lapak/{detail}/detailUpdate', 'PembukuanLapakController@detailUpdate')->name('lapak.detailUpdate');
    Route::delete('lapak/{detail}/detailDestroy', 'PembukuanLapakController@detailDestroy')->name('lapak.detailDestroy');

    Route::post('lapak/{record}/revisi', 'PembukuanLapakController@revisi')->name('lapak.revisi');

    Route::grid(
        'lapak',
        'PembukuanLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );


    // KAS LAPAK
    Route::get('kas/{record}/detail', 'KasLapakController@detail')->name('kas.detail');
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');

    // Grid
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');
    Route::post('kas/{record}/detailGridShow', 'KasLapakController@detailGridShow')->name('kas.detailGridShow');

    // Detail
    Route::get('kas/{record}/detailCreate', 'KasLapakController@detailCreate')->name('kas.detailCreate');
    Route::post('kas/{id}/detailStore', 'KasLapakController@detailStore')->name('kas.detailStore');
    Route::get('kas/{detail}/detailEdit', 'KasLapakController@detailEdit')->name('kas.detailEdit');
    Route::get('kas/{detail}/detailShow', 'KasLapakController@detailShow')->name('kas.detailShow');
    Route::patch('kas/{detail}/detailUpdate', 'KasLapakController@detailUpdate')->name('kas.detailUpdate');
    Route::delete('kas/{detail}/detailDestroy', 'KasLapakController@detailDestroy')->name('kas.detailDestroy');

    Route::post('kas/{record}/revisi', 'KasLapakController@revisi')->name('kas.revisi');

    Route::grid(
        'kas',
        'KasLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );
});


// TM 3

Route::namespace('Tm3')->prefix('tm3')->name('tm3.')->group(function () {
    // SAM
    Route::get('sam/{record}/detail', 'PembukuanSamController@detail')->name('sam.detail');
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');

    // Grid
    Route::post('sam/{record}/detailGrid', 'PembukuanSamController@detailGrid')->name('sam.detailGrid');
    Route::post('sam/{record}/detailGridShow', 'PembukuanSamController@detailGridShow')->name('sam.detailGridShow');

    // Detail
    Route::get('sam/{record}/detailCreate', 'PembukuanSamController@detailCreate')->name('sam.detailCreate');
    Route::post('sam/{id}/detailStore', 'PembukuanSamController@detailStore')->name('sam.detailStore');
    Route::get('sam/{detail}/detailEdit', 'PembukuanSamController@detailEdit')->name('sam.detailEdit');
    Route::get('sam/{detail}/detailShow', 'PembukuanSamController@detailShow')->name('sam.detailShow');
    Route::patch('sam/{detail}/detailUpdate', 'PembukuanSamController@detailUpdate')->name('sam.detailUpdate');
    Route::delete('sam/{detail}/detailDestroy', 'PembukuanSamController@detailDestroy')->name('sam.detailDestroy');

    Route::post('sam/{record}/revisi', 'PembukuanSamController@revisi')->name('sam.revisi');

    Route::grid(
        'sam',
        'PembukuanSamController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );

    // LAPAK
    Route::get('lapak/{record}/detail', 'PembukuanLapakController@detail')->name('lapak.detail');
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');

    // Grid
    Route::post('lapak/{record}/detailGrid', 'PembukuanLapakController@detailGrid')->name('lapak.detailGrid');
    Route::post('lapak/{record}/detailGridShow', 'PembukuanLapakController@detailGridShow')->name('lapak.detailGridShow');

    // Detail
    Route::get('lapak/{record}/detailCreate', 'PembukuanLapakController@detailCreate')->name('lapak.detailCreate');
    Route::post('lapak/{id}/detailStore', 'PembukuanLapakController@detailStore')->name('lapak.detailStore');
    Route::get('lapak/{detail}/detailEdit', 'PembukuanLapakController@detailEdit')->name('lapak.detailEdit');
    Route::get('lapak/{detail}/detailShow', 'PembukuanLapakController@detailShow')->name('lapak.detailShow');
    Route::patch('lapak/{detail}/detailUpdate', 'PembukuanLapakController@detailUpdate')->name('lapak.detailUpdate');
    Route::delete('lapak/{detail}/detailDestroy', 'PembukuanLapakController@detailDestroy')->name('lapak.detailDestroy');

    Route::post('lapak/{record}/revisi', 'PembukuanLapakController@revisi')->name('lapak.revisi');

    Route::grid(
        'lapak',
        'PembukuanLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );
    

    // KAS LAPAK
    Route::get('kas/{record}/detail', 'KasLapakController@detail')->name('kas.detail');
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');

    // Grid
    Route::post('kas/{record}/detailGrid', 'KasLapakController@detailGrid')->name('kas.detailGrid');
    Route::post('kas/{record}/detailGridShow', 'KasLapakController@detailGridShow')->name('kas.detailGridShow');

    // Detail
    Route::get('kas/{record}/detailCreate', 'KasLapakController@detailCreate')->name('kas.detailCreate');
    Route::post('kas/{id}/detailStore', 'KasLapakController@detailStore')->name('kas.detailStore');
    Route::get('kas/{detail}/detailEdit', 'KasLapakController@detailEdit')->name('kas.detailEdit');
    Route::get('kas/{detail}/detailShow', 'KasLapakController@detailShow')->name('kas.detailShow');
    Route::patch('kas/{detail}/detailUpdate', 'KasLapakController@detailUpdate')->name('kas.detailUpdate');
    Route::delete('kas/{detail}/detailDestroy', 'KasLapakController@detailDestroy')->name('kas.detailDestroy');

    Route::post('kas/{record}/revisi', 'KasLapakController@revisi')->name('kas.revisi');

    Route::grid(
        'kas',
        'KasLapakController',
        [
            'with' => ['submit', 'approval', 'print', 'history', 'tracking']
        ]
    );
});