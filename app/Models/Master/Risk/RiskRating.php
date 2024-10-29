<?php

namespace App\Models\Master\Risk;

use App\Models\Globals\TempFiles;
use App\Models\Model;

class RiskRating extends Model
{
    protected $table = 'ref_risk_ratings';

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

    /*******************************
     ** RELATION
     *******************************/
    public function riskAssessmentDetail()
    {
        return $this->hasMany(RiskAssessmentDetail::class, 'risk_rating_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
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
        switch (true) {
            case $this->riskAssessmentDetail()->exists():
                return false;
                break;
        }
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
