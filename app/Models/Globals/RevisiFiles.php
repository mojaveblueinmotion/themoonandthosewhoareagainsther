<?php

namespace App\Models\Globals;

use App\Models\Conducting\Closing\Closing;
use App\Models\Conducting\Kka\KkaCommitment;
use App\Models\Conducting\Kka\KkaFeedback;
use App\Models\Conducting\Kka\KkaSample;
use App\Models\Conducting\Kka\KkaWorksheet;
use App\Models\Conducting\MemoClosing\MemoClosing;
use App\Models\Conducting\MemoOpening\MemoOpening;
use App\Models\Conducting\Opening\Opening;
use App\Models\Followup\FollowupMonitor;
use App\Models\Followup\FollowupReschedule;
use App\Models\Followup\FollowupReview;
use App\Models\Followup\MemoTindakLanjut;
use App\Models\Model;
use App\Models\Preparation\Apm\Apm;
use App\Models\Preparation\Assignment\Assignment;
use App\Models\Reporting\Lha\Lha;
use App\Models\RiskAssessment\CurrentRisk;
use App\Models\RiskAssessment\InherentRisk;
use App\Models\RiskAssessment\RiskRating;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\Rkia\Rkia;
use App\Models\Rkia\Summary;
use Illuminate\Support\Facades\URL;

class RevisiFiles extends Model
{
    protected $table = 'sys_revisi';

    protected $fillable = [
        'target_id',
        'target_type',
        'module',
        'version',
        'file_path',
        'flag',
    ];

    protected $appends = [
        'signed_url'
    ];

    public function target()
    {
        return $this->morphTo();
    }

    public function getTitle()
    {
        $title = 'report.pdf';
        if ($this->target_type == RiskRegister::class) {
            $title = $this->target->periode->format('Y') . ' Risk Register.pdf';
        } elseif ($this->target_type == InherentRisk::class) {
            $title = $this->target->riskRegisterDetail->riskRegister->periode->format('Y') . ' Inherent Risk.pdf';
        } elseif ($this->target_type == CurrentRisk::class) {
            $title = $this->target->riskRegisterDetail->riskRegister->periode->format('Y') . ' Current Risk.pdf';
        } elseif ($this->target_type == Assignment::class) {
            $title = $this->target->summary->rkia->year . ' Surat Penugasan ' . $this->target->letter_manual . '.pdf';
        } elseif ($this->target_type == Apm::class) {
            $title = $this->target->summary->rkia->year . ' Audit Program ' . $this->target->overview . '.pdf';
        } elseif ($this->target_type == MemoOpening::class) {
            $title = $this->target->summary->rkia->year . ' Memo Opening ' . $this->target->no_memo . '.pdf';
        } elseif ($this->target_type == Opening::class) {
            $title = $this->target->summary->rkia->year . ' Opening ' . $this->target->summary->memoOpening->no_memo . '.pdf';
        } elseif ($this->target_type == KkaSample::class) {
            $title = $this->target->summary->rkia->year . ' Kertas Kerja ' . $this->target->no_kka . '.pdf';
        } elseif ($this->target_type == KkaFeedback::class) {
            $title = $this->target->sampleDetail->sample->summary->rkia->year . ' Tanggapan ' . $this->target->sampleDetail->id_temuan . '.pdf';
        } elseif ($this->target_type == KkaWorksheet::class) {
            $title = $this->target->sampleDetail->sample->summary->rkia->year . ' Opini & Rekomendasi ' . $this->target->sampleDetail->id_temuan . '.pdf';
        } elseif ($this->target_type == KkaCommitment::class) {
            $title = $this->target->sampleDetail->sample->summary->rkia->year . ' Komentar Manajemen ' . $this->target->sampleDetail->id_temuan . '.pdf';
        } elseif ($this->target_type == MemoClosing::class) {
            $title = $this->target->summary->rkia->year . ' Memo Closing ' . $this->target->no_memo . '.pdf';
        } elseif ($this->target_type == Closing::class) {
            $title = $this->target->summary->rkia->year . ' Closing ' . $this->target->summary->memoClosing->no_memo . '.pdf';
        } elseif ($this->target_type == Lha::class) {
            $title = $this->target->summary->rkia->year . ' LHA ' . $this->target->no_memo . '.pdf';
        } elseif ($this->target_type == MemoTindakLanjut::class) {
            $title = $this->target->reg->summary->rkia->year . ' Memo Tindak Lanjut ' . $this->target->no_memo . '.pdf';
        } elseif ($this->target_type == FollowupReschedule::class) {
            $title = $this->target->regItem->reg->summary->rkia->year . ' Jadwal Ulang ' . $this->target->regItem->sampleDetail->id_temuan . '.pdf';
        } elseif ($this->target_type == FollowupMonitor::class) {
            $title = $this->target->regItem->reg->summary->rkia->year . ' Monitoring ' . $this->target->regItem->sampleDetail->id_temuan . '.pdf';
        } elseif ($this->target_type == FollowupReview::class) {
            $title = $this->target->monitor->regItem->reg->summary->rkia->year . ' Review Monitoring ' . $this->target->monitor->regItem->sampleDetail->id_temuan . '.pdf';
        }
        return $title;
    }

    public function getSignedUrlAttribute()
    {


        return URL::temporarySignedRoute(
            'report.print',
            now()->addMinutes(30),
            [
                'id'        => $this->id,
                'title'     => $this->getTitle(),
            ]
        );
    }
}
