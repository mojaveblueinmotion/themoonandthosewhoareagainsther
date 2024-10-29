<?php

namespace App\Models\RiskAssessment;

use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Risk\LevelDampak;
use App\Models\Master\Risk\LevelKemungkinan;
use App\Models\Master\Risk\RiskRating;
use App\Models\Model;
use App\Models\RiskAssessment\CurrentRiskDetail;
use App\Models\Rkia\Summary;
use App\Models\Traits\HasApprovals;
use Carbon\Carbon;

class CurrentRisk extends Model
{
    use HasApprovals;

    protected $table = 'trans_current_risk';

    protected $fillable = [
        'risk_register_id',
        'risk_register_detail_id',

        // Likelihood
        'complexity',
        'volume',
        'known_issue',
        'chaning_process',
        'total_likehood',

        // Impact
        'materiality',
        'legal',
        'operational',
        'total_impact',

        'approval_id',
        'status',
        'upgrade_reject',
        'version',
    ];

    protected $casts = [];

    /*******************************
     ** MUTATOR
     *******************************/
    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function details()
    {
        return $this->hasMany(CurrentRiskDetail::class, 'current_risk_id');
    }

    public function riskRegister()
    {
        return $this->belongsTo(RiskRegister::class, 'risk_register_id');
    }

    public function riskRegisterDetail()
    {
        return $this->belongsTo(RiskRegisterDetail::class, 'risk_register_detail_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['current_risk_id', '='])
            ->whereHas('riskRegister', function ($query) {
                $query->filters();
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
            $this->riskRegisterDetail->update(
                [
                    'notes' => $request->notes,
                    'condition' => $request->condition,
                ]
            );
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
            $this->fill($request->all());
            $menu = \App\Models\Globals\Menu::where('module', 'risk-assessment.current-risk')->first();
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
            $this->status = $request->is_submit ? 'waiting.approval' : 'draft';
            $this->save();
            $this->riskRegisterDetail->update(
                [
                    'notes' => $request->notes,
                    'condition' => $request->condition,
                ]
            );
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
                    $this->update(['status' => 'draft']);
                    $this->update(
                        [
                            'status' => 'draft',
                            'version' => $this->version + 1,
                            'upgrade_reject' => null,
                        ]
                    );
                }
            } else {
                $this->approveApproval($request->module);
                if ($this->firstNewApproval($request->module)) {
                    $this->update(['status' => 'waiting.approval']);
                } else {
                    $this->update(['status' => 'completed']);
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

    public function saveLogNotify()
    {
        $data = 'Tahun ' . $this->riskRegister->periode->format('Y') . ' Unit Kerja ' . $this->riskRegister->subject->name;
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
                return $checkStatus && $user->checkPerms($perms . '.delete');

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
                if ($cek = Summary::where('type_id', $this->riskRegister->type_id)->where('object_id', $this->riskRegister->object_id)->whereHas('rkia', function ($q) {
                    $q->where('year', $this->riskRegister->periode);
                })->first()) {
                    if ($cek->rkia->status == 'completed') {
                        $checkStatus = false;
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
}
