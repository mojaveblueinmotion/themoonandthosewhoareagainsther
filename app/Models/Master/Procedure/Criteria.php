<?php

namespace App\Models\Master\Procedure;

use App\Imports\Master\ProcedureAuditImport;
use App\Models\Conducting\Kka\KkaSampleDetail;
use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Preparation\Apm\ApmDetail;
use App\Models\Preparation\Document\DocumentDetail;
use App\Models\Preparation\LangkahKerja\LangkahKerjaDetail;

class Criteria extends Model
{
    protected $table = 'ref_criteria';

    protected $fillable = [
        'aspect_id',
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
    public function procedures()
    {
        return $this->hasMany(ProcedureAudit::class, 'criteria_id');
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
        $type_id = request()->get('type_id') ?? NULL;
        $object = request()->get('object');

        return $query->filterBy(['name', 'description']);
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
        if ($this->procedures()->exists()) return false;

        return true;
    }
}
