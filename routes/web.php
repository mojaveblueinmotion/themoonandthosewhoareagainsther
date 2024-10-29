<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Followup\FollowupMonitor;
use App\Models\Followup\FollowupRegItem;
use App\Models\Master\Org\OrgStruct;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/home');
Route::get('lang/change', [Controller::class, 'change'])->name('changeLang');
Auth::routes();
Route::get('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get(
        'auth/check',
        function () {
            return response()->json(
                [
                    'data'  => auth()->check()
                ]
            );
        }
    );
    Route::namespace('Dashboard')
        ->group(
            function () {
                Route::get('home', 'DashboardController@index')->name('home');
                Route::post('progress', 'DashboardController@progress')->name('dashboard.progress');
                Route::post('chartFinding', 'DashboardController@chartFinding')->name('dashboard.chartFinding');
                Route::post('chartFollowup', 'DashboardController@chartFollowup')->name('dashboard.chartFollowup');
                Route::post('chartStage', 'DashboardController@chartStage')->name('dashboard.chartStage');
                 // Login
                 Route::post('chartLogin', 'DashboardController@chartLogin')->name('dashboard.chartLogin');
                 Route::post('chartLoginMonthly', 'DashboardController@chartLoginMonthly')->name('dashboard.chartLoginMonthly');

                Route::get('language/{lang}/setLang', 'DashboardController@setLang')->name('setLang');
            }
        );

    // monitoring
    Route::namespace('Monitoring')
        ->group(
            function () {
                Route::grid(
                    'monitoring',
                    'MonitoringController',
                    [
                        'with' => ['excel', 'history', 'tracking', 'submit', 'approval'],
                        'except' => ['create', 'store']
                    ]
                );

                Route::post('monitoring-temuan/{memo}/detailGrid', 'MonitoringTemuanController@detailGrid')->name('monitoring-temuan.detailGrid');
                Route::get('monitoring-temuan/{detail}/detailShow', 'MonitoringTemuanController@detailShow')->name('monitoring-temuan.detailShow');
                Route::grid(
                    'monitoring-temuan',
                    'MonitoringTemuanController',
                    [
                        'with' => ['excel', 'history', 'tracking', 'submit', 'approval'],
                        'except' => ['create', 'store']
                    ]
                );
            }
        );

    // Ajax
    Route::prefix('ajax')
        ->name('ajax.')
        ->group(
            function () {
                Route::post('saveTempFiles', 'AjaxController@saveTempFiles')->name('saveTempFiles');
                Route::get('testNotification/{emails}', 'AjaxController@testNotification')->name('testNotification');
                Route::post('userNotification', 'AjaxController@userNotification')->name('userNotification');
                Route::get('userNotification/{notification}/read', 'AjaxController@userNotificationRead')->name('userNotificationRead');
                // Ajax Modules
                Route::get('city-options', 'AjaxController@cityOptions')->name('cityOptions');
                Route::post('penilaian-category', 'AjaxController@penilaianCategoryOptions')->name('penilaianCategoryOptions');
                Route::post('city-options-root', 'AjaxController@cityOptionsRoot')->name('cityOptionsRoot');
                Route::get('jabatan-options', 'AjaxController@jabatanOptions')->name('jabatan-options');
                Route::get('jabatan-options-with-nonpkpt', 'AjaxController@jabatanWithNonPKPTOptions')->name('jabatan-options-with-nonpkpt');
                Route::post('{search}/provinceOptions', 'AjaxController@provinceOptionsBySearch')->name('provinceOptionsBySearch');
                Route::post('selectObject', 'AjaxController@selectObject')->name('selectObject');
                Route::post('{search}/selectRole', 'AjaxController@selectRole')->name('selectRole');
                Route::post('{search}/selectStruct', 'AjaxController@selectStruct')->name('selectStruct');
                Route::get('child-struct-options', 'AjaxController@childStructOptions')->name('child-struct-options');
                Route::post('{search}/selectPosition', 'AjaxController@selectPosition')->name('selectPosition');
                Route::post('{search}/selectUser', 'AjaxController@selectUser')->name('selectUser');
                Route::post('{search}/selectCity', 'AjaxController@selectCity')->name('selectCity');
                Route::post('{search}/selectProvince', 'AjaxController@selectProvince')->name('selectProvince');
                Route::post('selectProcedure', 'AjaxController@selectProcedure')->name('selectProcedure');
                Route::post('selectProcedureLangkahKerja', 'AjaxController@selectProcedureLangkahKerja')->name('selectProcedureLangkahKerja');
                Route::post('{search}/selectTypeAudit', 'AjaxController@selectTypeAudit')->name('selectTypeAudit');
                Route::post('selectMainProcess', 'AjaxController@selectMainProcess')->name('selectMainProcess');

                Route::post('getAuditRating', 'AjaxController@getAuditRating')->name('getAuditRating');
                Route::get('get-survey-statement', 'AjaxController@getSurveyStatement')->name('getSurveyStatement');
                Route::post('{search}/selectDokumen', 'AjaxController@selectDokumen')->name('selectDokumen');
                Route::post('{search}/selectAspect', 'AjaxController@selectAspect')->name('selectAspect');
                Route::post('selectCriteria', 'AjaxController@selectCriteria')->name('selectCriteria');
                Route::post('{search}/selectAuditReference', 'AjaxController@selectAuditReference')->name('selectAuditReference');
                Route::post('{search}/selectDocItem', 'AjaxController@selectDocItem')->name('selectDocItem');
                Route::post('{search}/selectLevelPosition', 'AjaxController@selectLevelPosition')->name('selectLevelPosition');
                Route::post('{search}/selectBankAccount', 'AjaxController@selectBankAccount')->name('selectBankAccount');
                Route::post('{search}/selectLevelDampak', 'AjaxController@selectLevelDampak')->name('selectLevelDampak');
                Route::post('{search}/selectLevelKemungkinan', 'AjaxController@selectLevelKemungkinan')->name('selectLevelKemungkinan');
                Route::post('{search}/selectStatusResiko', 'AjaxController@selectStatusResiko')->name('selectStatusResiko');
                Route::post('{search}/selectDetailApm', 'AjaxController@selectDetailApm')->name('selectDetailApm');
                Route::post('all/selectDetailApmByAspect', 'AjaxController@selectDetailApm2')->name('selectDetailApm2');
                Route::post('{search}/selectTrainingInstitute', 'AjaxController@selectTrainingInstitute')->name('selectTrainingInstitute');
                Route::post('{search}/selectTrainingType', 'AjaxController@selectTrainingType')->name('selectTrainingType');
                Route::post('{search}/selectServiceProvider', 'AjaxController@selectServiceProvider')->name('selectServiceProvider');
                Route::post('{search}/selectKategoriLangkahKerja', 'AjaxController@selectKategoriLangkahKerja')->name('selectKategoriLangkahKerja');
                Route::post('{search}/selectMemoDocument', 'AjaxController@selectMemoDocument')->name('selectMemoDocument');

                Route::post('{search}/selectJenisBiaya', 'AjaxController@selectJenisBiaya')->name('selectJenisBiaya');
                Route::post('{search}/selectAktiva', 'AjaxController@selectAktiva')->name('selectAktiva');
                Route::post('{search}/selectKomponenBiaya', 'AjaxController@selectKomponenBiaya')->name('selectKomponenBiaya');

                Route::post('selectEvidence', 'AjaxController@selectEvidence')->name('selectEvidence');
                Route::post('selectQualification', 'AjaxController@selectQualification')->name('selectQualification');
                Route::post('selectUrgency', 'AjaxController@selectUrgency')->name('selectUrgency');

                Route::post('selectRiskRating', 'AjaxController@selectRiskRating')->name('selectRiskRating');
                Route::post('selectRiskAssessmentRating', 'AjaxController@selectRiskAssessmentRating')->name('selectRiskAssessmentRating');
                Route::get('unit-kerja', 'AjaxController@unitKerja')->name('unitKerja');
                Route::get('getTingkatResiko', 'AjaxController@getTingkatResiko')->name('getTingkatResiko');
                Route::get('getCheckUniqueNoKka', 'AjaxController@getCheckUniqueNoKka')->name('getCheckUniqueNoKka');

                Route::get('getStruct', 'AjaxController@getStruct')->name('getStruct');
                Route::post('{search}/selectObjective', 'AjaxController@selectObjective')->name('selectObjective');
                Route::post('{search}/selectDepartmentAuditee', 'AjaxController@selectDepartmentAuditee')->name('selectDepartmentAuditee');

                // Pembukuan
                Route::post('{search}/selectLapak', 'AjaxController@selectLapak')->name('selectLapak');
                Route::post('{search}/selectPembayaran', 'AjaxController@selectPembayaran')->name('selectPembayaran');
                Route::post('{search}/selectKendaraan', 'AjaxController@selectKendaraan')->name('selectKendaraan');
                
                Route::get('getTotalPembukuan', 'AjaxController@getTotalPembukuan')->name('getTotalPembukuan');
                Route::get('getTotalPembukuanSam', 'AjaxController@getTotalPembukuanSam')->name('getTotalPembukuanSam');
                Route::get('getTotalKas', 'AjaxController@getTotalKas')->name('getTotalKas');
            }
        );

    // Setting
    Route::namespace('Setting')
        ->prefix('setting')
        ->name('setting.')
        ->group(
            function () {
                Route::namespace('Role')
                    ->group(
                        function () {
                            Route::get('role/{record}/permit', 'RoleController@permit')->name('role.permit');
                            Route::patch('role/{record}/grant', 'RoleController@grant')->name('role.grant');
                            Route::grid('role', 'RoleController');
                        }
                    );
                Route::namespace('Flow')
                    ->group(
                        function () {
                            Route::grid('flow', 'FlowController', ['with' => ['history']]);
                        }
                    );
                Route::namespace('User')
                    ->group(
                        function () {
                            Route::post('user/{record}/resetPassword', 'UserController@resetPassword')->name('user.resetPassword');
                            Route::grid('user', 'UserController');
                            Route::get('user/{record}/detail', 'UserController@detail')->name('user.detail');

                            Route::post('user-service-provider/{record}/resetPassword', 'UserServiceProviderController@resetPassword')
                                ->name('user-service-provider.resetPassword');
                            Route::grid('user-service-provider', 'UserServiceProviderController');

                            // Pendidikan
                            Route::get('user/{record}/pendidikan', 'UserController@pendidikan')->name('user.pendidikan');
                            Route::get('user/{record}/pendidikanDetailCreate', 'UserController@pendidikanDetailCreate')->name('user.pendidikan.detailCreate');
                            Route::post('user/{id}/pendidikanDetailStore', 'UserController@pendidikanDetailStore')->name('user.pendidikan.detailStore');
                            Route::get('user/{id}/pendidikanDetailShow', 'UserController@pendidikanDetailShow')->name('user.pendidikan.detailShow');
                            Route::get('user/{id}/pendidikanDetailEdit', 'UserController@pendidikanDetailEdit')->name('user.pendidikan.detailEdit');
                            Route::post('user/{id}/pendidikanDetailUpdate', 'UserController@pendidikanDetailUpdate')->name('user.pendidikan.detailUpdate');
                            Route::delete('user/{id}/pendidikanDetailDestroy', 'UserController@pendidikanDetailDestroy')->name('user.pendidikan.detailDestroy');
                            Route::post('user/{record}/pendidikanGrid', 'UserController@pendidikanGrid')->name('user.pendidikan.grid');

                            Route::get('profile', 'ProfileController@index')->name('profile.index');
                            Route::post('profile', 'ProfileController@updateProfile')->name('profile.updateProfile');
                            Route::get('profile/notification', 'ProfileController@notification')->name('profile.notification');
                            Route::post('profile/gridNotification', 'ProfileController@gridNotification')->name('profile.gridNotification');
                            Route::get('profile/activity', 'ProfileController@activity')->name('profile.activity');
                            Route::post('profile/gridActivity', 'ProfileController@gridActivity')->name('profile.gridActivity');
                            Route::get('profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
                            Route::post('profile/changePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
                            Route::get('profile/getModulesSecondary', 'ProfileController@getModulesSecondary')->name('profile.getModulesSecondary');
                        }
                    );

                Route::namespace('Activity')
                    ->group(
                        function () {
                            Route::get('activity/export', 'ActivityController@export')->name('activity.export');
                            Route::grid('activity', 'ActivityController');
                        }
                    );
                // Route::namespace('Reset')->group(function () {
                //     Route::get('reset-data', 'ResetController@index')->name('reset');
                //     Route::post('reset-data', 'ResetController@reset')->name('reset');
                // });
            }
        );

    // Master
    Route::namespace('Master')
        ->prefix('master')
        ->name('master.')
        ->group(
            function () {
                Route::namespace('Org')
                    ->prefix('org')
                    ->name('org.')
                    ->group(
                        function () {
                            Route::grid('root', 'RootController');
                            Route::grid('boc', 'BocController');
                            Route::grid('bod', 'BodController');
                            Route::get('subsidiary/import', 'SubsidiaryController@import')->name('subsidiary.import');
                            Route::post('subsidiary/importSave', 'SubsidiaryController@importSave')->name('subsidiary.importSave');
                            Route::grid('subsidiary', 'SubsidiaryController');
                            Route::grid('department', 'DepartmentController');
                            Route::grid('division', 'DivisionController');
                            Route::grid('subdivision', 'SubdivisionController');
                            Route::grid('level-position', 'LevelPositionController');
                            Route::grid('position', 'PositionController');
                            Route::grid('department-auditee', 'DepartmentAuditeeController');

                        }
                    );
                Route::namespace('Geografis')
                    ->prefix('geografis')
                    ->name('geografis.')
                    ->group(
                        function () {
                            Route::grid('province', 'ProvinceController');
                            Route::grid('city', 'CityController');
                            Route::grid('district', 'DistrictController');
                        }
                    );

                Route::namespace('Pembukuan')->group(function () {
                    Route::grid('lapak', 'LapakController');
                    Route::grid('kendaraan', 'KendaraanController');
                    Route::grid('pembayaran', 'PembayaranController');
                });
            }
        );

    // Web Transaction Modules
    foreach (FacadesFile::allFiles(__DIR__ . '/webs') as $file) {
        require $file->getPathname();
    }
});

Route::get(
    'dev/json',
    function () {
        return [
            url('login'),
            yurl('login'),
            rut('login'),
            rut('login'),
        ];
    }
);