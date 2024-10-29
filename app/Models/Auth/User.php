<?php

namespace App\Models\Auth;

use App\Models\Auth\Pendidikan;
use App\Models\Auth\Sertifikasi;
use App\Models\Globals\Activity;
use App\Models\Globals\Approval;
use App\Models\Globals\ApprovalDetail;
use App\Models\Globals\Notification;
use App\Models\Globals\TempFiles;
use App\Models\Master\Fee\BankAccount;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use App\Models\Rkia\Rkia;
use App\Models\Rkia\Summary;
use App\Models\Survey\SurveyRegUser;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;
    use RaidModel, Utilities, ResponseTrait;

    protected $table = 'sys_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'username',
        'email',
        'password',
        'jabatan_provider',
        'nik',
        'npp',
        'image',
        'phone',
        'position_id',
        'provider_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** MUTATOR **/
    /** ACCESSOR **/
    public function getImagePathAttribute()
    {
        if ($this->image) {
            if (\Storage::disk('public')->exists($this->image)) {
                return 'storage/' . $this->image;
            }
            $this->update(['image' => null]);
        }
        return 'assets/media/users/default.jpg';
    }

    public function getRolesImplodedAttribute()
    {
        return implode(', ', $this->roles->pluck('name')->toArray());
    }

    /** RELATION **/
    public function notifications()
    {
        return $this->belongsToMany(
            Notification::class,
            'sys_notifications_users',
            'user_id',
            'notification_id'
        )
            ->withPivot('readed_at');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function provider()
    {
        return $this->belongsTo(OrgStruct::class, 'provider_id');
    }

    /** SCOPE **/
    public function scopeGrid($query)
    {
        return $query
            ->with('position.location', 'roles', 'provider');
    }

    public function scopeInAuditorISO($query, $location_id)
    {
        $location_ids = OrgStruct::find($location_id)->getIdsWithChild();

        return $query->where('is_iso_auditor', 2)->whereHas(
            'position',
            function ($q) use ($location_ids) {
                $q->whereHas(
                    'location',
                    function ($qq) use ($location_ids) {
                        $qq->whereNotIn('id', $location_ids);
                    }
                );
            }
        );
    }

    public function scopeFilters($query)
    {
        return $query
            // ->filterBy('status', '=')
            // ->filterBy('position_id', '=')
            ->when(
                $name = request()->post('name'),
                function ($q) use ($name) {
                    $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                }
            )
            ->when(
                $location_id = request()->post('location_id'),
                function ($q) use ($location_id) {
                    $q->whereHas(
                        'position',
                        function ($qq) use ($location_id) {
                            $qq->where('location_id', $location_id);
                        }
                    );
                }
            )
            ->when(
                $provider_id = request()->post('provider_id'),
                function ($q) use ($provider_id) {
                    $q->where('provider_id', $provider_id);
                }
            )
            ->when(
                $status = request()->post('status'),
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )
            ->when(
                $role_id = request()->post('role_id'),
                function ($q) use ($role_id) {
                    $q->whereHas(
                        'roles',
                        function ($qq) use ($role_id) {
                            $qq->where('id', $role_id);
                        }
                    );
                }
            );
    }

    public function scopeWhereHasLocationId($query, $location_id = 0, $type_id = 0, $provider_id = 0)
    {
        return $query->when(
            $type_id === 2,
            function ($q) use ($provider_id) {
                $q->where('provider_id', $provider_id);
            },
            function ($q) use ($provider_id, $location_id) {
                $q
                    ->whereHas('position', function ($q) use ($location_id) {
                        $q
                            ->whereHas('location', function ($q) use ($location_id) {
                                $q->where('id', $location_id)
                                    ->orWhere('parent_id', $location_id)
                                    ->orWhereHas('parent', function ($q) use ($location_id) {
                                        $q->where('parent_id', $location_id);
                                    });
                            });
                    });
            }
        );
    }


    /** SAVE DATA */
    public function handleStoreOrUpdate($request, $type = null)
    {
        $this->beginTransaction();
        try {
            $this->fill($request->all());
            if ($request->password) {
                $this->password = bcrypt($request->password);
            }
            $this->save();
            if ($type == 'provider') {
                $this->roles()->sync([2]);
            } else {
                $this->roles()->sync(array_filter($request->roles ?? []));
            }
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
            // if (!$this->canDeleted()) {
            //     return $this->rollback(__('base.error.related'));
            // }
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleResetPassword()
    {
        $this->beginTransaction();
        try {
            $this->password = bcrypt('qwerty123456');
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved(['redirect' => rut(request()->get('routes') . '.index')]);
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleStoreOrUpdatePendidikan($request)
    {
        $this->beginTransaction();
        try {
            $pendidikan = $this->pendidikans()
                ->firstOrNew(
                    [
                        'jenjang_pendidikan' => $request->jenjang_pendidikan
                    ]
                );
            $pendidikan->fill($request->all());
            $this->pendidikans()->save($pendidikan);
            $pendidikan->saveFilesByTemp($request->attachments, $request->module, 'lampiran_pendidikan');

            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleStoreOrUpdateSertifikasi($request)
    {
        $this->beginTransaction();
        try {
            $sertifikasi = $this->sertifikasis()
                ->firstOrNew(
                    [
                        'nama_sertif' => $request->nama_sertif
                    ]
                );
            $sertifikasi->fill($request->all());
            $this->sertifikasis()->save($sertifikasi);
            $sertifikasi->saveFilesByTemp($request->attachments, $request->module, 'lampiran_sertifikasi');

            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleUpdateProfile($request)
    {
        $this->beginTransaction();
        try {
            if ($request->image) {
                $oldImage = $this->image;
                $this->image = $request->image->store('users', 'public');
            }
            $this->phone  = $request->phone;
            $this->email  = $request->email;
            $this->save();
            $this->saveLogNotify();

            // Hapus file image yg lama
            if (!empty($oldImage) && \Storage::disk('public')->exists($oldImage)) {
                \Storage::disk('public')->delete($oldImage);
            }
            return $this->commitSaved(['redirectTo' => rut($request->routes . '.index')]);
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleUpdatePassword($request)
    {
        $this->beginTransaction();
        try {
            $this->password  = bcrypt($request->new_password);
            $this->save();
            $request->merge(['module' => 'profile']);
            $this->saveLogNotify();
            return $this->commitSaved(['redirectTo' => rut($request->routes . '.index')]);
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
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
            case $routes . '.resetPassword':
                $this->addLog('Reset Password ' . $data);
                break;
            case $routes . '.updateProfile':
                $this->addLog('Mengubah Profil ' . $data);
                break;
            case $routes . '.updatePassword':
                $this->addLog('Mengubah Password ' . $data);
                break;
            case $routes . '.importSave':
                auth()->user()->addLog('Import Data User');
                break;
        }
    }

    /** OTHER FUNCTION **/
    public function canDeleted()
    {
        if (in_array($this->id, [1])) return false;
        if ($this->id == auth()->id()) return false;
        if (ApprovalDetail::where('user_id', $this->id)->exists()) return false;

        $check = Summary::where('pic_id', $this->id)
            ->orWhere('leader_id', $this->id)
            ->orWhereHas(
                'members',
                function ($q) {
                    $q->where('user_id', $this->id);
                }
            )
            ->orWhereHas(
                'rkia',
                function ($r) {
                    $r->orWhereHas(
                        'cc',
                        function ($q) {
                            $q->where('user_id', $this->id);
                        }
                    );
                }
            )
            ->orWhereHas(
                'assignment',
                function ($a) {
                    $a->where('pic_id', $this->id)
                        ->orWhere('leader_id', $this->id)
                        ->orWhereHas(
                            'members',
                            function ($q) {
                                $q->where('user_id', $this->id);
                            }
                        );
                }
            )
            ->exists();
        if ($check) return false;

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

    public function checkPerms($permission)
    {
        return $this->hasPermissionTo($permission);
    }

    public function hasAllObjects()
    {
        // Role Administrator
        if ($this->hasRole(1)) {
            return true;
        }

        if ($this->position && ($location = $this->position->location)) {
            // boc, bod, ia division
            if (in_array($location->level, ['boc', 'bod'])) {
                return true;
            }
            if (in_array($location->type, [3])) {
                return true;
            }
            if ($location->parent) {
                if (in_array($location->parent->type, [3])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getManajerSPI()
    {
        return $this->where('status', 'active')
            ->whereHas('position', function ($q) {
                $q->whereHas('level', function ($qq) {
                    $qq->where('name', 'Manajer SPI');
                });
            })->latest()->first();
    }

    public function getLastNotificationId()
    {
        $last = $this->notifications()
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            )
            ->first();
        return $last->id ?? 0;
    }

    public function getRoleIds()
    {
        return $this->roles()->pluck('id')->toArray();
    }
}
