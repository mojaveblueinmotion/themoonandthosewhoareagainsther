<?php

namespace App\Models\Master\Org;

use App\Models\Auth\User;
use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Geografis\City;
use App\Models\Master\Org\DepartmentAuditee;
use App\Models\Master\Risk\LastAudit;
use App\Models\Master\Risk\MainProcess;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Master\ServiceProvider\Contract;
use App\Models\Model;
use App\Models\Rkia\Summary;
use App\Models\Traits\HasFiles;

class OrgStruct extends Model
{
    use HasFiles;
    protected $table = 'ref_org_structs';

    protected $fillable = [
        'parent_id',
        'year',
        'level', //root, boc, bod, subsidiary, department, division, branch
        'type', //1:presdir, 2:direktur finance, 3:ia division, 4:it division
        'name',
        'email',
        'website',
        'code',
        'phone',
        'address',
        'province_id',
        'city_id',
        'type_id',
        'description',
    ];

    /** MUTATOR **/

    /** ACCESSOR **/
    public function getShowLevelAttribute()
    {
        switch ($this->level) {
            case 'root':
                return __('Perusahaan');
            case 'boc':
                return __('Pengawas');
            case 'bod':
                return __('Direksi');
            case 'department':
                return __('Departemen');
            case 'division':
                return __('Divisi');
            case 'subdivision':
                return __('Sub Divisi');
            case 'provider':
                return __('Penyedia Jasa');
            case 'group':
                return __('Grup Lainnya');
            case 'subject':
                return __('Subjek Audit');
            default:
                return ucfirst($this->level);
        }
    }

    /** RELATION **/
    public function aspects()
    {
        return $this->hasMany(Aspect::class, 'object_id');
    }
    public function mainProcesses()
    {
        return $this->hasMany(MainProcess::class, 'subject_id');
    }

    public function parent()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id');
    }

    public function parents()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id');
    }

    public function deptartmentAuditee()
    {
        return $this->hasMany(DepartmentAuditee::class, 'subject_id');
    }

    public function childOfGroup()
    {
        return $this->belongsToMany(OrgStruct::class, 'ref_org_structs_groups', 'group_id', 'struct_id');
    }

    public function structGroup()
    {
        return $this->belongsToMany(OrgStruct::class, 'ref_org_structs_groups', 'struct_id', 'group_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'location_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function typeAudit()
    {
        return $this->belongsTo(TypeAudit::class, 'type_id');
    }

    /** SCOPE **/
    public function scopeFilters($query)
    {
        $request = request();
        return $query
            ->when(
                $parent_parent_id = $request->parent_parent_id,
                function ($q) use ($parent_parent_id) {
                    $q->whereRelation('parent', 'parent_id', $parent_parent_id);
                }
            )
            ->when(
                $parent_id = $request->parent_id,
                function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                }
            )
            ->when(
                $type_id = $request->type_id,
                function ($q) use ($type_id) {
                    $q->where('type_id', $type_id);
                }
            )
            ->when(
                $code = $request->code,
                function ($q) use ($code) {
                    $q->where('code', $code);
                }
            )
            ->when(
                $name = $request->name,
                function ($q) use ($name) {
                    $q->where('name', 'LIKE', '%' . $name . '%');
                }
            )
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
    }

    public function scopeRoot($query)
    {
        return $query
            ->where('level', 'root');
    }

    public function scopeBoc($query)
    {
        return $query
            ->where('level', 'boc');
    }

    public function scopeBod($query)
    {
        return $query
            ->where('level', 'bod');
    }

    public function scopeDepartment($query)
    {
        return $query->where('level', 'department');
    }
    public function scopeDivision($query)
    {
        return $query->where('level', 'division');
    }
    public function scopeGroup($query)
    {
        return $query->where('level', 'group');
    }
    public function scopeGrid($query)
    {
        return $query;
    }
    public function scopeSubdivision($query)
    {
        return $query->where('level', 'subdivision');
    }
    public function scopeProvider($query)
    {
        return $query->where('level', 'provider');
    }
    public function scopeSubject($query)
    {
        return $query->where('level', 'subject');
    }
    public function scopeSubsidiary($query)
    {
        return $query->where('level', 'subsidiary');
    }

    public function scopeInAudit($query)
    {
        return $query->where('type', 3)->orWhereRelation('parent', 'type', 3);
    }

    /** SAVE DATA **/
    public function handleStoreOrUpdate($request, $level)
    {
        $this->beginTransaction();
        try {
            $closedWhenSubmit = 0;
            if (!empty($this->created_at)) {
                $closedWhenSubmit = 1;
            }
            if (in_array($level, ['boc', 'bod', 'department', 'division'])) {
                if ($root = static::root()->first()) {
                    $this->phone = $root->phone;
                    $this->address = $root->address;
                }
            }
            $this->fill($request->all());
            $this->updated_at = now();
            $this->level = $level;
            $this->code = $this->code ?: $this->getNewCode($level);
            $this->save();
            if (in_array($level, ['subsidiary', 'subdivision', 'department', 'division'])) {
                $this->code = $this->parent->code . $request->code;
            }
            $this->save();
            if (OrgStruct::where('code', $this->code)->where('id', '!=', $this->id)->count() > 0) {
                return $this->rollback(
                    [
                        'code'  => 422,
                        'message' => 'Kode sudah ada sebelumnya.',
                        'errors' => [
                            'code' => [
                                'sudah ada sebelumnya.'
                            ]
                        ],
                    ]
                );
            }
            $this->saveFilesByTemp($request->perseroan, $request->module, 'perseroan');

            if (in_array($level, ['group', 'subject'])) {
                $this->childOfGroup()->sync($request->children);
            }
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
                return $this->rollback(__('base.error.related'));
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
            case $routes . '.importSave':
                auth()->user()->addLog('Import Data Master Struktur Organisasi');
                break;
        }
    }

    /** OTHER FUNCTIONS **/
    public function canDeleted()
    {
        if (in_array($this->type, [1, 2, 3, 4, 5])) return false;
        if (in_array($this->level, ['root', 'boc'])) return false;
        if ($this->aspects()->exists()) return false;
        if ($this->children()->exists()) return false;
        if ($this->structGroup()->exists()) return false;
        if ($this->positions()->exists()) return false;
        if ($this->mainProcesses()->exists()) return false;
        if ($this->deptartmentAuditee()->exists()) return false;

        // if (LastAudit::where('subject_id', $this->id)->exists()) return false;
        // if (Summary::where('subject_id', $this->id)->exists()) return false;

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

        return false;
    }

    public function getNewCode($level)
    {
        switch ($level) {
            case 'root':
                $max = static::root()->max('code');
                return $max ? $max + 1 : 1001;
            case 'boc':
                $max = static::boc()->max('code');
                return $max ? $max + 1 : 1101;
            case 'bod':
                $max = static::bod()->max('code');
                return $max ? $max + 1 : 2001;
            case 'department':
                $max = static::division()->max('code');
                return $max ? $max + 1 : 3001;
            case 'division':
                $max = static::division()->max('code');
                return $max ? $max + 1 : 4001;
            case 'subdivision':
                $max = static::division()->max('code');
                return $max ? $max + 1 : 5001;
        }
        return null;
    }

    public function getIdsWithChild()
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getIdsWithChild());
        }
        return $ids;
    }
    public function getUserIds()
    {
        return User::whereRelation('position', 'location_id', $this->id)
            ->pluck('id')
            ->toArray();
    }

    public function getUnitKerja()
    {
        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
            <dic class="symbol-group symbol-hover">';

        $overCount = 0;
        $overName = '';
        foreach ($this->childOfGroup as $i => $value) {
            $overCount++;
            $overName .= ($i + 1) . '. ' . $value->name . "<br>";
        }

        if ($overCount > 0) {
            $str .= '<div class="symbol symbol-30 symbol-square symbol-light-success"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . $overCount . ' </span>
                </div>';
        }
        $str .= '
                </div>
            </div>';
        return $str;
    }
}
