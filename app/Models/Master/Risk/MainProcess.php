<?php

namespace App\Models\Master\Risk;

use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Reporting\Lha\LhaResult;
use Exception;

class MainProcess extends Model
{
    protected $table = 'ref_main_process';

    protected $fillable = [
        'subject_id',
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
    function subject()
    {
        return $this->belongsTo(OrgStruct::class, 'subject_id');
    }

    public function aspect()
    {
        return $this->hasMany(Aspect::class, 'main_process_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query
            ->with('subject')
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name', 'subject_id'])
            ->when($id = request()->post('name'), function ($qq) use ($id) {
                $qq->where('name', $id);
            })
            ->when(request()->get('type_id'), function ($q) {
                $q->whereHas('subject', function ($qq){
                    $qq->whereHas('typeAudit', function ($qqq){
                        $qqq->where('id', request()->get('type_id'));
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
            $this->save();
            $this->saveLogNotify();

            if ($closedWhenSubmit == 1) {
                return $this->commitSaved();
            } else {
                return $this->commitStateStill();
            }
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
    public function canDeleted()
    {
        if ($this->aspect()->exists()) return false;
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
