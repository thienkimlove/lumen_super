<?php

namespace App\Engine;

class ManualThread extends \Thread
{
    private $argument;

    public function __construct($arg)
    {
        $this->argument = $arg;
    }

    private function virtualCurl($isoCode, $url, $userAgent)
    {
        $username = 'lum-customer-theway_holdings-zone-nam-country-' . strtolower($isoCode);
        $password = '99oah6sz26i5';
        $port = 22225;
        $session = mt_rand();
        $super_proxy = 'zproxy.luminati.io';
        $url = str_replace("&amp;", "&", urldecode(trim($url)));
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-session-$session:$password");
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout in seconds
        //$result = curl_exec($curl);
        curl_close($curl);

        return true;
    }

    public function run()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $virtualLog = $this->argument;
        $type = random_int(0, 1);
        $agent = $app->db->table('agents')->where('type', $type)->inRandomOrder()->limit(1)->get();
        $trueAgent = $agent->first()->agent;
        $response = $this->virtualCurl($virtualLog->country, $virtualLog->url, $trueAgent);

        app('db')->table('clicks')->where('id', $virtualLog->id)->update(['sent' => true]);
    }
}