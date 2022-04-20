<?php

namespace Pulsor;

use Exception;

class Monitor
{

    public function __construct($servicesUrl = false)
    {
        if(!$servicesUrl)
            $servicesUrl = $_SERVER["DOCUMENT_ROOT"] . '/pulsor.json';
        try {
            $servicesData = file_get_contents($servicesUrl);
            if(!$servicesData)
                throw new Exception('Error opening config file');
            $this->services = json_decode($servicesData, TRUE);
            if(!is_array($this->services))
                throw new Exception('Incorrect config file format');
        } catch (Exception $e) {
            $this->services['error'] = $e->getMessage();
        }
    }

    static function start($service = false)
    {

        $response = [];

        $monitor = new Monitor();

        if(!$service)
        {
            if($monitor->services['error'])
                return $monitor->services;

            foreach ($monitor->services as $serviceName => $serviceConfig)
                $response[$serviceName] = $monitor->checkService($serviceConfig);
        } else $monitor->checkService($service);

        return json_encode($response);

    }

    private function checkService($config)
    {

        $response = [];

        $resource = file_get_contents($config['url']);
        foreach ($config['checkpoints'] as $checkpoint)
        {
            if(strpos($resource, $checkpoint) !== false)
            {
                $response['success'][] =  $checkpoint;
            } else $response['error'][] =  $checkpoint;
        }

        return $response;

    }

}