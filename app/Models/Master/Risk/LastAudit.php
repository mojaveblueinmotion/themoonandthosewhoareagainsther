<?php

namespace App\Models\Master\Risk;

use App\Imports\Master\LastAuditImport;
use App\Models\Globals\TempFiles;
use App\Models\Master\Org\DepartmentAuditee;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Model;
use App\Models\Reporting\Lha\Lha;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;

class LastAudit extends Model
{
    use HasFiles;

    protected $table = 'ref_last_audits';

    protected $fillable = [
        'lhp_id',
        'code',
        'date',
        'year',
        'type_id',
        'object_id',
        'department_auditee_id',
    ];

    protected $casts = [
        'date'  => 'date',
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    public function setRatingAttribute($value = '')
    {
        $ratings = $this->getRatings();
        $keys = array_keys($ratings);
        $texts = array_values($ratings);
        $this->attributes['rating'] = str_replace($texts, $keys, $value);
    }
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/
    public function getShowCategoryAttribute()
    {
        return $this->type->name ?? null;
    }

    /*******************************
     ** RELATION
     *******************************/
    function lhp()
    {
        return $this->belongsTo(Lha::class, 'lhp_id');
    }
    public function type()
    {
        return $this->belongsTo(TypeAudit::class, 'type_id');
    }

    public function subject()
    {
        return $this->belongsTo(OrgStruct::class, 'object_id');
    }
    public function deptAuditee()
    {
        return $this->belongsTo(DepartmentAuditee::class, 'department_auditee_id');
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
        $subject = request()->get('subject');

        return $query->filterBy(['year'])
            ->when(
                $type_id,
                function ($q) use ($type_id) {
                    $q->where('type_id', $type_id);
                }
            )
            ->when(
                $subject,
                function ($q) use ($type_id, $subject) {
                    $q->where('object_id', $subject);
                }
            )
            ->when(request()->get('auditee_id'), function ($q) {
                $q->whereHas('subject', function ($qq) {
                    $qq->whereHas('deptartmentAuditee', function ($qqq) {
                        $qqq->whereHas('departments', function ($qqqq) {
                            $qqqq->where('department_id', request()->get('auditee_id'));
                        });
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
            $this->fill($request->all());
            $this->object_id = $request->object_id;
            $this->save();
            $this->saveFiles($request);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveFiles($request)
    {
        $this->saveFilesByTemp($request->attachments, $request->module, 'attachments');
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

            \Excel::import(new LastAuditImport(), $file);

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = '';
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
    public function labelCategory()
    {
        return $this->type->name;
    }

    public function getsubjectLevel()
    {
        if ($this->type_id == 2) {
            return 'provider';
        }
        $subject = $this->subject;
        return $subject->level ?? null;
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
