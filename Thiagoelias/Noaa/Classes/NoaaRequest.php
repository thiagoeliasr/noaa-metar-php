<?php

namespace Thiagoelias\Noaa\Classes;

use Thiagoelias\Noaa\Classes\Noaa;
use Thiagoelias\Noaa\Classes\Exceptions\NoaaRequestException;

class NoaaRequest
{
    const TYPE_METAR = 1;
    const TYPE_TAF = 2;
    const TYPE_DECODED = 3;
    const URL_METAR = "http://weather.noaa.gov/pub/data/observations/metar/stations/";
    const URL_TAF = "http://weather.noaa.gov/pub/data/forecasts/taf/stations/";
    const URL_DECODED = "http://weather.noaa.gov/pub/data/observations/metar/decoded/";
    
    /**
     * This method will perform a request to Noaa,  
     * saving the result into a Noaa Object.
     * @param \Thiagoelias\Noaa\Classes\Noaa $noaa 
     * @param int $type type of request (metar, taf, decoded)
     * @return \Thiagoelias\Noaa\Classes\Noaa
     * @throws NoaaRequestException
     */
    public function request(Noaa $noaa, $type = null)
    {
        try {
            switch ($type) {
                case self::TYPE_METAR:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setMetar($requestResult);
                    break;
                case self::TYPE_TAF:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setTaf($requestResult);
                    break;
                case self::TYPE_DECODED:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setDecoded($requestResult);
                    break;
                default:
                    /* if $type is null, I'll fetch everything (please be warned
                     * that this may result in performance loss, due to
                     * the 3 requests that will be made.*/
                    $requestResultMetar = $this
                        ->getData($noaa->getIcao(), self::TYPE_METAR);
                    $noaa->setMetar($requestResultMetar);

                    $requestResultTaf = $this
                            ->getData($noaa->getIcao(), self::TYPE_TAF);
                    $noaa->setTaf($requestResultTaf);

                    $requestResultDecoded = $this
                            ->getData($noaa->getIcao(), self::TYPE_DECODED);
                    $noaa->setDecoded($requestResultDecoded);
                    break;
            }
        } catch (NoaaRequestException $exception) {
            throw new NoaaRequestException($exception->getMessage());
        }
        
        return $noaa;
    }
    
    /**
     * Will get NOAA text data for the desired Airport ICAO code
     * @param String $icao
     * @param int $type
     * @return String
     * @throws exceptions\NoaaRequestException
     */
    private function getData($icao, $type)
    {
        $result = null;
        
        if (empty($icao)) {
            throw new exceptions\NoaaRequestException("Invalid ICAO code");
        }
        
        switch ($type) {
            case self::TYPE_METAR:
                $result = self::curl(self::URL_METAR . strtoupper($icao) . ".TXT");
                break;
            case self::TYPE_TAF:
                $result = self::curl(self::URL_TAF . strtoupper($icao) . ".TXT");
                break;
            case self::TYPE_DECODED:
                $result = self::curl(self::URL_DECODED . strtoupper($icao) . ".TXT");
                break;
            default:
                throw new exceptions\NoaaRequestException("Invalid Request Type");
        }
        
        return $result;
    }
    
    /**
     * Perform a cURL request
     * @param String $url String containing the URL that will be fetched
     * @return String
     */
    private static function curl($url)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }
    
}