<?php

namespace App\Qlik;

class QlikTicket
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function retrieveQlikTicket()
    {
        $response = $this->retrieveAll();
        $ticket = $response->Ticket;
        return $ticket;
    }

    public function retrieveAll()
    {
        $QRSurl = 'https://example.com:4243/qps';
        $appName = 'qss-pro';
        $QRSClientCertfile = __DIR__ . '/QlikSenseCertificates/client.pem';
        $QRSClientCertKeyfile = __DIR__ . '/QlikSenseCertificates/client_key.pem';

        //Endpoint to call (with xrfkey parameter added)
        $xrfkey = mt_rand(100000000000000, 999999999999999) . 'j';
        $endpoint = '/' . $appName . '/ticket?xrfkey=' . $xrfkey;
//        $userDirectory = 'QLIK';
        //Set up the required headers
        $headers = array(
            'X-Qlik-Xrfkey: ' . $xrfkey,
            'Accept: application/json',
            'Content-Type: application/json');
        $req_fields = array(
//            'UserDirectory' => $userDirectory,
            'UserId' => $this->userId,
            'Attributes' => array(),
        );
        //Create Connection using Curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $QRSurl . $endpoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_CERTINFO, 1);
        curl_setopt($ch, CURLOPT_SSLKEY, $QRSClientCertKeyfile);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM");
        curl_setopt($ch, CURLOPT_SSLCERT, $QRSClientCertfile);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_fields));
        //Execute and print response
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            throw new \Exception("cURL error ({$errno}):\n {$error_message}");
        }
        $response = json_decode($data);
        return $response;
    }
}