<?php

namespace App\Models\Auth;

use App\Models\Auth\User;
use App\Models\Globals\Approval;
use App\Models\Globals\ApprovalDetail;
use App\Models\Globals\MenuFlow;
use App\Models\Globals\TempFiles;
use App\Models\Traits\HasFiles;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use RaidModel, Utilities, ResponseTrait;
    use HasFiles;

    /** SCOPE **/
    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeFilters($query)
    {
        return $query->filterBy('name');
    }

    /** RELATIONS **/
    public function menuFlows()
    {
        return $this->hasMany(MenuFlow::class, 'role_id');
    }

    /** SAVE DATA **/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $this->name = $request->name;
            $this->save();
            $this->saveLogNotify();
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            if (!$this->canDeleted()) {
                return $this->rollback(__('base.error.related'));
            }
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleGrant($request)
    {
        $this->beginTransaction();
        try {
            $this->syncPermissions($request->check ?? []);
            $this->touch();
            $this->save();
            $this->saveLogNotify();
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return $this->commitSaved(['redirectTo' => rut($request->routes . '.index')]);
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function getUsersRaw()
    {
        $str = '';
        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                    <div class="symbol-group symbol-hover">';
        $overName = '';
        $overCount = 0;
        foreach ($this->users as $i => $member) {
            $overCount++;
            $overName .= '<b>' . $member->name . '</b><br>';
        }
        $str .= '<div class="symbol symbol-30 symbol-circle symbol-light-success"
                data-toggle="tooltip" title="' . $overName . '"
                data-html="true" data-placement="right">
                <span class="symbol-label font-weight-bold">' . $overCount . '</span>
            </div>';
        $str .= '
                    </div>
                </div>';
        return $str;
    }

    public function saveLogNotify()
    {
        $data = $this->name;
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
            case $routes . '.grant':
                $this->addLog('Mengubah Hak Akses Role ' . $data);
                break;
            case $routes . '.importSave':
                auth()->user()->addLog('Import Data Hak Akses Role');
                break;
        }
    }

    /** OTHER FUNCTION **/
    public function canDeleted()
    {
        if (in_array($this->id, [1, 2])) return false;
        if ($this->users()->exists()) return false;
        if ($this->menuFlows()->exists()) return false;
        if (ApprovalDetail::where('role_id', $this->id)->exists()) return false;

        return true;
    }

    public function checkAction($user, $action, $perms)
    {
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                return $user->checkPerms($perms . '.edit');

            case 'delete':
                return $user->checkPerms($perms . '.delete') && $this->canDeleted();
        }
    }
}
