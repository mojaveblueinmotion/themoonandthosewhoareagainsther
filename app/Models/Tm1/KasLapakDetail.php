<?php

namespace App\Models\Tm1;

use App\Models\Model;
use App\Models\Tm1\KasLapak;
use Illuminate\Support\Carbon;


class KasLapakDetail extends Model
{
    protected $table = 'trans_detaiL_kas_lapak';

    protected $fillable = [
        'kas_lapak_id',
        'tgl_input',
        'keterangan',
        'tipe',
        'total',
        'saldo_sisa',
        'description',
        'status',
    ];

    protected $casts = [
        'tgl_input'       => 'date',
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    public function setTglInputAttribute($value)
    {
        if ($value === null) {
            $this->attributes['tgl_input'] = null;
        } else {
            $this->attributes['tgl_input'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function setTotalAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['total'] = (int) $value;
    }
    
    public function setSaldoSisaAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['saldo_sisa'] = (int) $value;
    }
    /*******************************
     ** ACCESSOR
     *******************************/
    /*******************************
     ** RELATION
     *******************************/
    public function kasLapak()
    {
        return $this->belongsTo(KasLapak::class, 'kas_lapak_id');
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
        return $query->filterBy(['main_process_id', 'sub_process_id'])
            ->when(request()->get('tgl_input'), function ($q) {
                $tgl_input = request()->get('tgl_input');
                $tgl_input = Carbon::createFromFormat('d/m/Y', $tgl_input);
                $tgl_input = Carbon::parse($tgl_input)->format('Y-m-d');

                $q->where('tgl_input', $tgl_input);
            })
            ->when(request()->get('type_id'), function ($q) {
                $q->whereHas('riskRegister', function ($qq) {
                    $qq->where('type_id', request()->get('type_id'));
                });
            })
            ->when(request()->get('object_id'), function ($q) {
                $q->whereHas('riskRegister', function ($qq) {
                    $qq->where('object_id', request()->get('object_id'));
                });
            })
            ->when(request()->get('unit_kerja_id'), function ($q) {
                $q->whereHas('riskRegister', function ($qq) {
                    $qq->where('unit_kerja_id', request()->get('unit_kerja_id'));
                });
            })
            ->when(request()->get('auditee_id'), function ($k) {
                $k->whereHas('riskRegister', function ($q) {
                    $q->whereHas('subject', function ($qq) {
                        $qq->whereHas('deptartmentAuditee', function ($qqq) {
                            $qqq->whereHas('departments', function ($qqqq) {
                                $qqqq->where('department_id', request()->get('auditee_id'));
                            });
                        });
                    });
                });
            });
    }

    /*******************************
     ** SAVING
     *******************************/

    public function handleDetailStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $checkKasLapak = KasLapakDetail::where('kas_lapak_id', $request->kas_lapak_id)->get();
            if ($checkKasLapak->count() == 0 && $request->tipe == 1) {
                return $this->rollback(
                    [
                        'message' => 'Saldo tidak cukup untuk melakukan kredit!'
                    ]
                );
            }
            if($request->saldo_sisa <= 0){
                return $this->rollback(
                    [
                        'message' => 'Saldo tidak cukup untuk melakukan kredit!'
                    ]
                );
            }
            $this->fill($request->all());
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    /** SAVE DATA **/

    public function handleDetailDestroy()
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
        $data = 'Detail Kas Lapak';
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.detailStore':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
        }
    }

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/

    public function checkAction($action, $perms, $summary = null)
    {
        $user = auth()->user();

        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.view');
                break;

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');
                break;

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');
                break;

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');
                break;

            case 'print':
                $checkStatus = in_array($this->status, ['draft', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');
                break;

            default:
                return false;
                break;
        }
    }

    public function canDeleted()
    {
        return true;
    }
}
