<?php
namespace JsonRpc\Transport;

class BasicClient
{
    public $output = '';
    public $error  = '';

    public function send($method, $url, $json, $headers = array(), $httpParams = array())
    {
        $header = 'Content-Type: application/json';

        if (!in_array($header, $headers)) {
            $headers[] = $header;
        }

        $opts = array(
            'http' => array(
                'method'  => $method,
                'header'  => implode("\r\n", $headers),
                'content' => $json,
            ),
        );
        foreach ($httpParams as $hpk => $hpv) {
            if (!isset($opts['http'][$hpk])) {
                $opts['http'][$hpk] = $hpv;
            }
        }

        $context  = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $this->error = 'Unable to connect to ' . $url;
            return;
        }

        $this->output = $response;

        return true;
    }

}
