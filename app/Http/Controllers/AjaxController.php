<?php

namespace App\Http\Controllers;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Rkia\Rkia;
use Illuminate\Support\Str;
use App\Models\Globals\Menu;
use App\Models\Rkia\Summary;
use Illuminate\Http\Request;
use App\Models\Survey\SurveyReg;
use App\Models\Globals\TempFiles;
use App\Models\Tm1\KasLapakDetail;
use App\Models\Master\Fee\CostType;
use App\Models\Master\Org\Position;
use App\Models\Globals\Notification;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Fee\AssetType;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Geografis\City;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Master\Fee\BankAccount;
use App\Models\Master\Pembukuan\Lapak;
use App\Models\Master\Risk\RiskRating;
use App\Models\Master\Risk\RiskStatus;
use App\Models\Tm1\PembukuanSamDetail;
use App\Models\Master\Risk\LevelDampak;
use App\Models\Master\Risk\MainProcess;
use App\Models\Conducting\Kka\KkaSample;
use App\Models\Master\Fee\CostComponent;
use App\Models\Master\Org\LevelPosition;
use App\Models\Tm1\PembukuanLapakDetail;
use App\Models\Master\Geografis\Province;
use App\Models\Master\Procedure\Criteria;
use App\Models\Preparation\Apm\ApmDetail;
use App\Models\Master\Pembukuan\Kendaraan;
use App\Models\Master\Pembukuan\Pembayaran;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\Master\Document\DocumentItem;
use App\Models\Master\Org\DepartmentAuditee;
use App\Models\Master\Risk\LevelKemungkinan;
use App\Models\Preparation\Document\Document;
use App\Models\Master\Document\AuditReference;
use App\Models\Master\Procedure\ProcedureAudit;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Models\Master\Penilaian\PenilaianCategory;
use App\Models\Master\Objective\Objective\Objective;
use App\Models\RiskAssessment\RiskRating as RiskAssessmentRating;

class AjaxController extends Controller
{
    public function saveTempFiles(Request $request)
    {
        $this->beginTransaction();
        $mimes = null;
        if ($request->accept == '.xlsx') {
            $mimes = 'xlsx';
        }
        if ($request->accept == '.png, .jpg, .jpeg') {
            $mimes = 'png,jpg,jpeg';
        }
        if ($mimes) {
            $request->validate(
                ['file' => ['mimes:' . $mimes]]
            );
        }
        try {
            if ($file = $request->file('file')) {
                $file_path = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName());
                $file_path .= '-' . time() . '.' . $file->getClientOriginalExtension();

                $temp = new TempFiles;
                $temp->file_name = $file->getClientOriginalName();
                $temp->file_path = $file->storeAs('temp-files', $file_path, 'public');
                // $temp->file_type = $file->extension();
                $temp->file_size = $file->getSize();
                $temp->flag = $request->flag;
                $temp->save();
                return $this->commit(
                    [
                        'file' => TempFiles::find($temp->id)
                    ]
                );
            }
            return $this->rollback(['message' => 'File not found']);
        } catch (\Exception $e) {
            return $this->rollback(['error' => $e->getMessage()]);
        }
    }
    public function testNotification($emails)
    {
        if ($rkia = Rkia::latest()->first()) {
            request()->merge(
                [
                    'module' => 'rkia_operation',
                ]
            );
            $emails = explode('--', trim($emails));
            $user_ids = User::whereIn('email', $emails)->pluck('id')->toArray();
            $rkia->addNotify(
                [
                    'message' => 'Waiting Approval RKIA ' . $rkia->show_category . ' ' . $rkia->year,
                    'url' => rut('rkia.operation.summary', $rkia->id),
                    'user_ids' => $user_ids,
                ]
            );
            $record = Notification::latest()->first();
            return $this->render('mails.notification', compact('record'));
        }
    }

    public function userNotification()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->simplePaginate(25);
        return $this->render('layouts.base.notification', compact('notifications'));
    }

    public function userNotificationRead(Notification $notification)
    {
        auth()->user()
            ->notifications()
            ->updateExistingPivot($notification, array('readed_at' => now()), false);
        return redirect($notification->full_url);
    }

    public function selectRole($search, Request $request)
    {
        $items = Role::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'approver':
                $menu = Menu::where('module', $request->perms)->first();
                $perms = $menu->show_perms . ".approve";
                $items = $items->whereHas(
                    'permissions',
                    function ($q) use ($perms) {
                        $q->where('name', $perms);
                    }
                );
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectCostComponent(Request $request)
    {
        $items = CostComponent::keywordBy('name')
            ->orderBy('name');

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectCostType(Request $request)
    {
        $items = CostType::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }
    public function selectBankAccount($search, Request $request)
    {
        $items = BankAccount::keywordBy('number')->orderBy('number');
        switch ($search) {
            case 'all':
                break;
            case 'find':
                return $items->with('owner')->find($request->id);

            case 'byOwner':
                $owners = json_decode($request->owner);
                $items = $items->whereIn('user_id', $owners);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        $results = [];
        $more = false;
        foreach ($items as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->number . ' - ' . $item->bank . ' - ' . $item->owner->name
            ];
        }
        if (method_exists($items, 'hasMorePages')) {
            $more = $items->hasMorePages();
        }
        return response()->json(compact('results', 'more'));
    }
    public function selectLevelPosition(Request $request)
    {
        $items = LevelPosition::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectStruct($search, Request $request)
    {
        $items = OrgStruct::keywordBy('name')->orderBy('level')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'with_parent':
                $items = $items->where('parent_id', $request->parent_id);
                break;
            case 'parent_boc':
                $items = $items->whereIn('level', ['root']);
                break;
            case 'parent_bod':
                $items = $items->whereIn('level', ['root', 'bod']);
                break;
            case 'parent_department':
                $items = $items->whereIn('level', ['bod']);
                break;
            case 'parent_division':
                $items = $items->whereIn('level', ['department']);
                break;
            case 'parent_subdivision':
                $items = $items->whereIn('level', ['division']);
                break;
            case 'parent_unitKerja':
                $items = $items->whereIn('level', ['subdivision']);
                break;
            case 'parent_position':
                $items = $items->whereNotIn('level', ['root', 'group']);
                break;
            case 'object_audit_report':
                $items = $items->whereNotIn('level', ['root', 'group', 'bod', 'boc']);
                break;
            case 'object_instruction':
                $items = $items->whereIn('level', ['department', 'division', 'provider']);
                break;
            case 'unit_kerja':
                $items = $items->whereIn('level', ['subsidiary', 'department', 'division']);
                break;
            case 'department_auditee':
                $items = $items->whereIn('level', ['subsidiary', 'department']);
                break;
            case 'subject':
                $items = $items->where('level', 'subject');
                $items = $items
                    ->when(
                        $not = $request->not,
                        function ($q) use ($not) {
                            $q->where('id', '!=', $not);
                        }
                    )
                    ->when(
                        $type_id = $request->type_id,
                        function ($q) use ($type_id) {
                            $q->where('type_id', $type_id);
                        }
                    )
                    ->get();

                return $this->responseSelect2($items, 'name', 'id');
            case 'select_unit':
                $items = $items->whereIn('parent_id', [$request->parent_id]);
                break;
            case 'object_audit':
                $items = $items->whereIn('level', ['department', 'division']);
                break;
            case 'position_with_req':
                $items = $items
                    ->whereNotIn('level', ['root', 'subject']);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items
            ->when(
                $parent_id = $request->parent_id,
                function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                }
            )
            ->when(
                $id = $request->id,
                function ($q) use ($id) {
                    $q->where('id', $id);
                }
            )
            ->when(
                $not = $request->not,
                function ($q) use ($not) {
                    $q->where('id', '!=', $not);
                }
            )
            ->when(
                $type_id = $request->type_id,
                function ($q) use ($type_id) {
                    $q->where('type_id', $type_id);
                }
            )
            ->get();
        $results = [];
        $more = false;

        $levels = ['root', 'boc', 'bod', 'subsidiary', 'department', 'division', 'subdivision', 'subject'];
        $i = 0;

        foreach ($levels as $level) {
            if ($items->where('level', $level)->count()) {
                foreach ($items->where('level', $level) as $item) {
                    $results[$i]['text'] = strtoupper($item->show_level);
                    $results[$i]['children'][] = ['id' => $item->id, 'text' => $item->name];
                }
                $i++;
            }
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectPosition($search, Request $request)
    {
        $items = Position::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'by_location':
                $items = $items->where('location_id', $request->id);
                break;
            case 'divisi_spi':
                $location_id = OrgStruct::where('name', 'Satuan Pengawas Internal')->firstOrFail();
                $items = $items->where('location_id', $location_id);
                break;
            case 'auditor':
                $items = $items->whereHas(
                    'location',
                    function ($qq) {
                        $qq->inAudit();
                    }
                );
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectUser($search, Request $request)
    {
        $items = User::keywordBy('name')
            ->where('status', 'active')
            ->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items->whereHas('roles', function ($query) {
                    $query->where('id', '!=', 1);
                })
                    ->when(
                        $with_admin = $request->with_admin,
                        function ($q) use ($with_admin) {
                            $q->orWhere('id', 1);
                        }
                    );
                break;
            case 'level_bod':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->where('level', 'bod');
                            }
                        );
                    }
                );
                break;
            case 'level_boc':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->where('level', 'boc');
                            }
                        );
                    }
                );
                break;
            case 'boc_bod':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->whereIn('level', ['boc', 'bod']);
                            }
                        );
                    }
                );
                break;
            case 'boc_bod_manajer':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->whereIn('level', ['boc', 'bod']);
                            }
                        )->orWhereHas('level', function ($q) {
                            $q->whereIn('name', ['Manajer Unit', 'Manajer SPI']);
                        });
                    }
                );
                break;
            case 'boc_bod_auditor':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->whereIn('level', ['boc', 'bod']);
                            }
                        );
                    }
                )->orWhereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->inAudit();
                            }
                        );
                    }
                );
                break;
            case 'user-ti':
                $items = User::keywordBy('name')->orderBy('name');
                if ($summary = Summary::find($request->summary_id)) {
                    $items = $items->where('type', 'provider')->where('provider_id', $summary->object_id);
                }
                break;
            case 'auditor':
                $type_id = $request->type_id ?? 0;
                $location_id = $request->location_id ?? 0;
                $items = $items->whereHas('position.location', function ($qq) {
                    $qq->inAudit();
                });

                break;
            case 'pengendali_teknis':
                $items = $items->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'struct',
                            function ($qq) {
                                $qq->inAudit();
                            }
                        )->whereHas('level', function ($q) {
                            $q->where('name', 'LIKE', '%' . 'Asisten Manajer SPI');
                        });
                    }
                );
                break;
            case 'assignment':
                $location_id = 0;
                if ($summary = Summary::find($request->summary_id)) {
                    $location_id = $summary->getStruct()->id ?? 0;
                }
                $items = $items->whereHas(
                    'position',
                    function ($q) use ($location_id) {
                        $q->whereHas(
                            'location',
                            function ($qq) use ($location_id) {
                                $qq->where(
                                    function ($qqq) use ($location_id) {
                                        $qqq->where('id', $location_id);
                                    }
                                )
                                    ->orWhere(
                                        function ($qqq) {
                                            $qqq->inAudit();
                                        }
                                    );
                            }
                        );
                    }
                );
                break;
            case 'auditee':
                $summary = Summary::find($request->summary_id);
                $items = $items
                    ->whereHas(
                        'position',
                        function ($q) use ($summary) {
                            $struct_ids = $summary->subject->childOfGroup->pluck('id')->toArray();
                            // dd($struct_ids);
                            $q->whereIn('location_id', $struct_ids);
                        }
                    );
                break;
            case 'boc_bod_auditee':
                $location_id = 0;
                $type_id = 0;
                $provider_id = 0;
                if ($request->summary_id) {
                    $summary = Summary::find($request->summary_id);
                    $location_id = $summary->getStruct()->id ?? 0;
                    $type_id = $summary->type_id ?? 0;
                    $provider_id = $summary->bject_id ?? 0;
                }
                if ($request->location_id) {
                    $location_id = $request->location_id;
                }
                $items = $items->whereHasLocationId($location_id, $type_id, $provider_id)->orWhereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->whereIn('level', ['boc', 'bod']);
                            }
                        );
                    }
                );
                break;
            case 'not_auditor_auditee':
                $location_id = 0;
                if ($summary = Summary::find($request->summary_id)) {
                    $location_id = $summary->getStruct()->id ?? 0;
                    $type_id = $summary->type_id ?? 0;
                    $provider_id = $summary->object_id ?? 0;
                }
                $items2 = User::keywordBy('name')
                    ->has('position')
                    ->where('status', 'active')
                    ->orderBy('name');
                $items3 = User::keywordBy('name')
                    ->where('status', 'active')
                    ->orderBy('name');
                $auditee = $items3->whereHasLocationId($location_id);
                $auditor = $items2->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->inAudit();
                            }
                        );
                    }
                );
                $items = $items->whereNotIn('id', $auditor->pluck('id')->toArray())->whereNotIn('id', $auditee->pluck('id')->toArray());
                break;
            case 'auditor-to':
                if ($summary = Summary::find($request->summary_id)) {
                    $location = OrgStruct::find($summary->object_id);
                    $user_object = [];
                    if ($location) {
                        foreach ($summary->departmentAuditee->departments as $val) {
                            $user_object[] = [$val->id];
                        }
                    }
                }

                $items = $items->whereHas(
                    'position',
                    function ($q) use ($user_object) {
                        $q->whereHas(
                            'location',
                            function ($qq) use ($user_object) {
                                $qq->WhereIn('id', $user_object);
                            }
                        );
                    }
                );
                break;
            case 'auditor_auditee':
                $location_id = $request->struct_id ?? 0;
                $type_id = 0;
                $provider_id = 0;
                if ($summary = Summary::find($request->summary_id)) {
                    $location_id = $summary->getStruct()->id ?? 0;
                    $type_id = $summary->type_id ?? 0;
                    $provider_id = $summary->object_id ?? 0;
                }
                $items2 = User::keywordBy('name')
                    ->has('position')
                    ->where('status', 'active')
                    ->orderBy('name');
                $items3 = User::keywordBy('name')
                    ->where('status', 'active')
                    ->orderBy('name');
                $auditee = $items3->whereHasLocationId($location_id, $type_id, $provider_id);
                $auditor = $items2->whereHas(
                    'position',
                    function ($q) {
                        $q->whereHas(
                            'location',
                            function ($qq) {
                                $qq->inAudit();
                            }
                        );
                    }
                );
                $items = $items->whereIn('id', $auditor->pluck('id')->toArray())->orWhereIn('id', $auditee->pluck('id')->toArray());
                break;
            case 'auditor_auditee_bod':
                $location_id = 0;
                $type_id = 0;
                $provider_id = 0;
                if ($summary = Summary::find($request->summary_id)) {
                    $location_id = $summary->getStruct()->id ?? 0;
                    $type_id = $summary->type_id ?? 0;
                    $provider_id = $summary->object_id ?? 0;
                }
                $list = [];
                $auditee = User::keywordBy('name')
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->whereHasLocationId($location_id, $type_id, $provider_id);
                $list = array_merge($list, $auditee->pluck('id')->toArray());
                $auditor = User::keywordBy('name')
                    ->has('position')
                    ->where('status', 'active')
                    ->orderBy('name')->whereHas(
                        'position',
                        function ($q) {
                            $q->whereHas(
                                'location',
                                function ($qq) {
                                    $qq->inAudit();
                                }
                            );
                        }
                    );
                $list = array_merge($list, $auditor->pluck('id')->toArray());
                $bod = User::keywordBy('name')
                    ->has('position')
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->whereHas(
                        'position',
                        function ($q) {
                            $q->whereHas(
                                'location',
                                function ($qq) {
                                    $qq->whereIn('level', ['bod', 'boc']);
                                }
                            );
                        }
                    );
                $list = array_merge($list, $bod->pluck('id')->toArray());
                $items = $items->whereIn('id', $list);
                break;
            case 'cc':
                $location_id = 0;
                if ($summary = Summary::find($request->summary_id)) {
                    $location_id = $summary->getStruct()->id ?? 0;
                }
                $list = [];
                if ($summary->rkia->category == 'operation') {
                    $auditee = User::keywordBy('name')
                        ->has('position')
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->whereHasLocationId($location_id);
                    $list = array_merge($list, $auditee->pluck('id')->toArray());
                }
                $bod = User::keywordBy('name')
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->whereHas(
                        'position',
                        function ($q) {
                            $q->whereHas(
                                'struct',
                                function ($qq) {
                                    $qq->whereIn('level', ['bod', 'boc']);
                                }
                            );
                        }
                    );
                $list = array_merge($list, $bod->pluck('id')->toArray());
                $items = $items->whereIn('id', $list);
                break;
            case 'auditor_summary_leader_and_member':
                $get_id_auditors = [];
                if ($summary = Summary::find($request->summary_id)) {
                    $get_id_auditors = $summary->getAuditorIds('leader_and_member');
                }
                $items = $items->whereIn('id', $get_id_auditors);
                break;
            case 'assignment_leader_members':
                $get_id_auditors = [];
                $not_id_auditors = [];
                if ($summary = Summary::find($request->summary_id)) {
                    $get_id_auditors = $summary->getAuditorIds('leader_and_member');
                }
                $items = $items->whereIn('id', $get_id_auditors)->whereNotIn('id', $not_id_auditors);
                break;
            case 'survey-auditee':
                $location_id = 0;
                $items = $items->whereIn('id', $location_id);
                break;
            case 'penilaian-kinerja-auditor':
                $location_id = 0;
                $items = $items->whereIn('id', $location_id);
                break;
            case 'by-struct':
                $items = $items->whereRelation('position', 'location_id', $request->struct_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();

        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            if ($item->hasRole(1)) {
                $results[] = ['id' => $item->id, 'text' => $item->name];
            } else {
                $results[] = ['id' => $item->id, 'text' => $item->name . ' (' . (isset($item->position) ? $item->position->name : $item->jabatan_provider . " " . $item->provider->name) . ')'];
            }
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectTypeAudit($search, Request $request)
    {
        $items = TypeAudit::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'without_investigasi':
                $items = $items->where('name', '!=', 'Audit Investigasi');
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function getCheckUniqueNoKka(Request $request)
    {
        // Ambil nilai code dari request
        $code = $request->input('no_kka');

        // Lakukan pengecekan keunikan
        $isUnique = KkaSample::where('no_kka', $code)->doesntExist();

        // Kembalikan respons dalam bentuk JSON
        return response()->json(['unique' => $isUnique]);
    }

    public function selectJenisBiaya($search, Request $request)
    {
        $items = CostType::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectAktiva($search, Request $request)
    {
        $items = AssetType::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectLapak($search, Request $request)
    {
        $items = Lapak::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectKendaraan($search, Request $request)
    {
        $items = Kendaraan::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items->paginate();
                $results = [];
                $more = false;
                foreach ($items as $item) {
                    $results[] = [
                        'id' => $item->id,
                        'text' => $item->name . ' (' . $item->no_kendaraan . ')'
                    ];
                }
                if (method_exists($items, 'hasMorePages')) {
                    $more = $items->hasMorePages();
                }
                return response()->json(compact('results', 'more'));
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }
    
    public function getTotalPembukuan(Request $request)
    {
        $pembukuan_lapak_id = $request->pembukuan_lapak_id;

        return response()->json([
            'total_dibayar'  => PembukuanLapakDetail::when(
                $pembukuan_lapak_id = $request->pembukuan_lapak_id,
                    function ($q) use ($pembukuan_lapak_id) {
                        $q->where('pembukuan_lapak_id', $pembukuan_lapak_id);
                    }
                )
                ->get()
                ->sum('total_dibayar'),

            'pengeluaran_lapak' => PembukuanLapakDetail::when(
                $pembukuan_lapak_id = $request->pembukuan_lapak_id,
                    function ($q) use ($pembukuan_lapak_id) {
                        $q->where('pembukuan_lapak_id', $pembukuan_lapak_id);
                    }
                )
                ->get()
                ->sum('pengeluaran_lapak'),

            'gross' => PembukuanLapakDetail::when(
                $pembukuan_lapak_id = $request->pembukuan_lapak_id,
                    function ($q) use ($pembukuan_lapak_id) {
                        $q->where('pembukuan_lapak_id', $pembukuan_lapak_id);
                    }
                )
                ->get()
                ->sum('gross'),

            'netto' => PembukuanLapakDetail::when(
                $pembukuan_lapak_id = $request->pembukuan_lapak_id,
                    function ($q) use ($pembukuan_lapak_id) {
                        $q->where('pembukuan_lapak_id', $pembukuan_lapak_id);
                    }
                )
                ->get()
                ->sum('netto'),
        ]);
    }

    
    public function getTotalPembukuanSam(Request $request)
    {
        $pembukuan_sam_id = $request->pembukuan_sam_id;

        return response()->json([
            'total_dibayar'  => PembukuanSamDetail::when(
                $pembukuan_sam_id = $request->pembukuan_sam_id,
                    function ($q) use ($pembukuan_sam_id) {
                        $q->where('pembukuan_sam_id', $pembukuan_sam_id);
                    }
                )
                ->get()
                ->sum('total_dibayar'),

            'hasil_akhir' => PembukuanSamDetail::when(
                $pembukuan_sam_id = $request->pembukuan_sam_id,
                    function ($q) use ($pembukuan_sam_id) {
                        $q->where('pembukuan_sam_id', $pembukuan_sam_id);
                    }
                )
                ->get()
                ->sum('hasil_akhir'),

            'gross' => PembukuanSamDetail::when(
                $pembukuan_sam_id = $request->pembukuan_sam_id,
                    function ($q) use ($pembukuan_sam_id) {
                        $q->where('pembukuan_sam_id', $pembukuan_sam_id);
                    }
                )
                ->get()
                ->sum('gross'),

            'netto' => PembukuanSamDetail::when(
                $pembukuan_sam_id = $request->pembukuan_sam_id,
                    function ($q) use ($pembukuan_sam_id) {
                        $q->where('pembukuan_sam_id', $pembukuan_sam_id);
                    }
                )
                ->get()
                ->sum('netto'),
        ]);
    }

    public function getTotalKas(Request $request)
    {
        $kas_lapak_id = $request->kas_lapak_id;

        $historyData = KasLapakDetail::when(
            $kas_lapak_id = $request->kas_lapak_id,
                function ($q) use ($kas_lapak_id) {
                    $q->where('kas_lapak_id', $kas_lapak_id);
                }
            )->orderBy('id', 'asc')->get();
        $saldoHistory = [];  // Store saldo for each transaction
        $currentSaldo = 0;   // Initialize starting saldo
        
        // Step 1: Calculate the saldo in correct (ascending) order
        foreach ($historyData as $kas) {
            if ($kas->tipe == 1) {  // Debit: Subtract from saldo
                $currentSaldo -= $kas->total;
            } else {  // Credit: Add to saldo
                $currentSaldo += $kas->total;
            }
            
            // Store the calculated saldo for each detail (by ID)
            $saldoHistory[$kas->id] = $currentSaldo;
        }

        $lastInsertedKas = KasLapakDetail::when(
            $kas_lapak_id = $request->kas_lapak_id,
                function ($q) use ($kas_lapak_id) {
                    $q->where('kas_lapak_id', $kas_lapak_id);
                }
            )->orderBy('id', 'desc')->first();

        return response()->json([
            'debet'  => KasLapakDetail::when(
                $kas_lapak_id = $request->kas_lapak_id,
                    function ($q) use ($kas_lapak_id) {
                        $q->where('kas_lapak_id', $kas_lapak_id);
                    }
                )
                ->where('tipe', 2)
                ->get()
                ->sum('total'),

            'kredit' => KasLapakDetail::when(
                $kas_lapak_id = $request->kas_lapak_id,
                    function ($q) use ($kas_lapak_id) {
                        $q->where('kas_lapak_id', $kas_lapak_id);
                    }
                )
                ->where('tipe', 1)
                ->get()
                ->sum('total'),

            'saldo_sisa' => $saldoHistory[$lastInsertedKas->id],
        ]);
    }
    
    



    public function selectPembayaran($search, Request $request)
    {
        $items = Pembayaran::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectKomponenBiaya($search, Request $request)
    {
        $items = CostComponent::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                break;
            case 'by_type':
                $items = $items->where('type_id', $request->type_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectCity($search, Request $request)
    {
        $items = City::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'by_province':
                $items = $items->where('province_id', $request->province_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectProvince($search, Request $request)
    {
        $items = Province::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectAspect($search, Request $request)
    {
        $items = Aspect::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'parent_level':
                $subject_id            = $request->parent_id;
                $items = $items->where('object_id', $subject_id);
                // if ($type_id == 2) {
                //     $items = $items->where('subject_id', $subject_id);
                // } else {
                //     $items
                //         ->when(
                //             in_array($object_type, ['department', 'division']),
                //             function ($q) use ($object_type) {
                //                 $q
                //                     ->where(
                //                         function ($q) use ($object_type) {
                //                             $q
                //                                 ->where('object_type', $object_type);
                //                         }
                //                     );
                //             },
                //             function ($q) use ($object_type) {
                //                 $q
                //                     ->whereRelation('object', 'level', $object_type);
                //             },
                //         )
                //         ->select('id', 'name')
                //         ->orderBy('name')
                //         ->distinct('name')
                //         ->get(['name']);
                // }
                break;
            case 'parent_doc':
                $type_id = $request->type_id;
                $subject_id = $request->subject_id;
                if ($type_id == 2) {
                    $items = $items->where('type_id', $type_id);
                } else {
                    $items = $items->where('subject_id', $subject_id);
                }
                break;
            case 'by_subject':
                $items = $items
                    ->whereRelation('subject', 'type_id', $request->type_id)
                    ->when(
                        $main_process_id = $request->main_process_id,
                        function ($q) use ($main_process_id) {
                            $q->where('main_process_id', $main_process_id);
                        }
                    )
                    ->where('object_id', $request->subject_id);
                break;
            case 'by_main_process':
                $items = $items
                    ->where('main_process_id', $request->main_process_id);
                break;
            case 'by_risk_register':
                $riskRegisterDetail = RiskRegisterDetail::where('main_process_id', $request->main_process_id)
                    ->pluck('sub_process_id')
                    ->toArray();
                $items = $items->whereIn('id', $riskRegisterDetail);
                break;
            case 'by_ids':
                $ids = $request->ids ?? [];
                $items = $items->whereIn('id', $ids);
                break;
            case 'by_object':
                $type_id = $request->type_id;
                $items = $items->where('object_id', $type_id);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectObjective($search, Request $request)
    {
        $items = Objective::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'by_aspect':
                $items = $items->where('aspect_id', $request->aspect_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectDepartmentAuditee($search, Request $request)
    {
        $items = DepartmentAuditee::keywordBy('name')
            ->when(
                $year = $request->year,
                function ($q) use ($year) {
                    $q->where('year', $year);
                }
            )
            ->when(
                $subject_id = $request->subject_id,
                function ($q) use ($subject_id) {
                    $q->where('subject_id', $subject_id);
                }
            )
            ->when(
                $type_id = $request->type_id,
                function ($q) use ($type_id) {
                    $q->where('type_id', $type_id);
                }
            );
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'subject':
                $items = $items->paginate(20);
                $results = [];
                $more = false;
                foreach ($items as $item) {
                    $results[] = ['id' => $item->id, 'text' => $item->subject->name];
                }
                if (method_exists($items, 'hasMorePages')) {
                    $more = $items->hasMorePages();
                }
                return response()->json(compact('results', 'more'));
                break;
            case 'by_subject':
                $items = $items
                    ->where('subject_id', $request->subject_id)
                    ->whereYear('year', $request->year);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectCriteria(Request $request)
    {
        $items = Criteria::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectObject(Request $request)
    {
        switch ($request->category) {
            case 'all':
                $levels = ['subsidiary', 'department', 'division'];
                $items = OrgStruct::keywordBy('name')
                    ->whereIn('level', $levels)
                    ->orderBy('level')
                    ->orderBy('name')
                    ->get();
                $results = [];
                $optgroups = [];
                $i = 0;
                if (!empty($request->optstart_id) && !empty($request->optstart_text)) {
                    $results[$i]['id'] = $request->optstart_id;
                    $results[$i]['text'] = $request->optstart_text;
                    $i++;
                }
                foreach ($levels as $level) {
                    foreach ($items->where('level', $level) as $item) {
                        if (!in_array($item->level, $optgroups)) {
                            $optgroups[] = $item->level;
                            $results[$i]['text'] = strtoupper($item->show_level);
                        }
                        $results[$i]['children'][] = ['id' => $item->id, 'text' => $item->name];
                        $i++;
                    }
                }
                $more = false;
                return response()->json(compact('results', 'more'));
            case 'provider':
                $items = OrgStruct::where('level', 'provider')->keywordBy('name')->orderBy('name')->paginate();
                return $this->responseSelect2($items, 'name', 'id');

            case 'by_type':
                $levels = ['subsidiary', 'department', 'division'];
                $items = OrgStruct::keywordBy('name')
                    ->whereIn('level', $levels)
                    ->orderBy('level')
                    ->orderBy('name')
                    ->get();
                $results = [];
                $optgroups = [];
                $i = 0;
                if (!empty($request->optstart_id) && !empty($request->optstart_text)) {
                    $results[$i]['id'] = $request->optstart_id;
                    $results[$i]['text'] = $request->optstart_text;
                    $i++;
                }
                foreach ($levels as $level) {
                    foreach ($items->where('level', $level) as $item) {
                        if (!in_array($item->level, $optgroups)) {
                            $optgroups[] = $item->level;
                            $results[$i]['text'] = strtoupper($item->show_level);
                        }
                        $results[$i]['children'][] = ['id' => $item->id, 'text' => $item->name];
                        $i++;
                    }
                }
                $more = false;
                return response()->json(compact('results', 'more'));

                break;
            default:
                $results = [];
                $more = false;
                return response()->json(compact('results', 'more'));
        }
    }

    public function selectAuditReference($search, Request $request)
    {
        $items = AuditReference::keywordBy('name')->has('aspect')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->find($request->id);
            case 'by_aspect':
                $items = $items->where('aspect_id', $request->aspect_id);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectDocItem($search, Request $request)
    {
        $items = DocumentItem::keywordBy('name')->has('aspect')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->find($request->id);
            case 'by_aspect':
                $items = $items->where('aspect_id', $request->aspect_id);
                $items = $items->paginate(100);
                $results = [];
                $more = false;
                foreach ($items as $item) {
                    $results[] = ['id' => $item->name, 'text' => $item->name];
                }
                if (method_exists($items, 'hasMorePages')) {
                    $more = $items->hasMorePages();
                }
                return response()->json(compact('results', 'more'));
                break;
            case 'by_aspect_sample':
                $items = $items->where('aspect_id', $request->aspect_id);
                $items = $items->paginate(1000);
                // return $this->responseSelect2($items, 'name', 'name');
                $set = [];
                $results = [];
                $more = false;
                foreach ($items as $item) {
                    $set[] = $item->name;
                    $results[] = ['id' => $item->name, 'text' => $item->name];
                }
                $items = \App\Models\Preparation\Document\DocumentDetail::whereRelation('docFull', 'status', 'completed')
                    ->whereRelation('document', 'summary_id', $request->summary_id)
                    ->where('aspect_id', $request->aspect_id)
                    ->get();
                foreach ($items as $item) {
                    if (!in_array($item->name, $set)) {
                        $set[] = $item->name;
                        $results[] = ['id' => $item->name, 'text' => $item->name];
                    }
                }
                return response()->json(compact('results', 'more'));

            case 'by_aspect_investigasi':
                $items = $items->where('aspect_id', $request->aspect_id);
                $items = $items->paginate(1000);
                // return $this->responseSelect2($items, 'name', 'name');
                $set = [];
                $results = [];
                $more = false;
                foreach ($items as $item) {
                    $set[] = $item->name;
                    $results[] = ['id' => $item->name, 'text' => $item->name];
                }
                return response()->json(compact('results', 'more'));

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate(100);
        // return $this->responseSelect2($items, 'name', 'id');
        $results = [];
        $more = false;
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->name];
        }
        if (method_exists($items, 'hasMorePages')) {
            $more = $items->hasMorePages();
        }
        return response()->json(compact('results', 'more'));
    }

    public function cityOptions(Request $request)
    {
        return City::when(
            $province_id = $request->province_id,
            function ($q) use ($province_id) {
                $q->where('province_id', $province_id);
            }
        )
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function cityOptionsRoot(Request $request)
    {
        $items = City::when(
            $province_id = $request->province_id,
            function ($q) use ($province_id) {
                $q->where('province_id', $province_id);
            }
        )
            ->orderBy('name', 'ASC')
            ->get();

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function provinceOptionsBySearch($search)
    {
        $items = Province::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function jabatanOptions(Request $request)
    {
        return Position::select('id', 'name')
            ->when(
                $location_id = $request->location_id,
                function ($q) use ($location_id) {
                    $q->where('location_id', $location_id);
                }
            )
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getStruct(Request $request)
    {
        return OrgStruct::when(
            $id = $request->id,
            function ($q) use ($id) {
                $q->where('id', $id);
            }
        )
            ->first();
    }

    public function childStructOptions(Request $request)
    {
        return OrgStruct::select('id', 'name')
            ->when(
                $parent_id = $request->parent_id,
                function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                }
            )
            ->orderBy('name', 'ASC')
            ->get();
    }


    public function penilaianCategoryOptions()
    {
        $items = PenilaianCategory::keywordBy('name')->orderBy('name');
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function getSurveyStatement(Request $request)
    {
        $record = SurveyReg::findOrFail($request->survey_reg_id);
        $summary = $record->summary;
        // $regUser = $record->surveyRegUsers()->whereIn('user_id', $request->user_ids)->firstOrFail();
        $regUser = $record->surveyRegUsers()
            ->where('user_id', $request->user_id)
            ->firstOrFail();
        $survey = $regUser->survey;
        $statements = $survey->statements()->with('category')->get();
        $categories = $statements->pluck('category')->unique();
        return response()->json(
            [
                'data'  => compact(
                    'record',
                    'summary',
                    'regUser',
                    'survey',
                    'statements',
                    'categories',
                )
            ]
        );
    }

    public function selectRiskRating(Request $request)
    {
        $items = RiskRating::keywordBy('name')
            ->orderBy('name');

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectRiskAssessmentRating(Request $request)
    {
        $items = RiskAssessmentRating::with('riskRating')->whereHas('riskRegister', function ($q) {
            $q->where('unit_kerja_id', request()->object);
            $q->whereYear('periode', request()->year);
            $q->where('type_id', request()->type_id);
        });

        $items = $items->paginate();
        $results = [];
        $more = false;
        foreach ($items as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->riskRating->name
            ];
        }
        if (method_exists($items, 'hasMorePages')) {
            $more = $items->hasMorePages();
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectLevelDampak($search, Request $request)
    {
        $items = LevelDampak::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->with('owner')->find($request->id);

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectLevelKemungkinan($search, Request $request)
    {
        $items = LevelKemungkinan::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->with('owner')->find($request->id);

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectMainProcess(Request $request)
    {
        $items = MainProcess::keywordBy('name')
            ->when(
                $subject_id = $request->subject_id,
                function ($q) use ($subject_id) {
                    $q->where('subject_id', $subject_id);
                }
            )
            ->orderBy('name');
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectStatusResiko($search, Request $request)
    {
        $items = RiskStatus::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->with('owner')->find($request->id);

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectDetailApm($search, Request $request)
    {
        $summary_id = $request->summary_id;
        $items = ApmDetail::with('procedureAudit')
            ->whereHas(
                'apm',
                function ($q) use ($summary_id) {
                    $q->where('summary_id', $summary_id);
                }
            )
            ->where('user_id', auth()->id())
            ->keywordBy('agenda')->orderBy('agenda');
        switch ($search) {
            case 'all':
                $items = $items;
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2ProcedureAudit($items, '', 'id');
        // return $this->responseSelect2($items, 'agenda', 'id');
    }

    public function selectDetailApm2(Request $request)
    {
        $summary_id = $request->summary_id;
        $aspect_id = $request->aspect_id;
        $items = ApmDetail::with('procedureAudit')
            ->whereHas(
                'apm',
                function ($q) use ($summary_id) {
                    $q->where('summary_id', $summary_id);
                }
            )
            // ->whereHas(
            //     'procedureAudit',
            //     function ($q) use ($aspect_id) {
            //         $q->where('aspect_id', $aspect_id);
            //     }
            // )
            ->where('user_id', auth()->id())
            ->keywordBy('agenda')->orderBy('agenda');
        $items = $items->paginate();
        return $this->responseSelect2ProcedureAudit($items, '', 'id');
        // return $this->responseSelect2($items, 'agenda', 'id');
    }

    public function selectProcedure(Request $request)
    {
        $items = ProcedureAudit::keywordBy('procedure')
            ->when($request->aspect_ids, function ($q) use ($request) {
                $q->whereIn('aspect_id', $request->aspect_ids);
            })
            ->when($request->objective_id, function ($q) use ($request) {
                $q->where('objective_id', $request->objective_id);
            })
            ->when($request->summary_id, function ($q) use ($request) {
                $summary = Summary::find($request->summary_id);

                if ($summary) {
                    $aspect_ids = [];

                    if ($summary->assignment) {
                        $aspect_ids = $summary->assignment->aspects->pluck('id')->toArray();
                    } elseif ($summary->instruction) {
                        $aspect_ids = $summary->instruction->aspects->pluck('id')->toArray();
                    }

                    $q->whereIn('aspect_id', $aspect_ids);
                }
            })
            ->orderBy('procedure', 'ASC')
            ->orderBy('number', 'ASC')
            ->get();
        $results = [];
        $more = false;
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->number . '. ' . $item->procedure];
        }

        return response()->json(compact('results', 'more'));
    }

    public function selectMemoDocument($search, Request $request)
    {
        $items = Document::keywordBy('no')->orderBy('created_at');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'by_summary':
                $items = $items->where('summary_id', $request->summary_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();

        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->no . ' (' . ($item->date->translatedFormat('d F Y') ?? '') . ')'];
        }
        return response()->json(compact('results', 'more'));
    }

    public function getTingkatResiko(Request $request)
    {
        $record = RiskRegister::with('details.inherentRisk', 'details.currentRisk')
            ->where('status', 'completed')
            ->where('type_id', $request->type_id)
            ->where('object_id', $request->subject_id)
            ->orderBy('periode', 'DESC')
            ->first();

        $scores = [];
        if (!empty($record->details)) {
            foreach ($record->details as $detail) {
                // $scores[] = $detail->inherentRisk->total_impact * $detail->inherentRisk->total_likehood;
                $scores[] = $detail->currentRisk->total_impact * $detail->currentRisk->total_likehood;
            }
            $result = max($scores);
        } else {
            $result = 0;
        }

        return response()->json(compact('record', 'result'));
    }

    public function unitKerja(Request $request)
    {
        $items = DepartmentAuditee::with('departments')
            ->where('subject_id', $request->subject_id)
            ->where('year', $request->year)
            // ->whereYear('year', $request->year)
            ->get();

        return $items;
    }
}
