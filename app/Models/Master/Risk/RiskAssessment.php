<?php

namespace App\Models\Master\Risk;

use App\Imports\Master\RiskAssessmentImport;
use App\Models\Globals\TempFiles;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Model;

class RiskAssessment extends Model
{
    protected $table = 'ref_risk_assessments';

    protected $fillable = [
        'year',
        'type_id',
        'object_id',
        'key',
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
    public function type()
    {
        return $this->belongsTo(TypeAudit::class, 'type_id');
    }
    public function object()
    {
        return $this->belongsTo(OrgStruct::class, 'object_id');
    }

    public function details()
    {
        return $this->hasMany(RiskAssessmentDetail::class, 'risk_assessment_id');
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
        $type_id = request()->get('type_id');
        $object = request()->get('object');

        return $query->filterBy(['year'])
                    ->when($type_id, function ($q) use ($type_id) {
                        $q->where('type_id', $type_id);
                    })
                    ->when($object, function ($q) use ($type_id, $object) {
                        $q->where('object_id', $object);
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
            $this->object_id = $request->object_id;
            $this->save();

            $details_ids = [];
            foreach ($request->details as $val) {
                $detail = $this->details()->firstOrNew(['id' => $val['id'] ?? 0]);
                $detail->fill($val);
                $this->details()->save($detail);

                $details_ids[] = $detail->id;
            }
            $this->details()->whereNotIn('id', $details_ids)->delete();

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

            \Excel::import(new RiskAssessmentImport(), $file);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = $this->key;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes.'.store':
                $this->addLog('Membuat Data '.$data);
                break;
            case $routes.'.update':
                $this->addLog('Mengubah Data '.$data);
                break;
            case $routes.'.destroy':
                $this->addLog('Menghapus Data '.$data);
                break;
        }
    }

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
    public function canDeleted()
    {
        switch (true) {

        }
        return true;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getObjectId()
    {
        return $this->getObject()->id ?? 0;
    }

    public function getObjectName()
    {
        return $this->getObject()->name ?? null;
    }
}
