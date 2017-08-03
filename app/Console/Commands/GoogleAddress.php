<?php

namespace App\Console\Commands;

use App\Engine\TestThread;
use Illuminate\Console\Command;

class GoogleAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
          $contents = app('db')->connection('azoffers')
              ->table('virtual_logs')
              ->where('sent', false)
              ->orderBy('created_at', 'asc')
              //->limit(10)
              ->get();

          $stacks = [];

        foreach ($contents as $content) {
            $stacks[] = new TestThread($content);
        }

        foreach ($stacks as $t) {
            $t->start(PTHREADS_INHERIT_NONE);
        }

    }
}
