<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CakeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cake:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will automate the query to database and send email';

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
        $results = DB::select('call AutoForwardLeaveRequests()');
        $forwarded = collect($results)[0]->forwarded;
        \Log::info("Request Forwarded!");
        $this->info('Request Forwarded');

    }
}
