<?php

namespace App\Console\Commands;

use App\Mail\Notification;
use App\Repositories\ProfileRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NewUserReportCommand extends Command
{
    use CustomOutput;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:new-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New user weekly report';

    protected $profileRepository;

    /**
     * Create a new command instance.
     *
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentWeek = Carbon::now()->startOfWeek()->startOfDay();
        $lastWeek = $currentWeek->copy()->subDays(7);

        $this->info('Begin report new user from [' . $lastWeek . '] to [' . $currentWeek . ']');

        try {
            $reportToEmails = !empty(env('REPORT_MAIL_TO')) ? explode(',', env('REPORT_MAIL_TO')) : null;
            if (!empty($reportToEmails)) {
                // Get list of new users a week ago
                $profiles = $this->profileRepository->getListProfileAfterDays(7)->toArray();
                $this->info('Total ' . count($profiles) . ' profile(s)');
                if (count($profiles) > 0) {
                    Mail::to($reportToEmails)->send(new Notification([
                        'subject' => '先週の新規ギルドメンバー',
                        'profiles' => $profiles,
                        'cc' => !empty(env('REPORT_MAIL_CC')) ? explode(',', env('REPORT_MAIL_CC')) : null,
                        'bcc' => !empty(env('REPORT_MAIL_BCC')) ? explode(',', env('REPORT_MAIL_BCC')) : null,
                        'template' => 'new_user_report'
                    ]));
                } else {
                    Mail::to($reportToEmails)->send(new Notification([
                        'subject' => '先週の新規ギルドメンバー',
                        'cc' => !empty(env('REPORT_MAIL_CC')) ? explode(',', env('REPORT_MAIL_CC')) : null,
                        'bcc' => !empty(env('REPORT_MAIL_BCC')) ? explode(',', env('REPORT_MAIL_BCC')) : null,
                        'template' => 'no_new_user_report'
                    ]));
                }
            } else {
                $this->error('Cannot report because have no email to in configuration. Please check .env file');
            }
        } catch (\Exception $exception) {
            $this->error('Cannot send email with error: ' . $exception->getMessage());
        }

        $this->info('End report new user from [' . $lastWeek . '] to [' . $currentWeek . ']');
    }
}
