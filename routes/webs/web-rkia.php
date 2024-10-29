<?php

// RKIA
Route::namespace('Rkia')->prefix('rkia')->name('rkia.')->group(function () {
    // Operation / Operasional
    Route::get('operation/{record}/submit', 'OperationController@submit')->name('operation.submit');
    Route::post('operation/{record}/submitSave', 'OperationController@submitSave')->name('operation.submitSave');
    Route::post('operation/{record}/approve', 'OperationController@approve')->name('operation.approve');
    Route::post('operation/{record}/reject', 'OperationController@reject')->name('operation.reject');
    Route::post('operation/{record}/upgrade', 'OperationController@upgrade')->name('operation.upgrade');
    Route::get('operation/{record}/summary', 'OperationController@summary')->name('operation.summary');
    Route::post('operation/{record}/summaryGrid', 'OperationController@summaryGrid')->name('operation.summaryGrid');
    Route::get('operation/{record}/summaryCreate', 'OperationController@summaryCreate')->name('operation.summaryCreate');
    Route::post('operation/{record}/summaryStore', 'OperationController@summaryStore')->name('operation.summaryStore');
    Route::get('operation/{summary}/summaryShow', 'OperationController@summaryShow')->name('operation.summaryShow');
    Route::get('operation/{summary}/summaryEdit', 'OperationController@summaryEdit')->name('operation.summaryEdit');
    Route::patch('operation/{summary}/summaryUpdate', 'OperationController@summaryUpdate')->name('operation.summaryUpdate');
    Route::delete('operation/{summary}/summaryDestroy', 'OperationController@summaryDestroy')->name('operation.summaryDestroy');
    Route::get('operation/{revisi}/printRevisi', 'OperationController@printRevisi')->name('operation.printRevisi');
    Route::grid('operation', 'OperationController', ['with' => ['print', 'history', 'tracking']]);
});


Route::namespace('DocumentRencana')
    ->group(
        function () {
            // Dokumen Rencana
            Route::get('document-rencana/{record}/create', 'DocumentController@create')->name('document-rencana.createNew');
            Route::post('document-rencana/{record}/store', 'DocumentController@store')->name('document-rencana.storeNew');
            Route::post('document-rencana/{record}/upgrade', 'DocumentController@upgrade')->name('document-rencana.upgrade');
            Route::grid('document-rencana', 'DocumentController', [
                'with' => ['print', 'history', 'tracking', 'submit', 'approval'],
            ]);
        }
    );


Route::namespace('DocumentRencana')
    ->group(
        function () {
            // Dokumen Rencana
            Route::get('document-rencana/{record}/create', 'DocumentController@create')->name('document-rencana.createNew');
            Route::post('document-rencana/{record}/store', 'DocumentController@store')->name('document-rencana.storeNew');
            Route::post('document-rencana/{record}/upgrade', 'DocumentController@upgrade')->name('document-rencana.upgrade');
            Route::grid('document-rencana', 'DocumentController', [
                'with' => ['print', 'history', 'tracking', 'submit', 'approval'],
            ]);
        }
    );

