<?php

namespace App\Models\Master\Procedure;

use App\Imports\Master\ProcedureAuditImport;
use App\Models\Conducting\Kka\KkaSampleDetail;
use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Document\AuditReference;
use App\Models\Master\Objective\Objective\Objective;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Preparation\Apm\ApmDetail;
use App\Models\Preparation\Document\DocumentDetail;
use App\Models\Preparation\LangkahKerja\LangkahKerjaDetail;

class ProcedureAudit extends Model
{
    protected $table = 'ref_procedure_audit';

    protected $fillable = [
        'aspect_id',
        'objective_id',
        'procedure',
        'description',
        'mandays',
        'number'
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

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }

    public function apmDetails()
    {
        return $this->hasMany(ApmDetail::class, 'agenda');
    }

    public function references()
    {
        return $this->hasMany(AuditReference::class, 'procedure_id');
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
        return $query->filterBy(['name', 'aspect_id'])
            ->when($id = request()->post('name'), function ($qq) use ($id) {
                $qq->where('name', $id);
            })
            ->when(request()->get('type_id'), function ($q) {
                $q->whereHas('aspect', function ($qq){
                    $qq->whereHas('subject', function ($qqq){
                        $qqq->whereHas('typeAudit', function ($qqqq){
                            $qqqq->where('id', request()->get('type_id'));
                        });
                    });
                });
            })
            ->when(request()->get('subject'), function ($q) {
                $q->whereHas('aspect', function ($qq){
                    $qq->whereHas('subject', function ($qqq){
                        $qqq->where('id', request()->get('subject'));
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
            $closedWhenSubmit = 0;
            if (!empty($this->created_at)) {
                $closedWhenSubmit = 1;
            }

            $this->fill($request->all());
            // if ($request->type_id == 2) {
            //     $this->object_type  = 'provider';
            // } else {
            //     $this->object_type = Aspect::find($this->aspect_id)->object_type;
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
        $data = $this->procedure;
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
        if ($this->apmDetails()->exists()) return false;

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
