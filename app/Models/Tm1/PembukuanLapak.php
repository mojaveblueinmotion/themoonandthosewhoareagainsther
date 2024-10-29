<?php

namespace App\Models\Tm1;

use App\Models\Auth\User;
use App\Models\Master\Pembukuan\Lapak;
use App\Models\Model;
use Carbon\Carbon;

class PembukuanLapak extends Model
{
    protected $table = 'trans_pembukuan_lapak';

    protected $fillable = [
        'perusahaan_id',
        'month',

        'status',
        'upgrade_reject',
        'version',
    ];

    protected $casts = [
        'month' => 'datetime',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    public function setMonthAttribute($value)
    {
        $this->attributes['month'] = Carbon::createFromFormat('d/m/Y', '01/' . $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function details()
    {
        return $this->hasMany(PembukuanLapakDetail::class, 'pembukuan_lapak_id');
    }

    public function lapak()
    {
        return $this->belongsTo(Lapak::class, 'perusahaan_id');
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
        return $query->filterBy(['perusahaan_id'])
            ->when(request()->get('month'), function ($q) {
                $month = request()->get('month');
                $month = Carbon::createFromFormat('d/m/Y', '01/' . $month);
                $month = Carbon::parse($month)->format('Y-m-d');

                $q->where('month', $month);
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
            $this->status = 'draft';
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
            $this->riskRating()->delete();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            if ($request->is_submit == 1) {
                if ($this->details()->count() == 0) {
                    return $this->rollback(
                        [
                            'message' => 'Detail Pembukuan Tidak Boleh Kosong!'
                        ]
                    );
                }
            }
            $this->fill($request->all());
            $this->status = $request->is_submit ? 'completed' : 'draft';
            $this->save();
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleRevisi($request)
    {
        $this->beginTransaction();
        try {

            $this->update(
                [
                    'status' => 'draft',
                    'version' => $this->version + 1, //versi diubah saat diapprove
                ]
            );
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = 'Tahun ' . $this->month->format('F Y') . ' Lapak ' . $this->lapak->name;
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
            case $routes . '.detailStore':
                $this->addLog('Membuat Detail Data ' . $data);
                $this->addNotify([
                    'message' => 'Membuat Detail Data ' . $data,
                    'url' => route($routes . '.detail', $this->id),
                    'user_ids' => [1],
                ]);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Detail Data ' . $data);
                $this->addNotify([
                    'message' => 'Mengubah Detail Data ' . $data,
                    'url' => route($routes . '.detail', $this->id),
                    'user_ids' => [1],
                ]);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Menghapus Detail Data ' . $data);
                $this->addNotify([
                    'message' => 'Menghapus Detail Data ' . $data,
                    'url' => route($routes . '.detail', $this->id),
                    'user_ids' => [1],
                ]);
                break;
            case $routes . '.submitSave':
                $this->addLog('Submit Data ' . $data);
                $this->addNotify([
                    'message' => 'Menyimpan ' . $data,
                    'url' => route($routes . '.detail', $this->id),
                    'user_ids' => [1],
                ]);
                break;

            case $routes . '.revisi':
                $this->addLog('Revisi ' . $data);
                $this->addNotify([
                    'message' => 'Revisi ' . $data,
                    'url' => route($routes . '.detail', $this->id),
                    'user_ids' => [1],
                ]);
                break;
        }
    }

    /** OTHER FUNCTIONS **/
    public function checkAction($action, $perms)
    {
        $user = auth()->user();

        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $this->details()->count() == 0 && $user->checkPerms($perms . '.approve');

            case 'detailCreate':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.create');

            case 'detailEdit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'detailDelete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');

            case 'detailShow':
                $checkStatus = ($this->status);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'submit':
                $checkStatus = in_array($this->status, ['new', 'draft']);
                $checkDetail = $this->details()->exists();
                return $checkStatus && $checkDetail && $user->checkPerms($perms . '.approve');

            case 'print':
                $checkStatus = in_array($this->status, ['draft', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'revisi':
                $checkStatus = in_array($this->status, ['completed']);
                return $checkStatus && $user->checkPerms($perms . '.approve');
                break;
        }
        return false;
    }

    public function canDeleted()
    {
        return true;
    }
}
