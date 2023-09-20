<?php

namespace App\Console\Commands;

use App\Mail\NotifyCourses;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email For All Student To Remember The Courses Every Day';

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
     * @return int
     */
    public function handle()
    {
        $emails = User::pluck('email')->toArray();

        foreach ($emails as $email){
            // send mail
            $student = User::where('email', $email)->with('Course')->first();
            Mail::to($email)->send(new NotifyCourses($student));
        }
    }
}
