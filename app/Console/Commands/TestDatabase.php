<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class TestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:db';

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
       $virtualLog = app('db')->connection('azoffers')->table('virtual_logs')->find(137335);

        app('db')->connection('azoffers')->table('virtual_logs')->where('id', 137335)->update([
            'allow_devices' => 1
        ]);

       $agent = app('db')->table('agents')->where('type', 1)->inRandomOrder()->limit(1)->get();

       dd($agent->first()->agent);

    }
}
