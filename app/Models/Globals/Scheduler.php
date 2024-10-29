<?php

namespace App\Models\Globals;

use App\Models\Auth\User;
use App\Models\Conducting\Kka\KkaSampleDetail;
use App\Models\Globals\Notification;
use App\Models\Model;
use App\Models\Rkia\Summary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Scheduler extends Model
{
    protected $table = 'sys_scheduler';

    protected $fillable = [
        'target_id',
        'target_type',
        'module',
        'process',
        'message'
    ];

    public function target()
    {
        return $this->morphTo();
    }

    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeFilters($query)
    {
        return $query
            ->when($module = request()->post('module_name'), function ($q) use ($module) {
                $q
                    ->where(function ($q) use ($module) {
                        $q->where('module', 'LIKE', '%' . $module . '%')
                            ->when($module == 'auth_', function ($qq) {
                                $qq->orWhere('module', 'setting.profile');
                            });
                    });
            })
            ->when($date_start = request()->post('date_start'), function ($q) use ($date_start) {
                $date_start = Carbon::createFromFormat('d/m/Y', $date_start);
                $q->whereDate('created_at', '>=', $date_start);
            })
            ->when($date_end = request()->post('date_end'), function ($q) use ($date_end) {
                $date_end = Carbon::createFromFormat('d/m/Y', $date_end);
                $q->whereDate('created_at', '<=', $date_end);
            });
    }

    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->module] ?? '[System]';
    }

    public function runScheduler()
    {
        $this->beginTransaction();
        try {
            $sample = KkaSampleDetail::whereHas('komitmen')->where('deadline', '!=', 'null')->get();
            foreach ($sample as $detail) {
                if (empty($detail->regItem)) {
                    $auditee = User::select('id')
                        ->whereRelation('position', 'location_id', $detail->sample->summary->object_id)
                        ->orWhere('provider_id', $detail->sample->summary->object_id)
                        ->pluck('id')->toArray();

                    $data['user_ids'] = array_filter($auditee ?? []);
                    if (!empty($data['user_ids'])) {
                        $notify = new Notification;
                        $notify->fill(
                            [
                                'message' => 'Tindak lanjut pada komitmen ' . $detail->sample->summary->getLetterNo() . ' telah melewati batas waktu!',
                                'url' => route('conducting.commitment.show', $detail->komitmen->id),
                                'module' => 'conducting.commitment',
                                'target_type' => 'App\Models\Conducting\Kka\KkaCommitment',
                                'target_id' => $detail->komitmen->id
                            ]
                        );
                        $notify->save();
                        $notify->users()->sync($data['user_ids']);

                        $this->fill(
                            [
                                'message' => 'Tindak lanjut pada komitmen ' . $detail->sample->summary->getLetterNo() . ' telah melewati batas waktu!',
                                'process' => 'conducting.commitment',
                                'target_type' => 'App\Models\Conducting\Kka\KkaCommitment',
                                'target_id' => $detail->komitmen->id
                            ]
                        );
                        $this->save();

                        if (config('base.mail.send') == true) {
                            $emails = $notify->users()->pluck('email')->toArray();
                            Mail::to($emails)->send(new \App\Mail\NotificationMail($notify));
                        }
                    }
                }
            }

            if($sample->count() == 0){
                return $this->rollback(
                    [
                        'message' => 'Data pada scheduler terkait tidak tersedia, scheduler dibatalkan!'
                    ]
                );
            }

            $redirect = rut(request()->get('routes') . '.index', $this->id);
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }


    }
}
