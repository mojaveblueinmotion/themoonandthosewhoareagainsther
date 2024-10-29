<?php

namespace App\Models\Traits;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Globals\Approval;
use App\Models\Globals\ApprovalDetail;
use App\Models\Globals\MenuFlow;

trait HasApprovals
{
    /** Approval by all module **/
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'target');
    }

    /** Approval by specific module **/
    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }

    function getLatestApprovalId() {
        return $this->approvals()
        ->where('module', 'NOT LIKE', '%_upgrade')
        ->orderBy('id', 'DESC')->first()->id ?? null;
    }

    /** Use this function when submit **/
    public function generateApproval($module, $upgrade = false)
    {
        // if ($this->approval()->where('module', $module . ($upgrade ? '_upgrade' : ''))->exists()) {
        //     // return $this->resetStatusApproval($module . $upgrade ? '_upgrade' : '');
        //     $this->approval()->where('module', $module . ($upgrade ? '_upgrade' : ''))->first()->details()->delete();
        //     $this->approval()->where('module', $module . ($upgrade ? '_upgrade' : ''))->delete();
        // }
        $flows = MenuFlow::whereRelation('menu', 'module', $module)
            ->orderBy('order')
            ->get();

        if (!$flows->count()) {
            return $this->responseError(
                [
                    'message' => 'Flow Approval belum diatur!'
                ]
            );
        }

        $approval = new Approval(
            [
                'target_type' => self::class,
                'target_id' => $this->id,
                'version' => $this->version,
                'module' => $module . ($upgrade ? '_upgrade' : ''),
                'status' => 'new',
            ]
        );
        $approval->save();
        $this->approval_id = $approval->id;
        $this->save();
        foreach ($flows as $item) {
            $approvalDetail = new ApprovalDetail(
                [
                    'approval_id' => $approval->id,
                    'role_id' => $item->role_id,
                    'order' => $item->order,
                    'type' => $item->type,
                    'status' => 'new',
                ]
            );
            $approvalDetail->save();
        }

        // $this->approval($module)->saveMany($results);
        return null;
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $this->update(
                [
                    'status' => 'waiting.approval',
                ]
            );
            $this->generateApproval($request->module);
            $this->update();
            $this->_generateReport('waiting.approval');
            // $this->saveLogNotify();
            $this->addLog('Submit Data ' . $this->getLogMessage());
            $this->addNotify(
                [
                    'message' => 'Waiting Approval ' . $this->getLogMessage(),
                    'url' => route(request()->get('routes') . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval($request->module, $this->getStructId()),
                ]
            );
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
            $this->update(
                [
                    'status' => 'waiting.approval.revisi',
                    'change_req_by' => auth()->id(),
                    'change_req_at' => now(),
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

    public function handleReject($request)
    {
        $this->beginTransaction();
        try {
            if ($this->status === 'waiting.approval.revisi') {
                // dd(
                //     $this->version,
                //     $this::class,
                //     $this->id,
                //     $request->module,
                //     str_replace('_upgrade', '', $request->module)
                // );
                $this->update(
                    [
                        'status' => 'completed',
                        'approval_id' => Approval::where('version', $this->version)
                            ->where('target_type', $this::class)
                            ->where('target_id', $this->id)
                            ->where('module', str_replace('_upgrade', '', $request->module))
                            ->orderByDesc('created_at')
                            ->first()
                            ->id
                    ]
                );
                $this->saveLogNotify();
            } else {
                $this->rejectApproval($request->module, $request->note);
                $this->update(['status' => 'rejected']);
                $this->saveLogNotify();
                $this->_generateReport('rejected');
            }

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function resetStatusApproval($module)
    {
        return $this->approval
            ->details()
            ->update(
                [
                    'status' => 'new',
                    'user_id' => null,
                    'position_id' => null,
                    'note' => null,
                    'approved_at' => null,
                ]
            );
    }

    /** Use this function before submit **/
    public function getFlowApproval($module)
    {
        return MenuFlow::whereHas(
            'menu',
            function ($q) use ($module) {
                $q->where('module', $module);
            }
        )
            ->orderBy('order')
            ->get()
            ->groupBy('order');
    }

    public function rejected($module)
    {
        if ($this->approval) {
            return $this->approval->details()->whereStatus('rejected')->latest()->first();
        }
    }

    public function approved($module)
    {
        if ($this->approval) {
            return $this->approval->details()->whereStatus('approved')->get();
        }
    }

    public function firstNewApproval($module)
    {
        if ($this->approval) {
            return $this->approval->details()->whereStatus('new')->orderBy('order')->first();
        }
    }

    /** Check auth user can action approval by specific module **/
    public function checkApproval($module)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval
                ->details()
                ->where('order', $new->order)
                ->whereStatus('new')
                ->whereIn('role_id', $user->getRoleIds())
                ->exists();
        }

        return false;
    }

    public function getNewUserIdsApproval($module)
    {
        $role_ids = [];
        $isLocation = false;
        if ($new = $this->firstNewApproval($module)) {
            $role_ids = $this->approval
                ->details()
                ->where('order', $new->order)
                ->whereStatus('new')
                ->pluck('role_id')
                ->toArray();
        }
        return User::whereHas('roles', function ($q) use ($role_ids, $isLocation) {
            $q->whereIn('id', $role_ids);
        })
            ->pluck('id')
            ->toArray();
    }

    /** Reject data by specific module by specific module **/
    public function rejectApproval($module, $note)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval
                ->details()
                ->where('order', $new->order)
                ->whereStatus('new')
                ->whereIn('role_id', $user->getRoleIds())
                ->update([
                    'status' => 'rejected',
                    'user_id' => $user->id,
                    'position_id' => $user->position_id,
                    'note' => $note,
                    'approved_at' => null,
                ]);
        }
    }

    /** Approve data by specific module **/
    public function approveApproval($module, $note = null)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval
                ->details()
                ->where('order', $new->order)
                ->whereStatus('new')
                ->whereIn('role_id', $user->getRoleIds())
                ->update([
                    'status' => 'approved',
                    'user_id' => $user->id,
                    'position_id' => $user->position_id,
                    'note' => $note,
                    'approved_at' => now(),
                ]);
        }
    }
}
