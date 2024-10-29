<?php

namespace App\Models\Master\Document;

use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Procedure\ProcedureAudit;
use App\Models\Model;
use App\Models\Traits\HasFiles;

class AuditReference extends Model
{
    use HasFiles;

    protected $table = 'ref_audit_reference';

    protected $fillable = [
        'aspect_id',
        'procedure_id',
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
    public function aspect()
    {
        return $this->belongsTo(Aspect::class, 'aspect_id');
    }

    public function procedure()
    {
        return $this->belongsTo(ProcedureAudit::class, 'procedure_id');
    }


    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->has('aspect')->latest();
    }

    public function scopeFilters($query)
    {
        $type_id = request()->get('type_id') ?? NULL;
        $object = request()->get('subject');

        return $query->filterBy(['name', 'description', 'aspect_id'])
            ->filterBy('aspect_id', '=')
            ->whereHas(
                'aspect',
                function ($q) use ($type_id, $object) {
                    $q
                        ->when(
                            $type_id,
                            function ($qq) use ($type_id) {
                                $qq->whereRelation('subject', 'type_id', $type_id);
                            }
                        )
                        ->when(
                            $object,
                            function ($qq) use ($type_id, $object) {
                                $qq->where('object_id', $object);
                            }
                        );
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
            $this->save();
            $this->saveLogNotify();
            $this->saveFilesByTemp($request->attachments, $request->module, 'attachments');

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
