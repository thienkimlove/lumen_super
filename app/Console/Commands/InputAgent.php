<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class InputAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'input:agent';

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
       $ios = resource_path('agents/ios2.txt');
       //$android = resource_path('agents/android.txt');

       $iosLines = file($ios, FILE_IGNORE_NEW_LINES);
       foreach ($iosLines as $iosLine) {
           if ($iosLine) {
               app('db')->table('agents')->insert([
                   'agent' => trim($iosLine),
                   'type' => 0
               ]);
           }
       }
       /* $androidLines = file($android, FILE_IGNORE_NEW_LINES);
        foreach ($androidLines as $androidLine) {
            if ($androidLine) {
                app('db')->table('agents')->insert([
                    'agent' => trim($androidLine),
                    'type' => 1
                ]);
            }
        }*/

    }
}
