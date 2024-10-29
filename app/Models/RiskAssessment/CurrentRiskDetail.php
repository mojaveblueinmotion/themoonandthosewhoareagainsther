<?php

namespace App\Models\RiskAssessment;

use App\Models\Model;
use App\Models\RiskAssessment\CurrentRisk;
use Illuminate\Support\Carbon;


class CurrentRiskDetail extends Model
{
    protected $table = 'trans_current_risk_detail';

    protected $fillable = [
        'current_risk_id',
        'internal_control',
        'tgl_realisasi',
        'realisasi',
    ];

    protected $casts = [
        'tgl_realisasi' => 'datetime'
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    public function setTglRealisasiAttribute($value)
    {
        $this->attributes['tgl_realisasi'] = Carbon::createFromFormat('d/m/Y', $value);
    }
    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function residualRisk()
    {
        return $this->belongsTo(CurrentRisk::class, 'current_risk_id');
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
        return $query->filterBy(['tgl_realisasi']);
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
            $this->residualRisk->update(
                [
                    'status' => 'draft'
                ]
            );
            $this->residualRisk->saveLogNotify();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
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
        $data = 'Detail Residual Risk';
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

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');

            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;

            case 'tracking':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');

            case 'print':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');

            default:
                return false;
        }
    }

    public function canDeleted()
    {
        return true;
    }

    public function getInternalControlRaw()
    {
        $text_content = $this->internal_control;

        // Count the number of words in the project name
        $wordCount = str_word_count($text_content);

        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

        // Adjust the width and height for a rectangular shape
        $str .= '<div class="symbol symbol-rect symbol-light-success"
                data-toggle="tooltip" title="' . $text_content . '"
                data-html="true" data-placement="right"
                style="width: 80px; height: 30px;">
                <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
            </div>';

        $str .= '
                </div>
            </div>';

        return $str;
    }

    public function getRealisasiRaw()
    {
        $text_content = $this->realisasi;

        // Count the number of words in the project name
        $wordCount = str_word_count($text_content);

        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

        // Adjust the width and height for a rectangular shape
        $str .= '<div class="symbol symbol-rect symbol-light-success"
                data-toggle="tooltip" title="' . $text_content . '"
                data-html="true" data-placement="right"
                style="width: 80px; height: 30px;">
                <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
            </div>';

        $str .= '
                </div>
            </div>';

        return $str;
    }
}
