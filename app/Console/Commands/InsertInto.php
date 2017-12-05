<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InsertInto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:into {--url=} {--country=}';

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
        $country = $this->option('country');
        $url = $this->option('url');


        for ($i = 0; $i < 30000; $i++) {
            app('db')->table('clicks')->insert([
                'url' => $url,
                'country' => $country
            ]);
        }

    }
}
