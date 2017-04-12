<?php

namespace classes\grabber;

use classes\debug\Logger;

class Grabber {
    
    protected $url;
    
    protected $timeout;
    
    protected $response;
    
    protected $dom;

    protected $xpath;
    
    protected $result_query;
    
    protected $search;
    
    protected $logger;

    /*
     * Устанавливаем начальные параметры и включаем логер, если требуется
     */
    public function __construct($url, $loggerparam=array('include'=>false), $timeout=30 ) {
        
        if (preg_match('#^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$#', $url)) {
        
            $this->url = $url;
            $this->timeout = $timeout;

            /*
             * Сразу инициируем дом и он же нам запишет два обхекста в свойства xpath и dom
             */
            $this->initDom();
        
        }
        else {
            throw new \Exception('Не верный формат URL');
        }
        
        if (!empty($loggerparam) && !empty($loggerparam['include']) && $loggerparam['include'] === true) {
            
            $this->setLoggerParam($loggerparam);
            
        }
        $this->Log('============Инициализация объекта Grabber============');
        
    }
    
    private function Log($string) {
        
        if (!empty($this->logger) && is_object($this->logger)) {
            
            $this->logger->Log($string);
            
        }
        else {
            
            return false;
            
        }
        
    }


    
    private function setLoggerParam($loggerparam=array()) {
        
        if (!empty($loggerparam['logname'])) {
            
            $logname = $loggerparam['logname'];
            
        }
        else {
            
            $logname = 'work.log';
            
        }
        
        if (!empty($loggerparam['log_dir'])) {
            
            $log_dir = $loggerparam['log_dir'];
            
        }
        else {
            
            $log_dir='/logs/';
            
        }
        
        
        
        $logger = new Logger($logname, $log_dir);
        
        $this->logger = $logger;
        
        
    }
    
    /*
     * Функция делающая запросы к адресу
     */
    private function CurlUrl() {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => $this->timeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          //CURLOPT_HTTPHEADER => array(),
        ));
        
        $this->Log('Отправлен запрос на адрес ' . $this->url);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            
          $this->Log('Запрос вернул ошибку: ' . $err);
          return $err;
          
        } else {
          
          $this->Log('Запрос вернул HTML содержимое');
          $this->response = $response;
          
        }  
        
    }
    
    /*
     * Установка Объекта дом
     */
    
    public function initDom() {
          
        $this->CurlUrl();
        
        $dom = new \DomDocument();
        @$dom->loadHTML($this->response);
        
        $this->dom = $dom;

        $this->xpath = new \DomXPath($this->dom);
        
        $this->Log('Инициирован DomDocument объект и объект DomXPath');
        return $this;
          
    }
    
    /*
     * Вернуть DomDocument-объект для целей минующих данную библиотеку
     */
    
    public function getDom() {
        
        
        $this->Log('Получен DomDocument объект');
        return $this->dom;
        
    }

    /*
     * Вернуть DomXPath-объект для других целей
     */

    public function getXpath(){

        $this->Log('Получен DomXPath объект');
        return $this->xpath;

    }

    /*
     * Вернуть просто HTML содержимое URL
     */
    
    public function getHTML() {
        
        $this->CurlUrl();
        $this->Log('Получено HTML содержимое запроса');
        return $this->response;
        
    }
    
    private function ValidateSearch($search, $tag, $selector, $value) {
        
        if (empty($tag)) {
            
            $this->Log('Возникла ошибка валидации поискового запроса: не передан обязательный параметр [tag]');
            throw new \Exception('Не передан обязательный параметр');
            
        }
        else {
            $search .= "/{$tag}";
        }
        
        if (!empty($selector)) {
            
            if(!empty($value)) {
                $search .= "[@{$selector}='{$value}']";
            }
            else {
                
                $this->Log('Возникла ошибка валидации поискового запроса: передан селектор, но не передано его значение');
                throw new \Exception('Передан селектор, но не передано его значение');
                
            }
            
        }
        
        return $search;
        
    }


    /*
     * Инициация объекта Класса DomXPath и всоставляет запрос
     */
    public function PathQuery($tag, $selector='', $value='') {
        
        $search = "./";
        
        $search = $this->ValidateSearch($search, $tag, $selector, $value);
        
        $this->search = $search;
        
        $this->Log('Составлен поисковый запрос: ' . $search);
        
        return $this;
        
    }
    
    /*
     * Добавляет условия к запросу
     */
    public function AddPathQuery($tag, $selector='', $value='') {
        
        $search = '';
        
        $search = $this->ValidateSearch($search, $tag, $selector, $value);
        
        $this->search .= $search;
        
        $this->Log('К поисковому запросу добавлены дополнительные условия: ' . $search);

        $this->Log('Общие поисковые условия: ' . $this->search);
        
        return $this;
        
        
    }
    
    
    /*
     * Выполняет поисковый запрос
     */
    public function PathExec() {

        $res = $this->xpath->query($this->search);
        
        $this->result_query = $res;
        
        
        if ($this->result_query->length == 0) {
            
            $this->Log('Поисковый запрос выполнен и ничего не вернул');
        
        }
        else {
            $this->Log('Поисковый запрос выполнен и вернул объект с содержимым');
        }
        
        return $this;
        
    }
    
    /*
     * Получить один атрибут выборки аттрибут
     */
    
    public function getAttributeOne($attributename) {
        
        $this->Log('Получен атрибут с первым совпадением поиска');
        
        foreach ($this->result_query as $object) {
            
            return $object->getAttribute($attributename);
            
        }
        
    }
    
    /*
     * Получить все атрибуты выборки
     */
    
    public function getAttributeArray($attributename) {
        
        $this->Log('Получен массив с атрибутами');
        
        $array = array();
        
        foreach ($this->result_query as $object) {
            $array[] = $object->getAttribute($attributename);
        }
        
        return $array;
        
    }
    
    /*
     * Получить один элемент первого совпадения без html
     */
    
    public function getValueOne() {
        
        $this->Log('Получено значение первого совпадения');
        
        foreach ($this->result_query as $object) {
            return $object->nodeValue;
        }
        
    }
    
    /*
     * Получить все совпадения в массиве
     */
    
    public function getValueArray() {
        
        $this->Log('Получен массив значений');
        
        $array = array();
        
        foreach ($this->result_query as $object) {
            $array[] = $object->nodeValue;
        }
        
        return $array;
        
    }
    
    
    /*
     * Получить первое содержимое вместе с HTML
     */
    public function getHTMLOne() {
        
        $this->Log('Получен HTML первого совпадения');
        
        foreach ($this->result_query as $object) {
            return $object->ownerDocument->saveHTML($object);
        }
        
    }
    
    /*
     * Получить массив всех совпадений вместе с HTML
     */
    
    public function getHTMLArray() {
        
        $this->Log('Получен массив HTML содержимого');
        
        $array = array();
        
        foreach ( $this->result_query as $object) {
            
            $array[] = $object->ownerDocument->saveHTML($object);
            
        }
        
        return $array;
        
    }
    
    function __destruct() {
        
        $this->Log('==================Работа завершена===================');
        
    }
    
}



