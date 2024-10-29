<?php

namespace App\Models\Tm1;

use App\Models\Master\Pembukuan\Kendaraan;
use App\Models\Model;
use Illuminate\Support\Carbon;


class PembukuanSamDetail extends Model
{
    protected $table = 'trans_detaiL_pembukuan_sam';

    protected $fillable = [
        'pembukuan_sam_id',
        'kendaraan_id',
        'no_timbangan',
        'tgl_masuk',
        'kirim_pabrik',
        'supplier',
        'gross',
        'tere',
        'bruto',
        'refaksi',
        'potongan',
        'netto',
        'harga',
        'jumlah',
        'biaya_bongkar_ampera',
        'fee_agen',
        'fee_agen_bruto',
        'total_dibayar',
        'hasil_akhir',
        'status',
    ];

    protected $casts = [
        'tgl_masuk'       => 'date',
        'kirim_pabrik'        => 'date',
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    public function setTglMasukAttribute($value)
    {
        if ($value === null) {
            $this->attributes['tgl_masuk'] = null;
        } else {
            $this->attributes['tgl_masuk'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }
    
    public function setKirimPabrikAttribute($value)
    {
        if ($value === null) {
            $this->attributes['kirim_pabrik'] = null;
        } else {
            $this->attributes['kirim_pabrik'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function setGrossAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['gross'] = (int) $value;
    }

    public function setTereAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['tere'] = (int) $value;
    }

    public function setBrutoAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['bruto'] = (int) $value;
    }

    public function setRefaksiAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['refaksi'] = (int) $value;
    }

    public function setPotonganAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['potongan'] = (int) $value;
    }

    public function setNettoAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['netto'] = (int) $value;
    }

    public function setHargaAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['harga'] = (int) $value;
    }

    public function setJumlahAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['jumlah'] = (int) $value;
    }

    public function setBiayaBongkarAmperaAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['biaya_bongkar_ampera'] = (int) $value;
    }

    public function setFeeAgenBrutoAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['fee_agen_bruto'] = (int) $value;
    }

    public function setFeeAgenAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['fee_agen'] = (int) $value;
    }

    public function setTotalDibayarAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['total_dibayar'] = (int) $value;
    }

    public function setHasilAkhirAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['hasil_akhir'] = (int) $value;
    }
    /*******************************
     ** ACCESSOR
     *******************************/
    /*******************************
     ** RELATION
     *******************************/
    public function pembukuanSam()
    {
        return $this->belongsTo(PembukuanSam::class, 'pembukuan_sam_id');
    }

    public function parts()
    {
        return $this->hasMany(PembukuanSamDetailPembayaran::class, 'detail_id');
    }
    
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
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
            ->when(request()->get('tgl_masuk'), function ($q) {
                $tgl_masuk = request()->get('tgl_masuk');
                $tgl_masuk = Carbon::createFromFormat('d/m/Y', $tgl_masuk);
                $tgl_masuk = Carbon::parse($tgl_masuk)->format('Y-m-d');

                $q->where('tgl_masuk', $tgl_masuk);
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
            $this->fill($request->all());
            $this->save();
            $this->saveParts($request);
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    /** SAVE DATA **/
    public function saveParts($request)
    {
        $partsIds = [];
        // Internal
        foreach ($request->parts as $val) {
            if(empty($val['total'])){
                continue;
            }
            $part = $this->parts()->firstOrNew(['pembayaran_id' => $val['pembayaran_id']]);
            $part->total = $val['total'];
            $this->parts()->save($part);
            $partsIds[] = $part->id;
        }
        $this->parts()->whereNotIn('id', $partsIds)->delete();
    }

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
        $data = 'Detail Pembukuan Sam';
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
