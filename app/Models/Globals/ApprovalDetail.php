<?php

namespace App\Models\Globals;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\Position;
use App\Models\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ApprovalDetail extends Model
{
    // use HasUuids;
    // protected $primaryKey = 'uuid';
    protected $table = 'sys_approval_detail';

    protected $fillable = [
        'approval_id',
        'role_id',
        'user_id',
        'position_id',
        'type',
        'order',
        'status',
        'note',
    ];

    protected $casts = [
        'approved_at'   => 'date',
    ];

    /** MUTATOR **/
    /** ACCESSOR **/
    public function getShowTypeAttribute()
    {
        if ($this->type == 2) {
            return 'Paralel';
        }
        return 'Sekuensial';
    }

    public function getShowColorAttribute()
    {
        if ($this->type == 2) {
            return 'info';
        }
        return 'primary';
    }

    /** RELATION **/
    function approval() {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
    public function target()
    {
        return $this->morphTo();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /** SCOPE **/
    /** TRANSACTION **/

    /** OTHER FUNCTIONS **/
}
