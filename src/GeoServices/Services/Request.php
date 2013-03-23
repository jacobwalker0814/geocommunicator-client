<?php

namespace GeoServices\Services;

class Request
{
    private static $instances = array();

    /**
     * @var SoapClient
     */
    private $soapClient;

    private $lat;
    private $long;
    private $error = false;

    private $state;
    private $principalMeridian;
    private $townshipNumber;
    private $townshipFraction;
    private $townshipDirection;
    private $rangeNumber;
    private $rangeFraction;
    private $rangeDireciton;
    private $section;
    private $townshipDuplicate;

    private function __construct($lat, $long)
    {
        $this->lat  = $lat;
        $this->long = $long;

        $this->soapClient = new \SoapClient('http://www.geocommunicator.gov/TownshipGeocoder/TownshipGeocoder.asmx?wsdl',
            array(
                'trace'        => true,
                'soap_version' => SOAP_1_2,
                'cache_wsdl'   => WSDL_CACHE_NONE
            )
        );

        $params = array(
            'Lat'   => $this->lat,
            'Lon'   => $this->long,
            'Units' => 'eDD',
            'Datum' => 'NAD83'
        );
        $result = $this->soapClient->GetTRS($params)->TownshipGeocoderResult;

        if ($result->CompletionStatus) {
            $xml = simplexml_load_string($result->Data);
            $data = explode(',', $xml->channel->item[1]->description);
            $this->setState($data[0]);
            $this->setPrincipalMeridian($data[1]);
            $this->setTownshipNumber($data[2]);
            $this->setTownshipFraction($data[3]);
            $this->setTownshipDirection($data[4]);
            $this->setRangeNumber($data[5]);
            $this->setRangeFraction($data[6]);
            $this->setRangeDireciton($data[7]);
            $this->setSection($data[8]);
            $this->setTownshipDuplicate($data[9]);
        } else {
            $this->setError($result->Message);
        }
    }

    public static function getInstance($lat, $long)
    {
        if (!isset(self::$instances[$lat][$long])) {
            self::$instances[$lat][$long] = new Request($lat, $long);
        }

        return self::$instances[$lat][$long];
    }

    /**
     * Ensure identity pattern. Don't allow clone
     */
    private function __clone()
    {
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLong()
    {
        return $this->long;
    }

    public function getState()
    {
        return $this->state;
    }

    protected function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getPrincipalMeridian()
    {
        return $this->principalMeridian;
    }

    protected function setPrincipalMeridian($principalMeridian)
    {
        $this->principalMeridian = $principalMeridian;

        return $this;
    }

    public function getTownshipNumber()
    {
        return $this->townshipNumber;
    }

    protected function setTownshipNumber($townshipNumber)
    {
        $this->townshipNumber = $townshipNumber;

        return $this;
    }

    public function getTownshipFraction()
    {
        return $this->townshipFraction;
    }

    protected function setTownshipFraction($townshipFraction)
    {
        $this->townshipFraction = $townshipFraction;

        return $this;
    }

    public function getTownshipDirection()
    {
        return $this->townshipDirection;
    }

    protected function setTownshipDirection($townshipDirection)
    {
        $this->townshipDirection = $townshipDirection;

        return $this;
    }

    public function getRangeNumber()
    {
        return $this->rangeNumber;
    }

    protected function setRangeNumber($rangeNumber)
    {
        $this->rangeNumber = $rangeNumber;

        return $this;
    }

    public function getRangeFraction()
    {
        return $this->rangeFraction;
    }

    protected function setRangeFraction($rangeFraction)
    {
        $this->rangeFraction = $rangeFraction;

        return $this;
    }

    public function getRangeDireciton()
    {
        return $this->rangeDireciton;
    }

    protected function setRangeDireciton($rangeDireciton)
    {
        $this->rangeDireciton = $rangeDireciton;

        return $this;
    }

    public function getSection()
    {
        return $this->section;
    }

    protected function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    public function getTownshipDuplicate()
    {
        return $this->townshipDuplicate;
    }

    protected function setTownshipDuplicate($townshipDuplicate)
    {
        $this->townshipDuplicate = $townshipDuplicate;

        return $this;
    }
}
