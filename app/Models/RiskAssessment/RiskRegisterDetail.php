<?php

namespace App\Models\RiskAssessment;

use App\Models\Master\Aspect\Aspect;
use App\Models\Master\Risk\MainProcess;
use App\Models\Model;
use App\Models\RiskAssessment\CurrentRisk;
use App\Models\RiskAssessment\RiskRegister;
use Illuminate\Support\Carbon;


class RiskRegisterDetail extends Model
{
    protected $table = 'trans_risk_assessment_register_detail';

    protected $fillable = [
        'id_resiko',
        'risk_register_id',
        'main_process_id',
        'peristiwa',
        'sub_process_id',
        'penyebab',
        'dampak',
        'objective',

        // Note Residual
        'condition',
        'notes',
    ];

    protected $casts = [];

    /*******************************
     ** MUTATOR
     *******************************/
    /*******************************
     ** ACCESSOR
     *******************************/
    /*******************************
     ** RELATION
     *******************************/
    public function riskRegister()
    {
        return $this->belongsTo(riskRegister::class, 'risk_register_id');
    }

    public function currentRisk()
    {
        return $this->hasOne(CurrentRisk::class, 'risk_register_detail_id');
    }

    public function inherentRisk()
    {
        return $this->hasOne(InherentRisk::class, 'risk_register_detail_id');
    }

    public function kodeResiko()
    {
        return $this->belongsTo(MainProcess::class, 'main_process_id');
    }

    public function jenisResiko()
    {
        return $this->belongsTo(Aspect::class, 'sub_process_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeGridCompleted($query)
    {
        return $query->whereHas('riskRegister', function ($query) {
            $query->where('status', 'completed');
        });
    }

    public function scopeGridInherentRiskCompleted($query)
    {
        return $query
            ->gridCompleted()
            ->whereHas('inherentRisk', function ($query) {
                $query->where('status', 'completed');
            });
    }

    public function scopeGridCurrentRiskCompleted($query)
    {
        return $query
            ->gridInherentRiskCompleted()
            ->whereHas('currentRisk', function ($query) {
                $query->where('status', 'completed');
            });
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['main_process_id', 'sub_process_id'])
            ->when(request()->get('periode'), function ($q) {
                $q->whereHas('riskRegister', function ($qq) {
                    $periode = request()->get('periode');
                    $periode = Carbon::createFromFormat('d/m/Y', '01/01/' . $periode);
                    $periode = Carbon::parse($periode)->format('Y-m-d');

                    $qq->where('periode', $periode);
                });
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
        $data = 'Detail Risk Register';
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

    public function getCurrentLikelihoodScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overName = '';
        $overCount = 0;
        if ($this->currentRisk) {
            $overCount = $this->currentRisk->total_likehood;
            $overName .= '<b>Likelihood</b>: ' . $this->currentRisk->total_likehood;
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-success"
                     data-toggle="tooltip" title="' . $overName . '"
                     data-html="true" data-placement="right">

                     <span class="symbol-label font-weight-bold">' . round($overCount, 0, PHP_ROUND_HALF_DOWN) . ' </span>
                 </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getLevel($score)
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overName = '';
        $overColor = '';
        $overHover = '';
        if ($score < 5) {
            $overName = 'Low Risk';
            $overColor = 'success';
        } elseif ($score > 10) {
            $overName = 'High Risk';
            $overColor = 'danger';
        } else {
            $overName = 'Medium Risk';
            $overColor = 'warning';
        }
        $overHover .= $score;

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-40 symbol-square symbol-light-' . $overColor . '"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold" style="color:' . $overColor . ';">' . $overHover . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getCurrentImpactScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overCount = 0;
        $overName = '';
        if ($this->currentRisk) {
            $overCount = $this->currentRisk->total_impact;
            $overName .= '<b>Impact</b>: ' . $this->currentRisk->total_impact;
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-success"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . round($overCount, 0, PHP_ROUND_HALF_DOWN) . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getInherentLikelihoodScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overCount = 0;
        $overName = '';
        if ($this->inherentRisk) {
            $overCount = $this->inherentRisk->total_likehood;
            $overName .= '<b>Likelihood</b>: ' . $this->inherentRisk->total_likehood;
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-success"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . round($overCount, 0, PHP_ROUND_HALF_DOWN) . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getInherentImpactScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overCount = 0;
        $overName = '';
        if ($this->inherentRisk) {
            $overCount = $this->inherentRisk->total_impact;
            $overName .= '<b>Impact</b>: ' . $this->inherentRisk->total_impact;
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-success"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . round($overCount, 0, PHP_ROUND_HALF_DOWN) . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getTotalInherentScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overCount = 0;
        $overName = '';
        if ($this->inherentRisk) {
            $overCount = round($this->inherentRisk->total_impact, 0, PHP_ROUND_HALF_DOWN) * round($this->inherentRisk->total_likehood, 0, PHP_ROUND_HALF_DOWN);
            $overName .= '<b>Inherent Score</b>: ' . $this->inherentRisk->total_impact * $this->inherentRisk->total_likehood;
        }

        if ($overCount <= 4) {
            $color = 'success';
        } elseif ($overCount >= 10) {
            $color = 'danger';
        } else {
            $color = 'warning';
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-' . $color . '"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . $overCount . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

    public function getTotalCurrentScore()
    {
        $str = '<div class="d-flex justify-content-between make-td-py-0">';

        $overCount = 0;
        $overName = '';
        if ($this->currentRisk) {
            $overCount = round($this->currentRisk->total_impact, 0, PHP_ROUND_HALF_DOWN) * round($this->currentRisk->total_likehood, 0, PHP_ROUND_HALF_DOWN);
            $overName .= '<b>Residual Score</b>: ' . $this->currentRisk->total_impact * $this->currentRisk->total_likehood;
        }

        if ($overCount <= 4) {
            $color = 'success';
        } elseif ($overCount >= 10) {
            $color = 'danger';
        } else {
            $color = 'warning';
        }

        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '<div class="symbol symbol-30 symbol-square symbol-light-' . $color . '"
                    data-toggle="tooltip" title="' . $overName . '"
                    data-html="true" data-placement="right">

                    <span class="symbol-label font-weight-bold">' . $overCount . ' </span>
                </div>';
        $str .= '</div>';
        $str .= '<div class="symbol-group symbol-hover">';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }

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

            case 'approval':
                if ($this->riskRegister->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;

            case 'tracking':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');
                break;

            case 'print':
                $checkStatus = in_array($this->status, ['waiting.approval', 'completed']);
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

    public function getSasaranRaw()
    {
        $text_content = $this->sasaran;

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
    public function getObjectiveRaw()
    {
        $text_content = strip_tags($this->objective);

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
    public function getPeristiwaRaw()
    {
        $text_content = strip_tags($this->peristiwa);

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

    public function getPenyebabRaw()
    {
        $text_content = strip_tags($this->penyebab);

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

    public function getDampakRaw()
    {
        $text_content = strip_tags($this->dampak);

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
