<?php

namespace App\Models\Master\Risk;

use App\Imports\Master\TypeAuditImport;
use App\Models\Globals\TempFiles;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;

class TypeAudit extends Model
{
    protected $table = 'ref_type_audit';

    protected $fillable = [
        'name',
        'description',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/
    function getShowNameAttribute()
    {
        $colorMap = [
            1 => 'warning',
            3 => 'primary',
            2 => 'success',
            4 => 'danger',
            5 => 'info',
            6 => 'secondary',
        ];

        $defaultColor = 'info';

        $typeAuditId = $this->id;

        if ($typeAuditId && isset($colorMap[$typeAuditId])) {
            return \Base::makeLabel($this->name, $colorMap[$typeAuditId]);
        }

        return \Base::makeLabel($this->name, $defaultColor);
    }

    /*******************************
     ** RELATION
     *******************************/
    public function riskAssessmentDetail()
    {
        return $this->hasMany(RiskAssessmentDetail::class, 'risk_rating_id');
    }

    public function subjects()
    {
        return $this->hasMany(OrgStruct::class, 'type_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name', 'description']);
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

    public function handleImport($request)
    {
        $this->beginTransaction();
        try {
            $file = TempFiles::getFileById($request->uploads['temp_files_ids'][0]);
            if (!$file) throw new \Exception('MESSAGE--File tidak tersedia!', 1);

            \Excel::import(new TypeAuditImport(), $file);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
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
    public function canDeleted()
    {
        // if (in_array($this->id, [1, 2, 3])) return false;
        if ($this->subjects()->exists()) return false;
        if ($this->riskAssessmentDetail()->exists()) return false;
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
