<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Globals\RevisiFiles;
use App\Models\Investigasi\PemeriksaanPelanggaran;
use App\Models\Investigasi\SuratPemanggilanInvestigasi;
use App\Models\Investigasi\SuratTugasInvestigasi;
use App\Models\Rkia\Summary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportPenilaianKinerjaController extends Controller
{
    protected $module   = 'report.penilaian-kinerja';
    protected $routes   = 'report.penilaian-kinerja';
    protected $views    = 'report.penilaian-kinerja';
    protected $perms    = 'report';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Penilaian Kinerja',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Penilaian Kinerja' => route($this->routes . '.index'),
                ],
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.penilaian_kinerja.summary')
            ->where('module', 'penilaian-kinerja')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'category',
                function ($record) use ($user) {
                    return $record->target->penilaian_kinerja->summary->type->show_name;
                }
            )
            ->addColumn(
                'year',
                function ($record) use ($user) {
                    return $record->target->penilaian_kinerja->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->target->penilaian_kinerja->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) use ($user) {
                    return $record->target->penilaian_kinerja->summary->type->show_name . "<br>" . $record->target->penilaian_kinerja->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) use ($user) {
                    return ($record->target->penilaian_kinerja->summary->lha ? $record->target->penilaian_kinerja->summary->lha->no_memo : '') . '<br>' . ($record->target->penilaian_kinerja->summary->lha ? $record->target->penilaian_kinerja->summary->lha->date_memo->format('d M Y') : '');
                }
            )
            ->addColumn(
                'auditor',
                function ($record) use ($user) {
                    return $record->target->auditor->name;
                }
            )
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
}
