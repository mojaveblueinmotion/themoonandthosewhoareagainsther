<?php

namespace App\Models\Master\Org;

use App\Models\Globals\TempFiles;
use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Geografis\City;
use App\Models\Master\Risk\LastAudit;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Master\ServiceProvider\Contract;
use App\Models\Model;
use App\Models\Rkia\Summary;
use App\Models\Traits\HasFiles;

class OrgStructGroup extends Model
{
    use HasFiles;
    protected $table = 'ref_org_structs_groups';

    protected $fillable = [
        'group_id',
        'struct_id',
    ];

    /** MUTATOR **/

    /** ACCESSOR **/

    /** RELATION **/
    public function group()
    {
        return $this->belongsTo(OrgStruct::class, 'group_id');
    }
    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    /** SCOPE **/

    /** SAVE DATA **/
}
