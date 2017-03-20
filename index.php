<?php
spl_autoload_register(function ($class) {
    //require_once './' . $class . '.php';
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
    
});

use classes\grabber\GetUrl;

$parse = new GetUrl('http://www.php.su/');

echo filterspaces($parse->getObject()->getValueOne('p', 'class', 'phpsulogo')) . PHP_EOL;

//$xpath = $dom->getPath('ul', 'class', 'site-footer-links float-right');

/*foreach( $xpath as $obj ) {
	
	dump($obj->nodeValue);
}*/

//dump($parse);

//dump($dom->getValueArray('p', 'class', 'phpsulogo'));

function dump($data, $mode = 'console') {
    
    if ($mode=='console') {
        var_dump($data);
    }
    else {    
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}

function filterspaces($data){
    
    $data = preg_replace('#\s#', '', $data);
    $data = preg_replace('#(.*)([a-zA-Z\.0-9]*)#U', '$2', $data);
    
    return $data;
    
}