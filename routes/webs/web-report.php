<?php

use App\Models\Globals\RevisiFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Report
Route::namespace('Report')
    ->name('report.')
    ->prefix('report')
    ->group(
        function () {
            Route::get(
                'print/{id}/{title}',
                function (Request $request, $id) {
                    if (!$request->hasValidSignature()) {
                        throw new NotFoundHttpException;
                    }
                    $report = RevisiFiles::findOrFail($id);
                    return response()->file(storage_path('app/' . $report->file_path));
                }
            )
                ->name('print');

            // Risk Assessment
            Route::get('risk-assessment', 'ReportRiskAssessmentController@index')->name('risk-assessment.index');
            Route::post('risk-assessment/grid', 'ReportRiskAssessmentController@grid')->name('risk-assessment.grid');

            Route::get('risk-assessment/register', 'ReportRiskAssessmentController@register')->name('risk-assessment.register');
            Route::post('risk-assessment/register/grid', 'ReportRiskAssessmentController@registerGrid')->name('risk-assessment.register-grid');

            Route::get('risk-assessment/inherent', 'ReportRiskAssessmentController@inherent')->name('risk-assessment.inherent');
            Route::post('risk-assessment/inherent/grid', 'ReportRiskAssessmentController@inherentGrid')->name('risk-assessment.inherent-grid');

            Route::get('risk-assessment/residual', 'ReportRiskAssessmentController@residual')->name('risk-assessment.residual');
            Route::post('risk-assessment/residual/grid', 'ReportRiskAssessmentController@residualGrid')->name('risk-assessment.residual-grid');

            Route::get('risk-assessment/mapping', 'ReportRiskAssessmentController@mapping')->name('risk-assessment.mapping');
            Route::post('risk-assessment/mapping/grid', 'ReportRiskAssessmentController@mappingGrid')->name('risk-assessment.mapping-grid');

            Route::get('risk-assessment/rating', 'ReportRiskAssessmentController@rating')->name('risk-assessment.rating');
            Route::post('risk-assessment/rating/grid', 'ReportRiskAssessmentController@ratingGrid')->name('risk-assessment.rating-grid');

            // Program Kerja
            Route::get('program-kerja', 'ReportProgramKerjaController@index')->name('program-kerja.index');
            Route::post('program-kerja/grid', 'ReportProgramKerjaController@grid')->name('program-kerja.grid');

            Route::get('program-kerja/schedule', 'ReportProgramKerjaController@schedule')->name('program-kerja.schedule');
            Route::post('program-kerja/schedule/grid', 'ReportProgramKerjaController@scheduleGrid')->name('program-kerja.schedule-grid');

            Route::get('program-kerja/cost-plan', 'ReportProgramKerjaController@costPlan')->name('program-kerja.cost-plan');
            Route::post('program-kerja/cost-plan/grid', 'ReportProgramKerjaController@costPlanGrid')->name('program-kerja.cost-plan-grid');

            Route::get('program-kerja/plan-document', 'ReportProgramKerjaController@planDocument')->name('program-kerja.plan-document');
            Route::post('program-kerja/plan-document/grid', 'ReportProgramKerjaController@planDocumentGrid')->name('program-kerja.plan-document-grid');

            Route::get('program-kerja/stage', 'ReportProgramKerjaController@stage')->name('program-kerja.stage');
            Route::post('program-kerja/stage/grid', 'ReportProgramKerjaController@stageGrid')->name('program-kerja.stage-grid');

            // Persiapan Audit
            Route::get('persiapan-audit', 'ReportPersiapanAuditController@index')->name('persiapan-audit.index');
            Route::post('persiapan-audit/grid', 'ReportPersiapanAuditController@grid')->name('persiapan-audit.grid');

            Route::get('persiapan-audit/assignment', 'ReportPersiapanAuditController@assignment')->name('persiapan-audit.assignment');
            Route::post('persiapan-audit/assignment/grid', 'ReportPersiapanAuditController@assignmentGrid')->name('persiapan-audit.assignment-grid');

            Route::get('persiapan-audit/instruction', 'ReportPersiapanAuditController@instruction')->name('persiapan-audit.instruction');
            Route::post('persiapan-audit/instruction/grid', 'ReportPersiapanAuditController@instructionGrid')->name('persiapan-audit.instruction-grid');

            Route::get('persiapan-audit/apm', 'ReportPersiapanAuditController@apm')->name('persiapan-audit.apm');
            Route::post('persiapan-audit/apm/grid', 'ReportPersiapanAuditController@apmGrid')->name('persiapan-audit.apm-grid');

            Route::get('persiapan-audit/fee', 'ReportPersiapanAuditController@fee')->name('persiapan-audit.fee');
            Route::post('persiapan-audit/fee/grid', 'ReportPersiapanAuditController@feeGrid')->name('persiapan-audit.fee-grid');

            // Pelaksanaan Audit
            Route::get('pelaksanaan-audit', 'ReportPelaksanaanAuditController@index')->name('pelaksanaan-audit.index');
            Route::post('pelaksanaan-audit/grid', 'ReportPelaksanaanAuditController@grid')->name('pelaksanaan-audit.grid');

            Route::get('pelaksanaan-audit/memo-opening', 'ReportPelaksanaanAuditController@memoOpening')->name('pelaksanaan-audit.memo-opening');
            Route::post('pelaksanaan-audit/memo-opening/grid', 'ReportPelaksanaanAuditController@memoOpeningGrid')->name('pelaksanaan-audit.memo-opening-grid');

            Route::get('pelaksanaan-audit/opening', 'ReportPelaksanaanAuditController@opening')->name('pelaksanaan-audit.opening');
            Route::post('pelaksanaan-audit/opening/grid', 'ReportPelaksanaanAuditController@openingGrid')->name('pelaksanaan-audit.opening-grid');

            Route::get('pelaksanaan-audit/doc-req', 'ReportPelaksanaanAuditController@docReq')->name('pelaksanaan-audit.doc-req');
            Route::post('pelaksanaan-audit/doc-req/grid', 'ReportPelaksanaanAuditController@docReqGrid')->name('pelaksanaan-audit.doc-req-grid');

            Route::get('pelaksanaan-audit/doc-full', 'ReportPelaksanaanAuditController@docFull')->name('pelaksanaan-audit.doc-full');
            Route::post('pelaksanaan-audit/doc-full/grid', 'ReportPelaksanaanAuditController@docFullGrid')->name('pelaksanaan-audit.doc-full-grid');

            Route::get('pelaksanaan-audit/sample', 'ReportPelaksanaanAuditController@sample')->name('pelaksanaan-audit.sample');
            Route::post('pelaksanaan-audit/sample/grid', 'ReportPelaksanaanAuditController@sampleGrid')->name('pelaksanaan-audit.sample-grid');

            Route::get('pelaksanaan-audit/feedback', 'ReportPelaksanaanAuditController@feedback')->name('pelaksanaan-audit.feedback');
            Route::post('pelaksanaan-audit/feedback/grid', 'ReportPelaksanaanAuditController@feedbackGrid')->name('pelaksanaan-audit.feedback-grid');

            Route::get('pelaksanaan-audit/worksheet', 'ReportPelaksanaanAuditController@worksheet')->name('pelaksanaan-audit.worksheet');
            Route::post('pelaksanaan-audit/worksheet/grid', 'ReportPelaksanaanAuditController@worksheetGrid')->name('pelaksanaan-audit.worksheet-grid');

            Route::get('pelaksanaan-audit/commitment', 'ReportPelaksanaanAuditController@commitment')->name('pelaksanaan-audit.commitment');
            Route::post('pelaksanaan-audit/commitment/grid', 'ReportPelaksanaanAuditController@commitmentGrid')->name('pelaksanaan-audit.commitment-grid');

            Route::get('pelaksanaan-audit/memo-closing', 'ReportPelaksanaanAuditController@memoClosing')->name('pelaksanaan-audit.memo-closing');
            Route::post('pelaksanaan-audit/memo-closing/grid', 'ReportPelaksanaanAuditController@memoClosingGrid')->name('pelaksanaan-audit.memo-closing-grid');

            Route::get('pelaksanaan-audit/closing', 'ReportPelaksanaanAuditController@closing')->name('pelaksanaan-audit.closing');
            Route::post('pelaksanaan-audit/closing/grid', 'ReportPelaksanaanAuditController@closingGrid')->name('pelaksanaan-audit.closing-grid');

            // Pelaporan Audit
            Route::get('pelaporan-audit', 'ReportPelaporanAuditController@index')->name('pelaporan-audit.index');
            Route::post('pelaporan-audit/grid', 'ReportPelaporanAuditController@grid')->name('pelaporan-audit.grid');

            Route::get('pelaporan-audit/lhp', 'ReportPelaporanAuditController@lhp')->name('pelaporan-audit.lhp');
            Route::post('pelaporan-audit/lhp/grid', 'ReportPelaporanAuditController@lhpGrid')->name('pelaporan-audit.lhp-grid');

            // Followup
            Route::get('followup', 'ReportFollowupController@index')->name('followup.index');
            Route::post('followup/grid', 'ReportFollowupController@grid')->name('followup.grid');

            Route::get('followup/memo', 'ReportFollowupController@memo')->name('followup.memo');
            Route::post('followup/memo-grid', 'ReportFollowupController@memoGrid')->name('followup.memo-grid');

            Route::get('followup/reschedule', 'ReportFollowupController@reschedule')->name('followup.reschedule');
            Route::post('followup/reschedule-grid', 'ReportFollowupController@rescheduleGrid')->name('followup.reschedule-grid');

            Route::get('followup/monitor', 'ReportFollowupController@monitor')->name('followup.monitor');
            Route::post('followup/monitor-grid', 'ReportFollowupController@monitorGrid')->name('followup.monitor-grid');

            Route::get('followup/review', 'ReportFollowupController@review')->name('followup.review');
            Route::post('followup/review-grid', 'ReportFollowupController@reviewGrid')->name('followup.review-grid');

            Route::get('followup/minutes', 'ReportFollowupController@minutes')->name('followup.minutes');
            Route::post('followup/minutes-grid', 'ReportFollowupController@minutesGrid')->name('followup.minutes-grid');
        }
    );
