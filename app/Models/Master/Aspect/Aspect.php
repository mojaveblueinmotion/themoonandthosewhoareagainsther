<?php

namespace App\Models\Master\Aspect;

use App\Models\Globals\TempFiles;
use App\Models\Master\Document\AuditReference;
use App\Models\Master\Document\DocumentItem;
use App\Models\Master\Objective\Objective\Objective;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Procedure\ProcedureAudit;
use App\Models\Master\Risk\MainProcess;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Model;
use App\Models\Preparation\Assignment\Assignment;

class Aspect extends Model
{
    protected $table = 'ref_aspects';

    protected $fillable = [
        'object_id',
        'main_process_id',
        'name',
        'description'
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/
    public function getShowCategoryAttribute()
    {
        return $this->type->name ?? '';
    }

    /*******************************
     ** RELATION
     *******************************/
    public function subject()
    {
        return $this->belongsTo(OrgStruct::class, 'object_id');
    }

    public function mainProcess()
    {
        return $this->belongsTo(MainProcess::class, 'main_process_id');
    }

    public function auditReferences() {
        return $this->hasMany(AuditReference::class, 'aspect_id');
    }

    public function procedures()
    {
        return $this->hasMany(ProcedureAudit::class, 'aspect_id');
    }

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'trans_assignments_aspects', 'aspect_id', 'assignment_id');
    }

    public function objective()
    {
        return $this->hasMany(Objective::class, 'aspect_id');
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
        $subject = request()->get('subject');

        return $query->filterBy(['name', 'main_process_id'])
            ->when(
                $type_id = request()->get('type_id'),
                function ($q) use ($type_id) {
                    $q->whereRelation('subject', 'type_id', $type_id);
                }
            )
            ->when(
                $subject,
                function ($q) use ($type_id, $subject) {
                    $q->where('object_id', $subject);
                }
            );
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $closedWhenSubmit = 0;
            if (!empty($this->created_at)) {
                $closedWhenSubmit = 1;
            }
            $this->fill($request->all());
            // if ($request->type_id == 2) {
            //     $this->object_type = 'provider';
            // $this->subject_id = $request->subject_id;
            // } else {
            //     $this->object_type = OrgStruct::find($this->subject_id)->level;
            // }
            $this->save();
            $this->saveLogNotify();

            if ($closedWhenSubmit == 1) {
                return $this->commitSaved();
            } else {
                return $this->commitStateStill();
            }
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
        if ($this->objective()->exists()) return false;

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
