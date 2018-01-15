<?php

namespace App\Console\Commands;

use App\Engine\TestThread;
use Carbon\Carbon;
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
          $contents = app('db')->connection('external')
              ->table('logs')
              ->whereNull('sent')
              ->orWhere('sent', false)
              ->orderBy('id', 'asc')
              //->limit(500)
              ->get();

          $stacks = [];

        foreach ($contents as $k => $content) {
            $stacks[$k] = new TestThread($content);
            $stacks[$k]->start(PTHREADS_INHERIT_NONE);
        }

        $this->line('Start at '.Carbon::now().' with '.count($stacks));

        foreach ($stacks as $t) {
            $t->join();
        }

       // $this->line('End at '.Carbon::now());

    }
}
