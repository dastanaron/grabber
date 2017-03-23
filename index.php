<?php
spl_autoload_register(function ($class) {
    //require_once './' . $class . '.php';
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
    
});

use classes\grabber\Grabber;

/*
 * Пример
 */

$parse = new Grabber('http://php.net/', ['include'=>true]);

dump( $parse->initDom()
        ->PathQuery('a', 'class', 'brand')
        ->AddPathQuery('img')
        ->PathExec()
        ->getAttributeOne('src'));

/*
 * Выведет
 *   
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
