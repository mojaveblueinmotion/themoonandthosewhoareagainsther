<?php

namespace App\Models\Master\Risk;

use App\Models\Model;

class RiskAssessmentDetail extends Model
{
    protected $table = 'ref_risk_assessments_details';

    protected $fillable = [
        'risk_assessment_id',
        'risk_rating_id',
        'description',
        'source',
    ];

    /*******************************
     ** MUTATOR
     *******************************/

    /*******************************
     ** ACCESSOR
     *******************************/

    /*******************************
     ** RELATION
     *******************************/
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }
    public function riskRating()
    {
        return $this->belongsTo(RiskRating::class, 'risk_rating_id');
    }

    /*******************************
     ** SCOPE
     *******************************/

    /*******************************
     ** SAVING
     *******************************/

    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
}
