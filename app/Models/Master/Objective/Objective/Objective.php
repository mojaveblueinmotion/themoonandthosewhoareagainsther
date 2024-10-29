<?php

namespace App\Models\Master\Objective\Objective;

use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Procedure\ProcedureAudit;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Objective extends Model
{
    protected $table = "ref_objective";

    protected $fillable = [
        "name",
        'aspect_id',
        'objective',
        "description",
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
    public function aspect() {
        return $this->belongsTo(Aspect::class, 'aspect_id');
    }

    public function procedure()
    {
        return $this->hasMany(ProcedureAudit::class, 'objective_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query
            ->with('aspect')
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
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
            ->when(request()->get('subject_id'), function ($q) {
                $q->whereHas('aspect', function ($qq){
                    $qq->whereHas('subject', function ($qqq){
                        $qqq->where('id', request()->get('subject_id'));
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
        if ($this->procedure()->exists()) return false;
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
