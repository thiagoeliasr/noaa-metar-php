<?php
//autoloading classes
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    
    # Support for non-namespaced classes.
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));

    $path = implode(DIRECTORY_SEPARATOR, $parts);

    $file = stream_resolve_include_path($path.'.php');
    if($file !== false) {
        require $file;
    }
});

use Thiagoelias\Noaa\Classes\NoaaRequest;
use Thiagoelias\Noaa\Classes\Noaa;
use Thiagoelias\Noaa\Classes\Exceptions\NoaaRequestException;

//creating a new NoaaRequest object
$noaaRequest = new NoaaRequest();

try {
    //making a request with a new Noaa Object (I'm already passing the icao in constructor)
    $noaa = $noaaRequest->request(new Noaa("SBST"));
    
    if (is_object($noaa)) {
        echo "METAR: " . $noaa->getMetar() . "\n";
        echo "TAF: " . $noaa->getTaf() . "\n";
        echo "DECODED: " . $noaa->getDecoded() . "\n";
    }
    
} catch (NoaaRequestException $e) {
    echo "Error: " . $e->getMessage();
}

