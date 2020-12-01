<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class cronRequestForwarding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forwardRequest:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'automatically forward leave request after 3 days pending ';

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
        // \Log::info("Request handled!");
        $results = DB::select('call AutoForwardLeaveRequests()');
        $forwarded = collect($results)[0]->forwarded;
        if ($forwarded > 0) {
            
        }
        $this->info('Job just started');


    }
}
