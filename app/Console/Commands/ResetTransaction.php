<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResetTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset data transaksi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->output->title($this->description);

        try {
            Schema::disableForeignKeyConstraints();

            // DB::table('sys_activities')->truncate();
            // DB::table('sys_approvals')->truncate();
            // DB::table('sys_failed_jobs')->truncate();
            // DB::table('sys_files')->truncate();
            // DB::table('sys_jobs')->truncate();
            // DB::table('sys_notifications')->truncate();
            // DB::table('sys_notifications_users')->truncate();

            // DB::table('temp_files')->truncate();
            // DB::table('trans_apm')->truncate();
            // DB::table('trans_apm_cc')->truncate();
            // DB::table('trans_apm_details')->truncate();
            // DB::table('trans_apm_users')->truncate();
            // DB::table('trans_assignments')->truncate();
            // DB::table('trans_assignments_aspects')->truncate();
            // DB::table('trans_assignments_cc')->truncate();
            // DB::table('trans_assignments_members')->truncate();
            // DB::table('trans_assignments_users')->truncate();
            // DB::table('trans_closing')->truncate();
            // DB::table('trans_closing_details')->truncate();
            // DB::table('trans_closing_details_parts')->truncate();
            // DB::table('trans_extern_followups')->truncate();
            // DB::table('trans_extern_regs')->truncate();
            // DB::table('trans_extern_reg_details')->truncate();
            // DB::table('trans_followup_monitors')->truncate();
            // DB::table('trans_followup_regs')->truncate();
            // DB::table('trans_followup_regs_items')->truncate();
            // DB::table('trans_kka_feedbacks')->truncate();
            // DB::table('trans_kka_samples')->truncate();
            // DB::table('trans_kka_samples_details')->truncate();
            // DB::table('trans_kka_worksheets')->truncate();
            // DB::table('trans_kka_worksheets_cc')->truncate();
            // DB::table('trans_lha')->truncate();
            // DB::table('trans_meetings')->truncate();
            // DB::table('trans_meetings_cc')->truncate();
            // DB::table('trans_meetings_parts')->truncate();
            // DB::table('trans_meetings_recap')->truncate();
            // DB::table('trans_meetings_recap_cc')->truncate();
            // DB::table('trans_opening')->truncate();
            // DB::table('trans_opening_details')->truncate();
            // DB::table('trans_opening_details_parts')->truncate();
            // DB::table('trans_programs')->truncate();
            // DB::table('trans_programs_cc')->truncate();
            // DB::table('trans_programs_details')->truncate();
            // DB::table('trans_rkia')->truncate();
            // DB::table('trans_rkia_cc')->truncate();
            // DB::table('trans_rkia_summary')->truncate();
            // DB::table('trans_rkia_summary_members')->truncate();
            // DB::table('trans_surveys_regs')->truncate();
            // DB::table('trans_surveys_regs_users')->truncate();
            // DB::table('trans_surveys_regs_users_details')->truncate();
            // DB::table('trans_training_auditors')->truncate();

            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
