<?php

namespace App\Models\Master\Org;

use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Risk\LastAudit;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Model;
use App\Models\RiskAssessment\RiskRegister;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepartmentAuditee extends Model
{
    protected $table = "ref_department_auditee";

    protected $fillable = [
        'year',
        'subject_id',
        'type_id',
        'name',
        'description',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function lastAudits()
    {
        return $this->hasMany(LastAudit::class, 'department_auditee_id');
    }
    public function subject()
    {
        return $this->belongsTo(OrgStruct::class, 'subject_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeAudit::class, 'type_id');
    }

    public function departments()
    {
        return $this->belongsToMany(OrgStruct::class, 'ref_department_auditee_dept', 'auditee_id', 'department_id');
    }

    public function riskRegisters()
    {
        return $this->hasMany(RiskRegister::class, 'department_auditee_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['year', 'subject_id', 'type_id']);
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $this->fill($request->all());
            $this->save();
            $this->departments()->sync($request->departments ?? []);
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (Exception $e) {
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
        }
    }

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
    public function getDepartments()
    {
        $str = '';
        $departments = $this->departments;
        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                    <div class="symbol-group symbol-hover">';
        $overName = '';
        $overCount = 0;
        foreach ($departments as $i => $department) {
            $overCount++;
            $overName .= '<b>' . $i + 1 . '. ' . $department->name . '</b><br>';
        }
        $str .= '<div class="symbol symbol-30 symbol-circle symbol-light-success"
                data-toggle="tooltip" title="' . $overName . '"
                data-html="true" data-placement="right">
                <span class="symbol-label font-weight-bold">' . $overCount . '</span>
            </div>';
        $str .= '
                    </div>
                </div>';
        return $overName;
    }


    public function canDeleted()
    {
        if (RiskRegister::where('object_id', $this->subject_id)
            ->whereYear('periode', $this->year)
            ->exists()
        ) return false;
        if ($this->lastAudits()->exists()) return false;
        return true;
    }

    public function checkAction($user, $action, $perms)
    {
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                return $user->checkPerms($perms . '.edit') && $this->canDeleted();;

            case 'delete':
                return $user->checkPerms($perms . '.delete') && $this->canDeleted();
        }
    }
}
