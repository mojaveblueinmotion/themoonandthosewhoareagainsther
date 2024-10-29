<?php

namespace App\Models\Globals;

use App\Models\Auth\User;
use App\Models\Globals\MenuFlow;
use App\Models\Globals\TempFiles;
use App\Models\Model;

class Menu extends Model
{
    protected $table = 'sys_menu';
    protected $fillable = [
        'parent_id',
        'module',
        'order',
    ];

    /** ACCESSOR **/
    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->module] ?? '[System]';
    }

    public function getShowModuleWithoutPrefixAttribute()
    {
        $modules = \Base::getModules(false, true);
        return $modules[$this->module] ?? '[System]';
    }

    public function getShowPermsAttribute()
    {
        $modules = \Base::getPermsMenu();
        return $modules[$this->module] ?? '';
    }

    /** RELATION **/
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(static::class, 'parent_id')
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->orderBy('order');
                }
            );
    }

    public function flows()
    {
        return $this->hasMany(MenuFlow::class, 'menu_id')
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->orderBy('order');
                }
            );
    }

    /** SCOPE **/
    // public function scopeGrid($query)
    // {
    //     $modules = \Base::getModules();
    //     return $query
    //         ->whereIn('module', array_keys($modules))
    //         // ->orderBy('order')
    //         ;
    // }
    public function scopeGrid($query)
    {
        $modules = \Base::getModules();
        return $query
            ->with('parent', 'flows')
            ->whereIn('module', array_keys($modules))
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->orderBy('order');
                }
            );
    }

    public function scopeFilters($query)
    {
        return $query->when($module = request()->post('module_name'), function ($q) use ($module) {
            $q->where('module', 'LIKE', $module . '%');
        });
    }

    public function checkAction($user, $action, $perms)
    {
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                return $user->checkPerms($perms . '.edit');
        }
    }

    /** SAVE DATA **/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $temp_role_id = [];
            $flows_ids = [];
            $order = 1;
            if (is_array($request->flows)) {
                // pengecekan tiap flow harus ada role yg terisi
                foreach ($request->flows as $key => $val) {
                    $var = $val['role_id'] ?? 'default value';
                    if ($var == 'default value') {
                        return $this->rollback(
                            [
                                'message' => 'Data role masih kosong!'
                            ]
                        );
                    }
                    $temp_role_id[] = $val['role_id'];
                }
                // pengecekan tiap flow tdk boleh ada role yg double
                if (count(array_unique($temp_role_id)) < count($temp_role_id)) {
                    return $this->rollback(
                        [
                            'message' => 'Role Flow tidak boleh double!'
                        ]
                    );
                }

                // Set agar key berurutan dari 1,2,3...
                $flows = array_combine(range(1, count($request->flows)), array_values($request->flows));
                foreach ($flows as $key => $val) {
                    $flow = $this->flows()->firstOrNew(['role_id' => $val['role_id']]);
                    $flow->type     = $val['type'];
                    $flow->order    = $order;
                    $flow->save();
                    $flows_ids[] = $flow->id;
                    // increment order ketika sekuensial(type==1) atau flow sebelumnya sekuensial(type==1)
                    if ($val['type'] == 1 || (!empty($flows[$key + 1]) && $flows[$key + 1]['type'] == 1)) {
                        $order++;
                    }
                }
            }
            $this->flows()->whereNotIn('id', $flows_ids)->delete();
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = $this->show_module;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Flow Approval ' . $data);
                break;
            case $routes . '.update':
                $this->addLog('Mengubah Flow Approval ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Flow Approval ' . $data);
                break;
            case $routes . '.grant':
                $this->addLog('Mengubah Hak Akses Role ' . $data);
                break;
            case $routes . '.importSave':
                auth()->user()->addLog('Import Data Flow Approval');
                break;
        }
    }
}
