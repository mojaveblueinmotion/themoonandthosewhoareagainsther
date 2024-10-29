<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Conducting\Kka\KkaSampleDetail;
use App\Support\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MonitoringTemuanController extends Controller
{
    protected $module = 'monitoring-temuan';
    protected $routes = 'monitoring-temuan';
    protected $views = 'monitoring-temuan';
    protected $perms = 'monitoring-temuan';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Monitoring Temuan',
            'breadcrumb' => [
                'Monitoring Temuan' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:temuan|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:tindak|label:Tindak Lanjut|className:text-center'),
                        $this->makeColumn('name:status|label:Status|className:text-center'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index', compact('user'));
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = [];
        if (!$user->hasRole('Administrator')) {
            $is_auditor = $user->position?->imAuditor();
            $is_presdir = $user->position?->location->type == 1;
            $is_financedir  = $user->position?->location->type == 2;
            $is_boc = $user->position?->location->level == 'boc';
            $is_bod = $user->position?->location->level == 'bod';
            $records = KkaSampleDetail::whereHas('sample', function ($q) {
                $q->where('status', 'completed')
                    ->whereHas('summary', function ($q) {
                        $q->filters();
                    });
            })
                ->filters()
                ->finding()
                ->temuan()
                ->when(
                    !($is_auditor || $is_financedir || $is_presdir || $is_boc),
                    function ($q) use ($user) {
                        $q->whereIn('struct_id', $user->position->location->getIdsWithChild());
                    }
                )
                ->when(
                    $status = $request->status,
                    function ($q) use ($status) {
                        $q->whereHas('regItem', function ($q) use ($status) {
                            if ($status == 'open') {
                                $q->whereNull('completion_date')
                                    ->orWhereRelation('monitor.reviewMonitoring', 'status', '!=', 'completed');
                            } elseif ($status == 'ontime') {
                                $q->whereRaw('deadline >= completion_date')
                                    ->whereRelation('monitor.reviewMonitoring', 'status', 'completed');
                            } elseif ($status == 'overdue') {
                                $q->whereRaw('completion_date > deadline')
                                    ->whereRelation('monitor.reviewMonitoring', 'status', 'completed');
                            }
                        });
                    }
                )
                ->dtGet();
        }

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                // dd($record->sample->summary->rkia->year);
                return request()->start;
            })
            ->addColumn('category', function ($record) {
                return $record->sample->summary->type->show_name . '<br>' . $record->sample->summary->subject->name;
            })
            ->addColumn('auditee', function ($record) {
                return $record->struct->name;
            })
            ->addColumn('year', function ($record) {
                return $record->sample->summary->rkia->year;
            })
            ->addColumn('month', function ($record) {
                if ($record->sample->assignment) {
                    return $record->sample->getDateRaw();
                }
                return $record->sample->summary->getMonthPlanRaw();
            })
            ->addColumn('letter_no', function ($record) {
                if (isset($record->sample->summary->lha)) {
                    $lha = $record->sample->summary->lha;
                    return $lha->no_memo  . "<br>" . $lha->date_memo?->translatedFormat('d F Y');
                }
                return '-';
            })
            ->addColumn('temuan', function ($record) {
                return $record->id_temuan  . "<br>" . ($record->regItem->deadline ?? $record->deadline)->translatedFormat('d F Y');
            })
            ->addColumn('tindak', function ($record) {
                // return dd($record->regItem->followup_note);
                if (isset($record->regItem->followup_note)) {
                    $regItem = $record->regItem;
                    return $record->getDescriptionRaw($regItem->followup_note)  . "" . $regItem->completion_date?->translatedFormat('d F Y');
                }
                return '-';
            })
            ->addColumn('auditor', function ($record) {
                if ($record->sample->assignment) {
                    return $record->sample->summary->getAuditorRaw();
                }
                return $record->sample->summary->getAuditorPlanRaw();
            })
            ->addColumn('status', function ($record) {
                if (isset($record->regItem)) {
                    if (!$record->regItem->completion_date) {
                        return \Base::makeLabel('Open', 'warning');
                    }
                    if (isset($record->regItem->monitor->reviewMonitoring) && $record->regItem->monitor->reviewMonitoring->status == 'completed') {
                        if ($record->regItem->deadline < $record->regItem->completion_date) {
                            return \Base::makeLabel('Overdue', 'danger');
                        } else if ($record->regItem->deadline >= $record->regItem->completion_date) {
                            return \Base::makeLabel('On Time', 'success');
                        }
                    }
                    return \Base::makeLabel('Open', 'warning');
                }
                return \Base::makeLabel('Open', 'warning');
            })
            ->addColumn('updated_by', function ($record) use ($user) {
                if ($record->sample->assignment) {
                    return $record->sample->assignment->createdByRaw();
                }
                return '';
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                $actions[] = 'type:show|page:true';
                // $actions[] = 'type:excel';

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'category',
                    'auditee',
                    'status',
                    'month',
                    'auditor',
                    'object_type',
                    'year',
                    'action',
                    'updated_by',
                    'letter_no',
                    'object_id',
                    'temuan',
                    'tindak'
                ]
            )
            ->make(true);
    }

    public function show($id)
    {
        $sampleDetail = KkaSampleDetail::with('regItem.monitor.reviewMonitoring', 'sample')->findOrFail($id);
        $regItem = $sampleDetail->regItem;
        $summary = $sampleDetail->sample->summary;
        $record = $regItem?->monitor?->reviewMonitoring ?? null;
        // return $this->render($this->views . '.show', compact('record', 'summary'));
        return $this->render('followup.followup-review.show', compact('summary', 'record', 'regItem', 'sampleDetail'));
    }
}
