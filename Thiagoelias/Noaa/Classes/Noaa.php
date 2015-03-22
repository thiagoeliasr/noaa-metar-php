<?php

namespace Thiagoelias\Noaa\Classes;

class Noaa
{
    private $icao;
    private $metar;
    private $taf;
    private $decoded;
    
    public function __construct($_icao = null) {
        if (!empty($_icao)) {
            $this->icao = $_icao;
        }
    }
    
    public function setIcao($_icao)
    {
        $this->icao = $_icao;
    }
    
    public function getIcao()
    {
        return $this->icao;
    }
    
    public function setMetar($_metar)
    {
        $this->metar = $_metar;
    }
    
    public function getMetar()
    {
        return $this->metar;
    }
    
    public function setTaf($_taf)
    {
        $this->taf = $_taf;
    }
    
    public function getTaf()
    {
        return $this->taf;
    }

    public function setDecoded($_decoded)
    {
        $this->decoded = $_decoded;
    }
    
    public function getDecoded()
    {
        return $this->decoded;
    }
    
}

