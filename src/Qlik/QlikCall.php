<?php

namespace App\Qlik;

class QlikCall
{
    /**
     * @param $extensionPage
     * @return array
     * @throws \Exception
     */
    public function retrieveParametersCall($extensionPage)
    {
        $host = 'example.com';
        $protocol = 'https';
        $prefix = '/qss-pro/';
        $extensionName = 'example-pro';
        switch ($extensionPage) {
            case 'PAGE1':
                $extensionPage = 'EXAMPLE_PRO_PAGE1';
                $extensionStartState = 'start1';
                break;
            case 'PAGE2':
                $extensionPage = 'EXAMPLE_PRO_PAGE1';
                $extensionStartState = 'start2';
                break;
            default:
                throw new \InvalidArgumentException('Must be a valid extension page environment');
                break;
        }

        return [
            'host' => $host,
            'protocol' => $protocol,
            'prefix' => $prefix,
            'extension_name' => $extensionName,
            'start_state' => $extensionStartState,
            'extension_environment' => $extensionPage,
        ];
    }
}