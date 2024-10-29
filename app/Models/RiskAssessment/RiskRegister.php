<?php

namespace App\Models\RiskAssessment;

use App\Models\Auth\User;
use App\Models\Master\Org\DepartmentAuditee;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Master\Subject\Subject;
use App\Models\Model;
use App\Models\RiskAssessment\InherentRisk;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Models\Traits\HasApprovals;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class RiskRegister extends Model
{
    use HasApprovals;

    protected $table = 'trans_risk_assessment_register';

    protected $fillable = [
        'type_id',
        'periode',
        'object_id',
        'department_auditee_id',
        'sasaran',

        'approval_id',
        'status',
        'upgrade_reject',
        'version',
    ];

    protected $casts = [
        'periode' => 'datetime',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    public function setPeriodeAttribute($value)
    {
        $this->attributes['periode'] = Carbon::createFromFormat('d/m/Y', '01/01/' . $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function details()
    {
        return $this->hasMany(RiskRegisterDetail::class, 'risk_register_id');
    }

    public function subject()
    {
        return $this->belongsTo(OrgStruct::class, 'object_id');
    }

    public function departmentAuditee()
    {
        return $this->belongsTo(DepartmentAuditee::class, 'department_auditee_id');
    }

    public function residualRisk()
    {
        return $this->hasMany(CurrentRisk::class, 'risk_register_id');
    }

    public function inherentRisk()
    {
        return $this->hasMany(InherentRisk::class, 'risk_register_id');
    }

    public function riskRating()
    {
        return $this->hasOne(RiskRating::class, 'risk_assessment_register_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeAudit::class, 'type_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeGridCurrentRiskCompleted($query)
    {
        return $query
            ->where('status', 'completed')
            ->whereHas('details', function ($q) {
                $q->gridInherentRiskCompleted();
            });
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['object_id', 'type_id'])
            ->when(request()->get('periode'), function ($q) {
                $periode = request()->get('periode');
                $periode = Carbon::createFromFormat('d/m/Y', '01/01/' . $periode);
                $periode = Carbon::parse($periode)->format('Y-m-d');

                $q->where('periode', $periode);
            })
            ->when(request()->get('auditee_id'), function ($q) {
                $q->whereHas('subject', function ($qq) {
                    $qq->whereHas('deptartmentAuditee', function ($qqq) {
                        $qqq->whereHas('departments', function ($qqqq) {
                            $qqqq->where('department_id', request()->get('auditee_id'));
                        });
                    });
                });
            });
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $this->fill($request->all());
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $this->riskRating()->delete();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $menu = \App\Models\Globals\Menu::where('module', 'risk-assessment.risk-register')->first();
            if (count($this->details) == 0) {
                return $this->rollback(
                    [
                        'message' => 'Pastikan Detail Risk Register Terisi!'
                    ]
                );
            }
            if ($request->is_submit == 1) {
                if ($menu->flows()->get()->groupBy('order')->count() == null) {
                    return $this->rollback(
                        [
                            'message' => 'Belum Ada Alur Persetujuan!'
                        ]
                    );
                }
                $this->generateApproval($request->module);
            }
            $this->fill($request->all());
            $this->status = $request->is_submit ? 'waiting.approval' : 'draft';
            $this->save();
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleReject($request)
    {
        $this->beginTransaction();
        try {
            if ($this->status === 'waiting.approval.revisi') {
                $this->update(
                    [
                        'status' => 'completed',
                        'upgrade_reject' => $request->note,
                        'approval_id' => $this->getLatestApprovalId(),
                    ]
                );
            } else {
                $this->rejectApproval($request->module, $request->note);
                $this->update(['status' => 'rejected']);
            }

            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleApprove($request)
    {
        $this->beginTransaction();
        try {
            if ($this->status === 'waiting.approval.revisi') {
                $this->approveApproval($request->module . '_upgrade');
                if ($this->firstNewApproval($request->module . '_upgrade')) {
                    $this->update(['status' => 'waiting.approval.revisi']);
                } else {
                    $this->update(
                        [
                            'status' => 'draft',
                            'version' => $this->version + 1,
                            'upgrade_reject' => null,
                        ]
                    );
                    foreach ($this->details as $detail) {
                        $detail->inherentRisk()->delete();
                    }
                }
            } else {
                $this->approveApproval($request->module);
                if ($this->firstNewApproval($request->module)) {
                    $this->update(['status' => 'waiting.approval']);
                } else {
                    $this->update(['status' => 'completed']);
                    foreach ($this->details as $detail) {
                        $inherentRisk = InherentRisk::firstOrNew(
                            [
                                'risk_register_detail_id' => $detail->id,
                                'risk_register_id' => $this->id,
                                'status' => 'new',
                            ]
                        );
                        $inherentRisk->save();
                    }
                }
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleRevisi($request)
    {
        $this->beginTransaction();
        try {
            $flowApproval = $this->getFlowApproval($request->module);
            if ($flowApproval->count() == 0) {
                return $this->rollback(
                    [
                        'message' => 'Data Flow Approval tidak tersedia!'
                    ]
                );
            }

            $this->update(
                [
                    'status' => 'waiting.approval.revisi',
                    // 'version' => $this->version+1, //versi diubah saat diapprove
                ]
            );
            $this->generateApproval($request->module, true);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }



    public function getLevel($score)
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overName = '';
        $overColor = '';
        $overHover = '';
        if ($score < 5) {
            $overName = 'Low Risk';
            $overColor = 'success';
        } elseif ($score > 10) {
            $overName = 'High Risk';
            $overColor = 'danger';
        } else {
            $overName = 'Medium Risk';
            $overColor = 'warning';
        }
        $overHover .= $score;

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-40 symbol-square symbol-light-' . $overColor . '"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold" style="color:' . $overColor . ';">' . $overHover . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function saveLogNotify()
    {
        $data = 'Tahun ' . $this->periode->format('Y') . ' Subjek Audit ' . $this->subject->name;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.update':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.detailStore':
                $this->addLog('Membuat Detail Data ' . $data);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.submitSave':
                $this->addLog('Submit Data ' . $data);
                $this->addNotify([
                    'message' => 'Waiting Approval ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.approve':
                if (in_array($this->status, ['draft', 'waiting.approval.revisi'])) {
                    $this->addLog('Menyetujui Revisi ' . $data);

                    $this->addNotify([
                        'message' => 'Waiting Approval Revisi ' . $data,
                        'url' => route($routes . '.approval', $this->id),
                        'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                    ]);
                } else {
                    $this->addLog('Menyetujui Data ' . $data);
                    $this->addNotify([
                        'message' => 'Waiting Approval ' . $data,
                        'url' => route($routes . '.approval', $this->id),
                        'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                    ]);
                }
                break;
            case $routes . '.reject':
                if (in_array($this->status, ['rejected'])) {
                    $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));

                    $this->addNotify([
                        'message' => 'Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'),
                        'url' => route($routes . '.show', $this->id),
                        'user_ids' => [$this->created_by],
                    ]);
                } else {
                    $this->addLog('Menolak Revisi Data ' . $data . ' dengan alasan: ' . request()->get('note'));

                    $this->addNotify([
                        'message' => 'Menolak Revisi Data ' . $data . ' dengan alasan: ' . request()->get('note'),
                        'url' => route($routes . '.show', $this->id),
                        'user_ids' => [$this->created_by],
                    ]);
                }

                break;

            case $routes . '.revisi':
                $this->addLog('Revisi ' . $data);
                $this->addNotify([
                    'message' => 'Waiting Approval Revisi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module') . "_upgrade"),
                ]);
                break;
        }
    }

    /** OTHER FUNCTIONS **/
    public function checkAction($action, $perms)
    {
        $user = auth()->user();

        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $this->details()->count() == 0 && $user->checkPerms($perms . '.delete');

            case 'detailCreate':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.create');

            case 'detailEdit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                // $cek = in_array(request()->route()->getName(), ['RiskAssessment.RiskAssessment-reg.detail']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'detailDelete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                // $cek = in_array(request()->route()->getName(), ['RiskAssessment.RiskAssessment-reg.detail']);
                return $checkStatus && $user->checkPerms($perms . '.delete');

            case 'detailShow':
                $checkStatus = ($this->status);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'submit':
                $checkStatus = in_array($this->status, ['new', 'draft']);
                $checkDetail = $this->details()->exists();
                return $checkStatus && $checkDetail && $user->checkPerms($perms . '.create');

            case 'approval':
                if ($this->status === 'waiting.approval.revisi') {
                    if ($this->checkApproval(request()->get('module') . '_upgrade')) {
                        return $user->checkPerms($perms . '.approve');
                    }
                }
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;

            case 'tracking':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'print':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed', 'waiting.approval.revisi']);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'revisi':
                $checkStatus = in_array($this->status, ['completed']);
                if ($checkDetail = $this->details) {
                    foreach ($this->details as $detail) {
                        if ($detail->inherentRisk) {
                            if ($detail->inherentRisk->status == 'completed') {
                                $checkStatus = false;
                            }
                        }
                    }
                }
                return $checkStatus;
                break;
        }
        return false;
    }

    public function canDeleted()
    {
        // if($this->moduleRelations()->exists()) return false;
        return true;
    }

    public function getSasaranRaw()
    {
        $text_content = $this->sasaran;

        // Count the number of words in the project name
        $wordCount = str_word_count($text_content);

        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

        // Adjust the width and height for a rectangular shape
        $str .= '<div class="symbol symbol-rect symbol-light-success"
                data-toggle="tooltip" title="' . $text_content . '"
                data-html="true" data-placement="right"
                style="width: 80px; height: 30px;">
                <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
            </div>';

        $str .= '
                </div>
            </div>';

        return $str;
    }

    public function getPeristiwaRaw()
    {
        $text_content = $this->peristiwa;

        // Count the number of words in the project name
        $wordCount = str_word_count($text_content);

        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

        // Adjust the width and height for a rectangular shape
        $str .= '<div class="symbol symbol-rect symbol-light-success"
                data-toggle="tooltip" title="' . $text_content . '"
                data-html="true" data-placement="right"
                style="width: 80px; height: 30px;">
                <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
            </div>';

        $str .= '
                </div>
            </div>';

        return $str;
    }

    public function generateIdResiko()
    {
        $year = $this->periode->format('Y');

        // Fetch the last record for the current year
        $lastRecord = RiskRegisterDetail::whereYear('created_at', $year)
            ->orderBy('id_resiko', 'desc')
            ->first();

        if ($lastRecord) {
            $lastId = $lastRecord->id_resiko;
            $lastNumber = (int) substr($lastId, 5);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // If no records exist for the current year, start with 001
            $newNumber = '001';
        }

        return $year . '-' . $newNumber;
    }
}
