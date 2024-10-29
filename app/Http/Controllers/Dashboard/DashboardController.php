<?php

namespace App\Http\Controllers\Dashboard;

use App;
use App\Http\Controllers\Controller;
use App\Models\Conducting\Kka\KkaCommitment;
use App\Models\Conducting\Kka\KkaFeedback;
use App\Models\Conducting\Kka\KkaSampleDetail;
use App\Models\Conducting\Kka\KkaWorksheet;
use App\Models\Followup\FollowupMinutes;
use App\Models\Followup\FollowupMonitor;
use App\Models\Followup\FollowupRegItem;
use App\Models\Followup\FollowupReview;
use App\Models\Globals\Activity;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use App\Models\Preparation\Assignment\Assignment;
use App\Models\Rkia\Summary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $module =  'dashboard';
    protected $routes =  'dashboard';
    protected $views =  'dashboard';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'title' => 'Dashboard',
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->status != 'active') {
            return $this->render($this->views . '.nonactive');
        }
        if (!$user->checkPerms('dashboard.view') || !$user->roles()->exists()) {
            return abort(403);
        }

        $progress = [
            [
                'name' => 'reporting',
                'title' => 'Pelaporan Audit',
                'color' => 'success',
                'icon' => 'fas fa-bookmark',
            ],
            [
                'name' => 'followup',
                'title' => 'Tindak Lanjut Audit',
                'color' => 'warning',
                'icon' => 'fas fa-id-card',
            ],
        ];

        $is_auditor = false;
        if (isset($user->position->id) && $user->position->imAuditor()) {
            $is_auditor = true;
        }
        if ((isset($user->position->id) && $user->position->imAuditor()) || $user->hasRole('Administrator') || $user->hasRole('Direksi') || $user->hasRole('Direktur Utama')) {
            array_unshift($progress, [
                'name' => 'conducting',
                'title' => 'Pelaksanaan Audit',
                'color' => 'danger',
                'icon' => 'fa fa-tags',
            ]);

            array_unshift($progress, [
                'name' => 'preparation',
                'title' => 'Persiapan Audit',
                'color' => 'primary',
                'icon' => 'fas fa-paper-plane',
            ]);
        }

        return $this->render($this->views . '.index', compact('is_auditor', 'progress', 'user'));
    }

    public function setLang($lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);

        return redirect()->back();
    }


    public function progress(Request $request)
    {
        $user = auth()->user();
        // Preparation
        $total = Summary::whereHas('rkia')->dashboardProgress()->count();
        $compl = Summary::whereRelation('apm', 'status', 'completed')->dashboardProgress()->count();

        $percent = ($compl > 0 && $total > 0) ? round(($compl / $total * 100), 0) : 0;
        $cards[] = [
            'name' => 'preparation',
            'total' => $total,
            'completed' => $compl,
            'percent' => $percent,
        ];

        // Conducting
        $total = $compl;
        $compl = Summary::whereRelation('apm', 'status', 'completed')->dashboardProgress()->count();

        $percent = ($compl && $total) ? round(($compl / $total * 100), 0) : 0;
        $cards[] = [
            'name' => 'conducting',
            'total' => $total,
            'completed' => $compl,
            'percent' => $percent,
        ];

        // Reporting
        $total = $compl;
        $compl = Summary::whereRelation('lha', 'status', 'completed')->dashboardProgress()->count();

        $percent = ($compl && $total) ? round(($compl / $total * 100), 0) : 0;
        $cards[] = [
            'name' => 'reporting',
            'total' => $total,
            'completed' => $compl,
            'percent' => $percent,
        ];

        // Followup
        $total = FollowupRegItem::whereHas('sampleDetail.sample.summary', function ($q) {
            $q->dashboardProgress();
        })->count();
        $compl = FollowupReview::whereHas('monitor.regItem.reg.summary', function ($q) {
            $q->dashboardProgress();
        })
            ->where('status', 'completed')->count();

        $percent = ($compl && $total) ? round(($compl / $total * 100), 0) : 0;
        $cards[] = [
            'name' => 'followup',
            'total' => $total,
            'completed' => $compl,
            'percent' => $percent,
        ];

        return response()->json([
            'data' => $cards
        ]);
    }

    public function chartFinding(Request $request)
    {
        $user = auth()->user();

        // $user->hasRole('Auditee')
        $request->merge(['year_start' => $request->finding_start ?? date('Y') - 10]);
        $request->merge(['year_end' => $request->finding_end ?? date('Y')]);

        $years = range($request->year_start, $request->year_end);

        $data = KkaSampleDetail::countFindingForDashboard($years, $request);
        // $title = 'Temuan '.$object['object_name'].' '.$request->year_start.'/'.$request->year_end;
        $title = '  ';

        $series = [];
        foreach ($data as $key => $value) {
            $series[] = [
                'name'  => $key,
                // 'type'  => $key === 'total' ? 'area' : 'column',
                'type'  => 'column',
                'data'  => $value,
            ];
        }

        if ($user->hasRole('Administrator')) {
            return [
                'title' => ['text' => $title],
                'series' => [
                    'name'  => 'total',
                    'type'  => 'area',
                    'data'  => 0,
                ],
                'xaxis' => ['categories' => $years]
            ];
        } else {
            return [
                'title' => ['text' => $title],
                'series' => $series,
                'xaxis' => ['categories' => $years]
            ];
        }
    }

    public function chartFollowup(Request $request)
    {
        $user = auth()->user();

        $request->merge(['year_start' => $request->followup_start ?? date('Y') - 10]);
        $request->merge(['year_end' => $request->followup_end ?? date('Y')]);

        $years = range($request->year_start, $request->year_end);

        $data = KkaSampleDetail::countFollowupForDashboard($years, $request);
        // $title = 'Tindak Lanjut Temuan '.$object['object_name'].' '.$request->year_start.'/'.$request->year_end;
        $title = '  ';

        $user = auth()->user();

        if ($user->hasRole('Administrator')) {
            return [
                'title' => ['text' => $title],
                'series' => [
                    'name'  => 'total',
                    'type'  => 'area',
                    'data'  => 0,
                ],
                'xaxis' => ['categories' => $years]
            ];
        } else {
            return [
                'title' => ['text' => $title],
                'series' => [
                    [
                        'name' => 'Total',
                        'type' => 'area',
                        'data' => $data['total']
                    ],
                    [
                        'name' => 'Open',
                        'type' => 'column',
                        'data' => $data['open']
                    ],
                    [
                        'name' => 'On Time',
                        'type' => 'column',
                        'data' => $data['ontime']
                    ],
                    [
                        'name' => 'Overdue',
                        'type' => 'column',
                        'data' => $data['overdue']
                    ],
                    // [
                    //     'name' => 'Close',[inde]
                    //     'type' => 'column',
                    //     'data' => $data['close']
                    // ],
                ],
                'xaxis' => ['categories' => $years]
            ];
        }
    }


    public function chartStage(Request $request)
    {
        $user = auth()->user();
        $request->merge(['year' => $request->stage_year ?? date('Y')]);
        $year = $request->year;
        $stage_object = $request->stage_object;

        $categories = [];
        $total = [];
        $completed = [];
        $progress = [];

        // Surat Penugasan
        $categories[] = 'Audit Plan';
        $total[] = Summary::chartStage($year, $stage_object)->count();
        $completed[] = Summary::chartStage($year, $stage_object, 'rkia')->count();
        $progress[] = (end($total) - end($completed));

        // Surat Penugasan
        $categories[] = 'Surat Penugasan';
        $total[] = Summary::gridAssignment()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridAssignment()->chartStage($year, $stage_object, 'assignment')->count();
        $progress[] = (end($total) - end($completed));

        // APM
        $categories[] = 'Audit Program';
        $total[] = Summary::gridApm()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridApm()->chartStage($year, $stage_object, 'apm')->count();
        $progress[] = (end($total) - end($completed));

        // Memo Opening
        $categories[] = 'Memo Opening';
        $total[] = Summary::gridMemoOpening()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridMemoOpening()->chartStage($year, $stage_object, 'memoOpening')->count();
        $progress[] = (end($total) - end($completed));

        // Opening Meeting
        $categories[] = 'Opening Meeting';
        $total[] = Summary::gridOpening()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridOpening()->chartStage($year, $stage_object, 'opening')->count();
        $progress[] = (end($total) - end($completed));

        // Kertas Kerja
        $categories[] = 'Kertas Kerja';
        $total[] = Summary::gridSample()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridSample()->chartStage($year, $stage_object, 'samples')->count();
        $progress[] = (end($total) - end($completed));

        // Review Kertas Kerja
        $categories[] = 'Review Kertas Kerja';
        $total[] = Summary::gridReviewSample()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridReviewSample()->chartStage($year, $stage_object, 'reviewSamples')->count();
        $progress[] = (end($total) - end($completed));

        // Feedback
        $categories[] = 'Tanggapan';
        $total[] = KkaFeedback::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->count();
        $completed[] = KkaFeedback::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->where('status', 'completed')->count();
        $progress[] = (end($total) - end($completed));

        // Audit Worksheet
        $categories[] = 'Temuan Sementara';
        $total[] = KkaWorksheet::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->count();
        $completed[] = KkaWorksheet::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->where('status', 'completed')->count();
        $progress[] = (end($total) - end($completed));

        // Komitmen
        $categories[] = 'Komentar Manajemen';
        $total[] = KkaCommitment::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->count();
        $completed[] = KkaCommitment::whereHas('sampleDetail.sample.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })->where('status', 'completed')->count();
        $progress[] = (end($total) - end($completed));

        // Memo Closing
        $categories[] = 'Memo Closing';
        $total[] = Summary::gridMemoClosing()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridMemoClosing()->chartStage($year, $stage_object, 'closing')->count();
        $progress[] = (end($total) - end($completed));

        // Closing Meeting
        $categories[] = 'Closing Meeting';
        $total[] = Summary::gridClosing()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridClosing()->chartStage($year, $stage_object, 'memoClosings')->count();
        $progress[] = (end($total) - end($completed));

        // LHP
        $categories[] = 'LHA';
        $total[] = Summary::gridLha()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridLha()->chartStage($year, $stage_object, 'lha')->count();
        $progress[] = (end($total) - end($completed));

        // Memo Tindak Lanjut
        $categories[] = 'Memo Tindak Lanjut';
        $total[] = Summary::gridFollowupMemo()->chartStage($year, $stage_object)->count();
        $completed[] = Summary::gridFollowupMemo()->chartStage($year, $stage_object, 'followupMemos')->count();
        $progress[] = (end($total) - end($completed));

        // Monitoring Tindak Lanjut
        $categories[] = 'Monitoring Tindak Lanjut';
        $total[] = FollowupMonitor::whereHas('regItem.reg.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })
            ->count();
        $completed[] = FollowupMonitor::whereHas('regItem.reg.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })
            ->where('status', 'completed')
            ->count();
        $progress[] = (end($total) - end($completed));

        // Review Monitoring
        $categories[] = 'Review Monitoring';
        $total[] = FollowupReview::whereHas('monitor.regItem.reg.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })
            ->count();
        $completed[] = FollowupReview::whereHas('monitor.regItem.reg.summary', function ($q) use ($year, $stage_object) {
            $q->chartStage($year, $stage_object);
        })
            ->where('status', 'completed')
            ->count();
        $progress[] = (end($total) - end($completed));

        return [
            // 'title' => ['text' => 'Tahap Audit ' . $object['object_name'] . ' ' . $request->year],
            'series' => [
                [
                    'name' => 'Total',
                    'type' => 'area',
                    'data' => $total
                ], [
                    'name' => 'On Progress',
                    'type' => 'column',
                    'data' => $progress
                ], [
                    'name' => 'Completed',
                    'type' => 'column',
                    'data' => $completed
                ],
            ],
            'xaxis' => ['categories' => $categories]
        ];
    }

    public function chartLogin(Request $request)
    {
        // login__perusahaan
        // login__object
        $user = auth()->user();


        $request->merge(['login__start' => $request->login__start ?? date('d/m/Y')]);
        $request->merge(['login__end' => $request->login__end ?? date('d/m/Y')]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->login__start);
        $endDate = Carbon::createFromFormat('d/m/Y', $request->login__end);

        $dates = [];

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->format('d/m/Y');
            $startDate->addDay();
        }

        $data = Activity::countDailyLoginForDashboard($request->login__start, $request->login__end, $request->login__perusahaan);
        $title = '  ';

        $series = [];
        foreach ($data as $key => $value) {
            $series[] = [
                'name'  => $key,
                'type'  => 'column',
                'data'  => $value,
            ];
        }

        return [
            'title' => ['text' => $title],
            'series' => $series,
            'xaxis' => ['categories' => $dates]
        ];
    }

    public function chartLoginMonthly(Request $request)
    {
        $user = auth()->user();


        $request->merge(['login__monthly_start' => $request->login__monthly_start ?? date('Y')]);

        $year = Carbon::createFromFormat('Y', $request->login__monthly_start)->startOfYear();

        $monthsArray = [];

        for ($i = 0; $i < 12; $i++) {
            $monthsArray[] = $year->format('m/Y');
            $year->addMonth();
        }

        $data = Activity::countMonthlyLoginForDashboard($request->login__monthly_start, $request->login__monthly_perusahaan);

        $title = '';

        $series = [];
        foreach ($data as $key => $value) {
            $series[] = [
                'name'  => $key,
                'type'  => 'column',
                'data'  => $value,
            ];
        }

        return [
            'title' => ['text' => $title],
            'series' => $series,
            'xaxis' => ['categories' => $monthsArray]
        ];
    }
}
