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
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout in seconds

        $result = curl_exec($curl);
        curl_close($curl);

        if ($currentRedirection < 6 &&
            isset($result) &&
            is_string($result) &&
            (preg_match("/window.location.replace('(.*)')/i", $result, $value) ||
                preg_match("/window.location\s*=\s*[\"'](.*)[\"']/i", $result, $value) ||
                preg_match("/meta\s*http-equiv\s*=\s*[\"']refresh['\"]\s*content=[\"']\d+;url\s*=\s*(.*)['\"]/i", $result, $value) ||
                preg_match("/location.href\s*=\s*[\"'](.*)[\"']/i", $result, $value))) {
            return $this->virtualCurl($isoCode, $value[1], $userAgent, ++$currentRedirection);
        } else {
            return 'LastURL=';
        }
    }

    public function run()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $virtualLog = $this->argument;
        $type = ($virtualLog->allow > 4) ? 0 : 1;

        if ($app->cache->has('agents'.$type)) {
            $trueAgent = $app->cache->get('agents'.$type);
        } else {
            $trueAgent = $app->db->table('agents')->where('type', $type)->inRandomOrder()->limit(1)->first()->agent;
            $app->cache->put('agents'.$type, $trueAgent, 1);
        }

        $userCountry = str_replace(' ',',', strtolower($virtualLog->country));
        if (strpos(',', $userCountry) !== false) {
            $userCountry = explode(',', $userCountry);
            $userCountry = $userCountry[0];
        }
        $link = $virtualLog->link;
        $response = $this->virtualCurl($userCountry, $link, $trueAgent);

        $app->db->connection('external')
            ->table('logs')
            ->where('id', $virtualLog->id)
            ->update([
                'agent' => $trueAgent,
                'response' => $response,
                'sent' => true
            ]);
    }
}