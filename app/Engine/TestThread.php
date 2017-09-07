<?php

namespace App\Engine;

class TestThread extends \Thread
{
    private $argument;

    public function __construct($arg)
    {
        $this->argument = $arg;
    }

    private function virtualCurl($isoCode, $url, $userAgent, $currentRedirection = 0)
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

        $result = curl_exec($curl);
        curl_close($curl);

        if ($currentRedirection < 10 &&
            isset($result) &&
            is_string($result) &&
            (preg_match("/window.location.replace('(.*)')/i", $result, $value) ||
                preg_match("/window.location\s*=\s*[\"'](.*)[\"']/i", $result, $value) ||
                preg_match("/meta\s*http-equiv\s*=\s*[\"']refresh['\"]\s*content=[\"']\d+;url\s*=\s*(.*)['\"]/i", $result, $value) ||
                preg_match("/location.href\s*=\s*[\"'](.*)[\"']/i", $result, $value))) {
            return $this->virtualCurl($isoCode, $value[1], $userAgent, ++$currentRedirection);
        } else {
            return 'LastURL=' . $url;
        }
    }

    public function run()
    {

        $app = require __DIR__.'/../../bootstrap/app.php';
        try {
            $virtualLog = $this->argument;
            $type = ($virtualLog->allow_devices > 4) ? 0 : 1;
            $agent = $app->db->table('agents')->where('type', $type)->inRandomOrder()->limit(1)->get();

            $trueAgent = $agent->first()->agent;
            $userCountry = str_replace(' ',',', strtolower($virtualLog->user_country));
            if (strpos(',', $userCountry) !== false) {
                $userCountry = explode(',', $userCountry);
                $userCountry = $userCountry[0];
            }
            $link = env('DB2_SITE').'/check?offer_id='.$virtualLog->offer_id;
            $response = $this->virtualCurl($userCountry, $link, $trueAgent);

            $app->db->connection('external')
                ->table('virtual_logs')
                ->where('id', $virtualLog->id)
                ->update([
                    'user_agent' => $trueAgent,
                    'response' => $response,
                    'sent' => true
                ]);
        } catch (\Exception $e) {
            @file_put_contents(storage_path('logs/thread.log'), $e->getMessage(), FILE_APPEND);
        }
    }
}