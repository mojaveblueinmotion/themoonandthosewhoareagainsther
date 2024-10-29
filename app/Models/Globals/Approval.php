<?php

namespace App\Models\Globals;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\Position;
use App\Models\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Approval extends Model
{
    // use HasUuids;
    // protected $primaryKey = 'uuid';
    protected $table = 'sys_approval';

    protected $fillable = [
        'target_type',
        'target_id',
        'module',
    ];

    protected $casts = [];

    /** MUTATOR **/
    /** ACCESSOR **/
    /** RELATION **/
    function details()
    {
        return $this->hasMany(ApprovalDetail::class, 'approval_id');
    }
    public function target()
    {
        return $this->morphTo();
    }
    /** SCOPE **/
    /** TRANSACTION **/

    /** OTHER FUNCTIONS **/
}
