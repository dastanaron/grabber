<?php
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
    
});

use classes\grabber\Grabber;

/*
 * Пример
 */

$parse = new Grabber('http://php.net/', ['include'=>true]);

dump( $parse->PathQuery('a', 'class', 'brand')
        ->AddPathQuery('img')
        ->PathExec()
        ->getAttributeOne('src'));

/*
 * Выведет
 *   string(26) "/images/logos/php-logo.svg"
 */




/*
 * Дополнительные и вспомогательные функции
 */
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
