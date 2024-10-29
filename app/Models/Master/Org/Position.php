<?php

namespace App\Models\Master\Org;

use App\Models\Auth\User;
use App\Models\Globals\TempFiles;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;

class Position extends Model
{
    protected $table = 'ref_positions';

    protected $fillable = [
        'location_id',
        'level_id',
        'name',
        'code',
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
    public function location()
    {
        return $this->belongsTo(OrgStruct::class, 'location_id');
    }

    public function level()
    {
        return $this->belongsTo(LevelPosition::class, 'level_id');
    }

    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'location_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'position_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query
            ->with('level')
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name'])
            ->when(
                $location_id = request()->post('location_id'),
                function ($q) use ($location_id) {
                    $q->where('location_id', $location_id);
                }
            )
            ->when(
                $level_id = request()->post('level_id'),
                function ($q) use ($level_id) {
                    $q->where('level_id', $level_id);
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
            $this->code = $this->code ?: $this->getNewCode();
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
            if (!$this->canDeleted()) {
                throw new \Exception('#' . __('base.error.related'));
            }
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
        if ($this->users()->exists()) return false;

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

    public function getNewCode()
    {
        $max = static::max('code');
        return $max ? $max + 1 : 1001;
    }

    public function imAuditor()
    {
        return $this->location->type == 3 || (isset($this->location->parent->type) && $this->location->parent->type == 3);
    }

    public function imAuditorBranchEvaluasi()
    {
        $temp = OrgStruct::where(function ($q) {
            $q->where(function ($qq) {
                $qq->seksiEvaluasi();
            });
        })->get();
        $lists = [];
        foreach ($temp as $dd) {
            $lists = array_merge($lists, $dd->getIdsWithChild());
        }
        return in_array($this->location_id, $lists);
    }

    public function imLevelManajerSPI()
    {
        $temp = Position::whereHas('level', function ($q) {
            $q->where('name', 'LIKE', '%' . 'Kepala Divisi Internal Audit');
            $q->orWhere('name', 'LIKE', '%' . 'Officer Internal Audit');
        })->pluck('id')->toArray();
        return in_array($this->id, $temp);
    }

    public function isAuditor($request)
    {
        if ($this->where([['name', 'like', '%Audit%'], ['id', '=', $request]])->exists()) return true;

        return false;
    }
}
