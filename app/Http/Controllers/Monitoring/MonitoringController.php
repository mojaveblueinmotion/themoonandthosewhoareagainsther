<?php

namespace App\Http\Controllers\Monitoring;

use App\Exports\Monitoring\MonitoringExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Preparation\Assignment\AssignmentRequest;
use App\Models\Followup\FollowupMonitor;
use App\Models\Followup\FollowupReschedule;
use App\Models\Followup\MemoTindakLanjut;
use App\Models\Master\Letter\Letter;
use App\Models\Preparation\Assignment\Assignment;
use App\Models\Rkia\Summary;
use App\Support\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MonitoringController extends Controller
{
    protected $module = 'monitoring';
    protected $routes = 'monitoring';
    protected $views = 'monitoring';
    protected $perms = 'monitoring';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Monitoring Aktivitas',
            'breadcrumb' => [
                'Monitoring Aktivitas' => rut($this->routes . '.index'),
            ],
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:category|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:progres|label:Progres|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index', compact('user'));
    }

    public function grid()
    {
        $user = auth()->user();
        $is_auditor     = $user->position?->imAuditor();
        $is_presdir     = $user->position?->location->type == 1;
        $is_financedir  = $user->position?->location->type == 2;
        $is_boc = $user->position?->location->level == 'boc';
        $is_bod = $user->position?->location->level == 'bod';
        $records = Summary::filters()
            ->when(
                !($is_auditor || $is_financedir || $is_presdir || $is_boc),
                function ($q) use ($user) {
                    $q->whereHas(
                        'departmentAuditee.departments',
                        function ($q) use ($user) {
                            $q->whereIn('department_id', $user->position->location->getIdsWithChild());
                        }
                    );
                }
            )
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($summary) {
                return request()->start;
            })
            ->addColumn('category', function ($summary) {
                return $summary->type->show_name . '<br>' . $summary->subject->name;
            })
            ->addColumn('auditee', function ($summary) {
                return $summary->departmentAuditee->getDepartments();
            })
            ->addColumn('year', function ($summary) {
                return $summary->rkia->year;
            })
            ->addColumn('month', function ($summary) {
                if ($summary->assignment) {
                    return $summary->getDateRaw();
                }
                return $summary->getMonthPlanRaw();
            })
            ->addColumn('progres', function ($summary) {
                $completed = 0;
                $total = 0;

                $total += 1;
                if (($summary->rkia->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->assignment->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->apm->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->memoOpening->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->opening->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                foreach ($summary->samples as $sample) {
                    $total += 1;
                    if ($sample->status == 'completed') {
                        $completed += 1;
                    }
                    $total += 1;
                    if (($sample->reviewSample->status ?? 'new') == 'completed') {
                        $completed += 1;
                    }
                    foreach ($sample->details as $detail) {
                        $total += 1;
                        if (($detail->feedback->status ?? 'new') == 'completed') {
                            $completed += 1;
                        }
                        $total += 1;
                        if (($detail->worksheet->status ?? 'new') == 'completed') {
                            $completed += 1;
                        }
                        $total += 1;
                        if (($detail->komitmen->status ?? 'new') == 'completed') {
                            $completed += 1;
                        }
                        $total += 1;
                        if (($detail->regItem->monitor->status ?? 'new') == 'completed') {
                            $completed += 1;
                        }
                        $total += 1;
                        if (($detail->regItem->monitor->reviewMonitoring->status ?? 'new') == 'completed') {
                            $completed += 1;
                        }
                    }
                }
                $total += 1;
                if (($summary->memoClosing->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->closing->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $total += 1;
                if (($summary->lha->status ?? 'new') == 'completed') {
                    $completed += 1;
                }
                $followupMemos = Summary::select('id')->withWhereHas('followupMemos', function ($q) {
                    $q->select('summary_id', 'status');
                })->get();
                $total += $followupMemos->count() > 0 ? $followupMemos->count() : 1;
                foreach ($followupMemos as $memo) {
                    if ($memo->status == 'completed') {
                        $completed += 1;
                    }
                }

                $followupMonitor = FollowupMonitor::whereRelation('regItem.reg', 'summary_id', $summary->id)
                    ->where('status', 'completed')
                    ->count();
                if ($followupMonitor > 0) {
                    $completed += 1;
                }
                if (number_format($completed / $total * 100, 2) == 100) {
                    return $completed . ' / ' . $total . ' (100%)';
                } else {
                    return $completed . ' / ' . $total . ' (' . (number_format($completed / $total * 100, 2)) . '%)';
                }
            })
            // ->addColumn('durasi', function ($summary) use ($user) {
            //     $firstDate = $summary->rkia->created_at ?? $summary->instruction()->first()->created_at;
            //     $endDate = $summary->followupMonitor()->first()->updated_at ?? $summary->followupReg->reschedule->updated_at ?? $summary->followupMemoTindakLanjut()->first()->updated_at ?? $summary->dirNote()->first()->updated_at ?? $summary->lha()->first()->updated_at ?? $summary->memoLhp()->first()->updated_at ?? $summary->exiting()->first()->updated_at ?? $summary->memoExiting()->first()->updated_at ?? $summary->closing()->first()->updated_at ?? $summary->memoClosing()->first()->updated_at ?? $summary->samples()->first()->worksheet->updated_at ?? $summary->samples()->first()->feedback->updated_at ?? $summary->samples()->first()->reviewSample->updated_at ?? $summary->samples()->first()->updated_at ?? $summary->documents()->first()->docFull->updated_at ?? $summary->documents()->first()->updated_at ?? $summary->opening()->first()->updated_at ?? $summary->memoOpening()->first()->updated_at ?? $summary->fee()->first()->updated_at ?? $summary->apm()->first()->updated_at ?? $summary->instruction()->first()->updated_at ?? $summary->assignment()->first()->updated_at ?? $summary->rkia->document->updated_at ?? $summary->rkia->rencanaBiaya->updated_at ?? $summary->rkia->updated_at;

            //     $start = Carbon::parse($firstDate);
            //     $end = Carbon::parse($endDate);

            //     // Calculate the difference between the two dates
            //     $diff = $end->diff($start);
            //     return $diff->d .' Hari '. $diff->h .' Jam ' . $diff->i.' Menit';
            // })
            ->addColumn('letter_no', function ($summary) use ($user) {
                if ($summary->rkia && !$summary->assignment) {
                    return '-';
                }
                return  $summary->getLetterNo()  . "<br>" . $summary->getDate();
            })
            ->addColumn('auditor', function ($summary) use ($user) {
                if ($summary->assignment) {
                    return $summary->getAuditorRaw();
                }
                return $summary->getAuditorPlanRaw();
            })
            ->addColumn('status', function ($summary) use ($user) {
                // return $summary->labelStatus($summary->assignment->status ?? 'new');
                return $summary->show_status;
            })
            ->addColumn('updated_by', function ($summary) use ($user) {
                if ($summary->assignment) {
                    return $summary->assignment->createdByRaw();
                }
                return '';
            })
            ->addColumn('action', function ($summary) use ($user) {
                $actions = [];
                $actions[] = 'type:show|page:true';
                // $actions[] = 'type:excel';

                return $this->makeButtonDropdown($actions, $summary->id);
            })
            ->rawColumns(
                [
                    'action',
                    'updated_by',
                    'auditee',
                    'status',
                    'month',
                    'auditor',
                    'object_type',
                    'category',
                    'year',
                    'letter_no',
                    'object_id'
                ]
            )
            ->make(true);
    }

    public function create(Summary $summary)
    {
        // $letter = Letter::generateForAssignment($summary);
        return $this->render($this->views . '.create', compact('summary'));
    }

    public function store(AssignmentRequest $request, Summary $summary)
    {
        $record = Assignment::firstOrNew(['summary_id' => $summary->id]);
        return $record->handleStoreOrUpdate($request);
    }

    public function show($id)
    {
        $summary = Summary::findOrFail($id);
        // dd($summary);
        return $this->render($this->views . '.show', compact('summary'));
    }

    public function edit(Assignment $record)
    {
        $summary = $record->summary;
        return $this->render($this->views . '.edit', compact('record', 'summary'));
    }

    public function update(AssignmentRequest $request, Assignment $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function submit(Assignment $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(Assignment $record, Request $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(Assignment $record)
    {
        $summary = $record->summary;
        return $this->render($this->views . '.approval', compact('record', 'summary'));
    }

    public function reject(Assignment $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(Assignment $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function history(Assignment $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(Assignment $record)
    {
        $this->prepare(['title' => 'Tracking Approval']);
        return $this->render('globals.tracking', compact('record'));
    }

    public function excel($id)
    {
        $summary = Summary::with('assignment')->findOrFail($id);
        $record = $summary->assignment;
        $title = $this->prepared('title') . ' ' . $record->summary->rkia->year;
        $module = $this->prepared('module');
        $summary = $record->summary;
        $fileName = 'Laporan Monitoring ' . $record->summary->rkia->year . '.xlsx';
        return \Excel::download(new MonitoringExport($record), $fileName);
    }

    public function print(Assignment $record)
    {
        $TITLE = $this->prepared('title');
        $title = $this->prepared('title') . ' ' . $record->year;
        $module = $this->prepared('module');
        $summary = $record->summary;
        $pdf = \PDF::loadView($this->views . '.print', compact('title', 'TITLE', 'module', 'record', 'summary'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream(date('Y-m-d') . ' ' . $title . '.pdf');
    }
}
