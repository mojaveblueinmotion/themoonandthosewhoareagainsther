<?php

namespace App\Http\Controllers\Setting\Scheduler;

use App\Models\Auth\User;
use App\Models\Globals\Scheduler;
use App\Http\Controllers\Controller;
use App\Models\Globals\Notification;
use App\Models\Conducting\Kka\KkaSampleDetail;

class SchedulerController extends Controller
{
    protected $module = 'setting.scheduler';
    protected $routes = 'setting.scheduler';
    protected $views = 'setting.scheduler';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'permission' => $this->perms . '.view',
                'title' => 'Scheduler',
                'breadcrumb' => [
                    'Pengaturan Umum' => rut($this->routes . '.index'),
                    'Scheduler' => rut($this->routes . '.index'),
                ]
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
                        $this->makeColumn('name:date|label:Tanggal|className:text-left'),
                        $this->makeColumn('name:module|label:Modul|className:text-left'),
                        $this->makeColumn('name:process|label:Proses|className:text-left'),
                        $this->makeColumn('name:created_by'),
                    ],
                ],
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $records = Scheduler::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('date', function ($record) {
                return $record->created_at->translatedFormat('d M Y, H:i:s');
            })
            ->addColumn('module', function ($record) {
                return $record->show_module;
            })
            ->addColumn('process', function ($record) {
                return $record->process;
            })
            ->editColumn('created_by', function ($record) {
                return $record->createdByRaw();
            })
            ->rawColumns(['action', 'created_by'])
            ->make(true);
    }

    public function runScheduler()
    {
        $scheduler = new Scheduler();
        return $scheduler->runScheduler();
    }
}
